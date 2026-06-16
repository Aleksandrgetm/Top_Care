<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
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
            'delivery_method' => ['required', Rule::in(array_keys($this->deliveryMethods()))],
            'city' => [Rule::requiredIf(fn () => $request->string('delivery_method')->toString() === 'delivery'), 'nullable', 'string', 'max:255'],
            'street' => [Rule::requiredIf(fn () => $request->string('delivery_method')->toString() === 'delivery'), 'nullable', 'string', 'max:255'],
            'house' => [Rule::requiredIf(fn () => $request->string('delivery_method')->toString() === 'delivery'), 'nullable', 'string', 'max:50'],
            'apartment' => ['nullable', 'string', 'max:50'],
            'postal_code' => [Rule::requiredIf(fn () => $request->string('delivery_method')->toString() === 'delivery'), 'nullable', 'string', 'max:50'],
            'delivery_comment' => ['nullable', 'string', 'max:1000'],
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

        $deliveryAddress = $this->composeDeliveryAddress($validated);
        $orderComment = $this->composeOrderComment(
            $validated['comment'] ?? null,
            $validated['delivery_comment'] ?? null
        );

        $order = DB::transaction(function () use ($validated, $normalizedItems, $totalPrice, $deliveryAddress, $orderComment): Order {
            $order = Order::query()->create([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'],
                'delivery_address' => $deliveryAddress,
                'delivery_method' => $validated['delivery_method'],
                'comment' => $orderComment,
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
                    $street = $this->extractStreet($properties);
                    $house = $this->extractHouse($properties);
                    $city = $this->extractCity($properties);
                    $postalCode = $this->extractPostalCode($properties);

                    $parts = [
                        filled($street) ? trim(collect([$street, $house])->filter()->implode(' ')) : null,
                        $postalCode,
                        $city,
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
                        'street' => $street ?: $label,
                        'house' => $house,
                        'city' => $city,
                        'postal_code' => $postalCode,
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
            'delivery' => 'Piegāde Latvijā',
            'pickup' => 'Saņemšana uz vietas',
        ];
    }

    private function composeDeliveryAddress(array $validated): string
    {
        if (($validated['delivery_method'] ?? null) !== 'delivery') {
            return 'Saņemšana uz vietas';
        }

        $streetLine = trim(collect([
            $validated['street'] ?? null,
            $validated['house'] ?? null,
        ])->filter(fn ($part) => filled($part))->implode(' '));

        $parts = [
            $streetLine,
            filled($validated['apartment'] ?? null) ? 'Dz. ' . trim((string) $validated['apartment']) : null,
            $validated['city'] ?? null,
            $validated['postal_code'] ?? null,
        ];

        return (string) collect($parts)
            ->filter(fn ($part) => filled($part))
            ->implode(', ');
    }

    private function composeOrderComment(?string $comment, ?string $deliveryComment): ?string
    {
        $parts = collect([
            filled($comment) ? 'Komentārs: ' . trim($comment) : null,
            filled($deliveryComment) ? 'Piegādes komentārs: ' . trim($deliveryComment) : null,
        ])->filter();

        return $parts->isNotEmpty() ? $parts->implode(PHP_EOL) : null;
    }

    private function extractStreet(array $properties): ?string
    {
        return filled($properties['street'] ?? null)
            ? trim((string) $properties['street'])
            : (filled($properties['name'] ?? null) ? trim((string) $properties['name']) : null);
    }

    private function extractHouse(array $properties): ?string
    {
        return filled($properties['housenumber'] ?? null) ? trim((string) $properties['housenumber']) : null;
    }

    private function extractCity(array $properties): ?string
    {
        $city = $properties['city'] ?? $properties['district'] ?? $properties['county'] ?? null;

        return filled($city) ? trim((string) $city) : null;
    }

    private function extractPostalCode(array $properties): ?string
    {
        return filled($properties['postcode'] ?? null) ? trim((string) $properties['postcode']) : null;
    }
}
