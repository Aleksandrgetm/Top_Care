<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function create(): View|RedirectResponse
    {
        $cart = $this->cart();

        if ($cart === []) {
            return redirect()->route('cart.index')->with('error', 'Grozs ir tukšs. Pirms noformēšanas pievienojiet preces.');
        }

        return view('shop.checkout', [
            'title' => 'Checkout | Top Care Group',
            'description' => 'Noformējiet Top Care Group pasūtījumu bez reģistrācijas.',
            'canonical' => '/checkout',
            'items' => array_values($cart),
            'cartTotal' => $this->cartTotal($cart),
            'cartCount' => $this->cartCount($cart),
            'deliveryMethods' => $this->deliveryMethods(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $cart = $this->cart();

        if ($cart === []) {
            return redirect()->route('cart.index')->with('error', 'Grozs ir tukšs. Pirms noformēšanas pievienojiet preces.');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email:rfc', 'max:255'],
            'delivery_address' => ['required', 'string', 'max:1000'],
            'delivery_method' => ['required', 'in:' . implode(',', array_keys($this->deliveryMethods()))],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $productIds = array_map(static fn (array $item) => (int) $item['product_id'], $cart);
        $products = Product::query()
            ->whereIn('id', $productIds)
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        $normalizedItems = [];
        $totalPrice = 0.0;

        foreach ($cart as $item) {
            $productId = (int) $item['product_id'];
            $product = $products->get($productId);

            if (! $product) {
                return redirect()->route('cart.index')->with('error', "Prece \"{$item['name']}\" vairs nav pieejama.");
            }

            $quantity = (int) $item['quantity'];
            $availableQuantity = max(0, (int) $product->stock_quantity);

            if ($availableQuantity < 1) {
                return redirect()->route('cart.index')->with('error', "Prece \"{$product->name}\" pašlaik nav noliktavā.");
            }

            if ($quantity > $availableQuantity) {
                return redirect()->route('cart.index')->with('error', "Pieejamais daudzums precei \"{$product->name}\" ir {$availableQuantity} gab.");
            }

            $price = (float) $product->price;
            $lineTotal = $price * $quantity;
            $totalPrice += $lineTotal;

            $normalizedItems[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $price,
                'quantity' => $quantity,
                'total' => $lineTotal,
            ];
        }

        $order = DB::transaction(function () use ($validated, $normalizedItems, $totalPrice): Order {
            $order = Order::query()->create([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'],
                'delivery_address' => $validated['delivery_address'],
                'delivery_method' => $validated['delivery_method'],
                'comment' => $validated['comment'] ?? null,
                'total_price' => $totalPrice,
                'payment_method' => 'manual',
                'payment_status' => 'pending',
                'status' => 'new',
            ]);

            $order->orderItems()->createMany($normalizedItems);

            return $order;
        });

        session()->forget('cart');

        return redirect()
            ->route('checkout.thank-you', $order)
            ->with('status', 'Pasūtījums ir veiksmīgi izveidots.');
    }

    public function thankYou(Order $order): View
    {
        return view('shop.checkout-thank-you', [
            'title' => 'Paldies par pasūtījumu | Top Care Group',
            'description' => 'Top Care Group pasūtījums ir saņemts.',
            'canonical' => route('checkout.thank-you', $order, false),
            'order' => $order->loadMissing('orderItems'),
        ]);
    }

    public function addressSuggestions(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        if (mb_strlen($query) < 3) {
            return response()->json(['suggestions' => []]);
        }

        $provider = (string) config('services.address_autocomplete.provider', 'photon');
        $endpoint = (string) config('services.address_autocomplete.endpoint');
        $timeout = (float) config('services.address_autocomplete.timeout', 4);

        if ($provider !== 'photon' || $endpoint === '') {
            return response()->json(['suggestions' => []]);
        }

        try {
            $response = Http::acceptJson()
                ->timeout($timeout)
                ->withHeaders([
                    'User-Agent' => config('app.name', 'TopCare') . ' Address Lookup',
                ])
                ->get($endpoint, [
                    'q' => $query . ', Latvia',
                    'limit' => 6,
                    'lang' => 'lv',
                ]);

            if (! $response->successful()) {
                return response()->json(['suggestions' => []]);
            }

            $suggestions = collect($response->json('features', []))
                ->filter(function (array $feature): bool {
                    $countryCode = strtolower((string) data_get($feature, 'properties.countrycode', ''));
                    $country = mb_strtolower((string) data_get($feature, 'properties.country', ''));

                    return $countryCode === 'lv'
                        || $country === 'latvia'
                        || $country === 'latvija';
                })
                ->map(function (array $feature): ?array {
                    $properties = (array) data_get($feature, 'properties', []);
                    $parts = [
                        $this->formatStreetAddress($properties),
                        $properties['postcode'] ?? null,
                        $properties['city'] ?? $properties['district'] ?? $properties['county'] ?? null,
                    ];

                    $label = collect($parts)
                        ->filter(fn ($part) => filled($part))
                        ->implode(', ');

                    if ($label === '') {
                        $label = (string) ($properties['name'] ?? '');
                    }

                    if ($label === '') {
                        return null;
                    }

                    return [
                        'label' => $label,
                        'value' => $label,
                    ];
                })
                ->filter()
                ->unique('value')
                ->values()
                ->all();

            return response()->json(['suggestions' => $suggestions]);
        } catch (\Throwable) {
            return response()->json(['suggestions' => []]);
        }
    }

    private function cart(): array
    {
        return session('cart', []);
    }

    private function cartCount(array $cart): int
    {
        return (int) collect($cart)->sum('quantity');
    }

    private function cartTotal(array $cart): float
    {
        return (float) collect($cart)->sum(fn (array $item) => ((float) $item['price']) * ((int) $item['quantity']));
    }

    private function deliveryMethods(): array
    {
        return [
            'delivery_latvia' => 'Piegāde Latvijā',
            'pickup' => 'Saņemšana uz vietas',
        ];
    }

    private function formatStreetAddress(array $properties): ?string
    {
        $street = $properties['street'] ?? $properties['name'] ?? null;
        $houseNumber = $properties['housenumber'] ?? null;

        if (! filled($street) && ! filled($houseNumber)) {
            return null;
        }

        return trim(collect([$street, $houseNumber])->filter()->implode(' '));
    }
}
