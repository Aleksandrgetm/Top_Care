<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Collection;

class CheckoutDeliveryMethodService
{
    public function definitions(): array
    {
        return [
            'courier' => [
                'label' => 'Kurjers',
                'description' => 'Piegāde uz norādīto adresi',
                'price_label' => 'Tiks precizēta',
                'icon' => 'courier',
                'requires_address' => true,
                'product_flag' => 'supports_courier',
            ],
            'dpd' => [
                'label' => 'DPD',
                'description' => 'Piegāde ar DPD kurjeru vai tīklu',
                'price_label' => 'Tiks precizēta',
                'icon' => 'dpd',
                'requires_address' => true,
                'product_flag' => 'supports_dpd',
            ],
            'omniva' => [
                'label' => 'Omniva',
                'description' => 'Piegāde ar Omniva tīklu',
                'price_label' => 'Tiks precizēta',
                'icon' => 'omniva',
                'requires_address' => true,
                'product_flag' => 'supports_omniva',
            ],
            'pickup' => [
                'label' => 'Saņemšana uz vietas',
                'description' => 'Saņemšana pēc vienošanās',
                'price_label' => 'Bezmaksas',
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
}
