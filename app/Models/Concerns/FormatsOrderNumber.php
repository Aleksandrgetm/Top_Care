<?php

namespace App\Models\Concerns;

trait FormatsOrderNumber
{
    public function formatOrderNumber(?int $id = null): ?string
    {
        $orderId = $id ?? $this->id;

        if (! $orderId) {
            return null;
        }

        return 'TCG-' . (1000 + $orderId);
    }

    public function getDisplayOrderNumberAttribute(): string
    {
        return $this->order_number ?: '#' . $this->id;
    }
}
