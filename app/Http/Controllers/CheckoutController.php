<?php

namespace App\Http\Controllers;

use App\Mail\NewOrderAdminMail;
use App\Mail\OrderCustomerConfirmationMail;
use App\Models\DeliveryPoint;
use App\Models\Order;
use App\Models\Product;
use App\Support\CheckoutDeliveryMethodService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CheckoutDeliveryMethodService $checkoutDeliveryMethodService,
    ) {
    }

    public function create(): View|RedirectResponse
    {
        $cart = $this->cart();

        if ($cart === []) {
            return redirect()->route('cart.index')->with('error', 'Grozs ir tukšs. Pirms noformēšanas pievienojiet preces.');
        }

        $products = $this->loadCartProducts($cart);
        $deliveryMethods = $this->checkoutDeliveryMethodService->availableForProducts($products);

        return view('shop.checkout', [
            'title' => 'Checkout | Top Care Group',
            'description' => 'Noformējiet Top Care Group pasūtījumu bez reģistrācijas.',
            'canonical' => '/checkout',
            'items' => array_values($cart),
            'cartTotal' => $this->cartTotal($cart),
            'cartCount' => $this->cartCount($cart),
            'deliveryMethods' => $deliveryMethods,
            'defaultDeliveryMethod' => $this->checkoutDeliveryMethodService->defaultKeyForProducts($products),
            'addressMethodKeys' => collect($deliveryMethods)
                ->filter(fn (array $method) => (bool) ($method['requires_address'] ?? false))
                ->keys()
                ->implode(' '),
            'deliveryPointMethodKeys' => collect($deliveryMethods)
                ->filter(fn (array $method) => (bool) ($method['requires_delivery_point'] ?? false))
                ->keys()
                ->implode(' '),
            'deliveryPrices' => collect($deliveryMethods)
                ->mapWithKeys(fn (array $method, string $key) => [$key => $method['price']])
                ->all(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $cart = $this->cart();

        if ($cart === []) {
            return redirect()->route('cart.index')->with('error', 'Grozs ir tukšs. Pirms noformēšanas pievienojiet preces.');
        }

        $products = $this->loadCartProducts($cart);
        $deliveryMethods = $this->checkoutDeliveryMethodService->availableForProducts($products);
        $allowedDeliveryMethods = array_keys($deliveryMethods);
        $selectedMethod = (string) $request->input('delivery_method', '');
        $requiresAddress = $this->checkoutDeliveryMethodService->requiresAddress($selectedMethod);
        $requiresDeliveryPoint = $this->checkoutDeliveryMethodService->requiresDeliveryPoint($selectedMethod);
        $deliveryPointProvider = $this->checkoutDeliveryMethodService->deliveryPointProvider($selectedMethod);

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email:rfc', 'max:255'],
            'delivery_method' => ['required', Rule::in($allowedDeliveryMethods)],
            'city' => [Rule::requiredIf($requiresAddress), 'nullable', 'string', 'max:255'],
            'street' => [Rule::requiredIf($requiresAddress), 'nullable', 'string', 'max:255'],
            'house' => [Rule::requiredIf($requiresAddress), 'nullable', 'string', 'max:50'],
            'apartment' => ['nullable', 'string', 'max:50'],
            'postal_code' => [Rule::requiredIf($requiresAddress), 'nullable', 'string', 'max:50'],
            'delivery_comment' => ['nullable', 'string', 'max:1000'],
            'comment' => ['nullable', 'string', 'max:2000'],
            'selected_delivery_point_id' => [Rule::requiredIf($requiresDeliveryPoint), 'nullable', 'integer'],
        ]);

        $selectedDeliveryPoint = null;

        if ($requiresDeliveryPoint) {
            $selectedDeliveryPoint = DeliveryPoint::query()
                ->whereKey($validated['selected_delivery_point_id'])
                ->where('provider', $deliveryPointProvider)
                ->where('is_active', true)
                ->first();

            if (! $selectedDeliveryPoint) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'selected_delivery_point_id' => 'Izvēlētais piegādes punkts nav derīgs.',
                    ]);
            }
        }

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

        $deliveryPrice = $this->checkoutDeliveryMethodService->priceForMethod($selectedMethod);
        $deliveryAddress = $this->composeDeliveryAddress($validated, $requiresAddress, $selectedDeliveryPoint);
        $orderComment = $this->composeOrderComment(
            $validated['comment'] ?? null,
            $validated['delivery_comment'] ?? null
        );

        $order = DB::transaction(function () use (
            $validated,
            $normalizedItems,
            $totalPrice,
            $deliveryAddress,
            $orderComment,
            $selectedMethod,
            $deliveryPointProvider,
            $selectedDeliveryPoint,
            $deliveryPrice
        ): Order {
            $order = Order::query()->create([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'],
                'delivery_address' => $deliveryAddress,
                'delivery_method' => $selectedMethod,
                'delivery_provider' => $deliveryPointProvider ?? ($selectedMethod === 'courier' ? 'courier' : ($selectedMethod === 'pickup' ? 'pickup' : null)),
                'delivery_point_id' => $selectedDeliveryPoint?->id,
                'delivery_point_name' => $selectedDeliveryPoint?->name,
                'delivery_point_address' => $selectedDeliveryPoint?->address,
                'delivery_city' => $selectedDeliveryPoint?->city,
                'delivery_postal_code' => $selectedDeliveryPoint?->postal_code,
                'delivery_price' => $deliveryPrice,
                'comment' => $orderComment,
                'total_price' => $totalPrice,
                'payment_method' => 'manual',
                'payment_status' => 'pending',
                'status' => 'new',
            ]);

            $order->orderItems()->createMany($normalizedItems);

            return $order;
        });

        $order->loadMissing('orderItems');
        $this->sendOrderEmails($order);

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

    public function deliveryPoints(Request $request): JsonResponse
    {
        $provider = trim((string) $request->query('provider', ''));
        $query = trim((string) $request->query('q', ''));

        if (! in_array($provider, ['omniva', 'dpd'], true)) {
            return response()->json(['points' => []]);
        }

        $points = DeliveryPoint::query()
            ->where('provider', $provider)
            ->where('is_active', true)
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($inner) use ($query) {
                    $inner->where('name', 'like', "%{$query}%")
                        ->orWhere('city', 'like', "%{$query}%")
                        ->orWhere('address', 'like', "%{$query}%");
                });
            })
            ->orderBy('city')
            ->orderBy('name')
            ->limit(20)
            ->get([
                'id',
                'provider',
                'name',
                'city',
                'address',
                'postal_code',
            ]);

        return response()->json([
            'points' => $points->map(fn (DeliveryPoint $point) => [
                'id' => $point->id,
                'provider' => $point->provider,
                'name' => $point->name,
                'city' => $point->city,
                'address' => $point->address,
                'postal_code' => $point->postal_code,
            ])->all(),
        ]);
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

    private function loadCartProducts(array $cart): Collection
    {
        $productIds = array_map(static fn (array $item) => (int) $item['product_id'], $cart);

        return Product::query()
            ->whereIn('id', $productIds)
            ->where('is_active', true)
            ->get()
            ->keyBy('id');
    }

    private function composeDeliveryAddress(array $validated, bool $requiresAddress, ?DeliveryPoint $deliveryPoint): string
    {
        if ($deliveryPoint) {
            return collect([
                $deliveryPoint->name,
                $deliveryPoint->address,
                $deliveryPoint->city,
                $deliveryPoint->postal_code,
            ])->filter(fn ($part) => filled($part))->implode(', ');
        }

        if (! $requiresAddress) {
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

    private function sendOrderEmails(Order $order): void
    {
        $adminEmail = (string) config('mail.admin.address', '');

        if ($adminEmail !== '') {
            try {
                Mail::to($adminEmail)->send(new NewOrderAdminMail($order));

                Log::info('Order admin email sent', [
                    'order_id' => $order->id,
                    'to' => $adminEmail,
                ]);
            } catch (\Throwable $e) {
                Log::error('Order admin email failed', [
                    'order_id' => $order->id,
                    'to' => $adminEmail,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        if ($order->customer_email !== '') {
            try {
                Mail::to($order->customer_email)->send(new OrderCustomerConfirmationMail($order));

                Log::info('Order customer email sent', [
                    'order_id' => $order->id,
                    'to' => $order->customer_email,
                ]);
            } catch (\Throwable $e) {
                Log::warning('Order customer email failed', [
                    'order_id' => $order->id,
                    'to' => $order->customer_email,
                    'message' => $e->getMessage(),
                ]);
            }
        }
    }
}
