<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Collection;

class CheckoutDeliveryMethodService
{
    public function definitions(): array
    {
        $prices = config('delivery.prices', []);

        return [
            'courier' => [
                'label' => 'Kurjers',
                'description' => 'Piegāde uz norādīto adresi',
                'price' => $prices['courier'] ?? null,
                'price_label' => $this->formatPriceLabel($prices['courier'] ?? null, 'Tiks precizēta'),
                'icon' => 'courier',
                'requires_address' => true,
                'product_flag' => 'supports_courier',
            ],
            'dpd' => [
                'label' => 'DPD',
                'description' => 'Saņemšana DPD Pickup punktā',
                'price' => $prices['dpd'] ?? null,
                'price_label' => $this->formatPriceLabel($prices['dpd'] ?? null, 'Tiks precizēta'),
                'icon' => 'dpd',
                'requires_delivery_point' => true,
                'delivery_point_provider' => 'dpd',
                'product_flag' => 'supports_dpd',
            ],
            'omniva' => [
                'label' => 'Omniva',
                'description' => 'Saņemšana Omniva pakomātā',
                'price' => $prices['omniva'] ?? null,
                'price_label' => $this->formatPriceLabel($prices['omniva'] ?? null, 'Tiks precizēta'),
                'icon' => 'omniva',
                'requires_delivery_point' => true,
                'delivery_point_provider' => 'omniva',
                'product_flag' => 'supports_omniva',
            ],
            'pickup' => [
                'label' => 'Saņemšana uz vietas',
                'description' => 'Saņemšana pēc vienošanās',
                'price' => $prices['pickup'] ?? 0.0,
                'price_label' => $this->formatPriceLabel($prices['pickup'] ?? 0.0, 'Bezmaksas'),
                'icon' => 'pickup',
                'requires_address' => false,
                'always_available' => true,
            ],
        ];
    }

    public function availableForProducts(Collection $products): array
    {
        $definitions = $this->definitions();
        $available = [];

        foreach ($definitions as $key => $definition) {
            if (($definition['always_available'] ?? false) === true) {
                $available[$key] = $definition;
                continue;
            }

            $flag = $definition['product_flag'] ?? null;

            if (! $flag) {
                continue;
            }

            $isSupportedByAllProducts = $products->isNotEmpty()
                && $products->every(fn (Product $product) => (bool) $product->{$flag});

            if ($isSupportedByAllProducts) {
                $available[$key] = $definition;
            }
        }

        return $available;
    }

    public function allowedKeysForProducts(Collection $products): array
    {
        return array_keys($this->availableForProducts($products));
    }

    public function defaultKeyForProducts(Collection $products): string
    {
        $keys = $this->allowedKeysForProducts($products);

        return $keys[0] ?? 'pickup';
    }

    public function requiresAddress(string $method): bool
    {
        return (bool) ($this->definitions()[$method]['requires_address'] ?? false);
    }

    public function requiresDeliveryPoint(string $method): bool
    {
        return (bool) ($this->definitions()[$method]['requires_delivery_point'] ?? false);
    }

    public function deliveryPointProvider(string $method): ?string
    {
        return $this->definitions()[$method]['delivery_point_provider'] ?? null;
    }

    public function priceForMethod(string $method): ?float
    {
        $price = $this->definitions()[$method]['price'] ?? null;

        return $price === null ? null : (float) $price;
    }

    private function formatPriceLabel(mixed $price, string $fallback): string
    {
        if ($price === null) {
            return $fallback;
        }

        if ((float) $price === 0.0) {
            return 'Bezmaksas';
        }

        return '€' . number_format((float) $price, 2, '.', ' ');
    }
}
