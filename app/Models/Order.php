<?php

namespace App\Models;

use App\Models\Concerns\FormatsOrderNumber;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use FormatsOrderNumber;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'customer_email',
        'delivery_address',
        'delivery_method',
        'delivery_provider',
        'delivery_point_id',
        'delivery_point_name',
        'delivery_point_address',
        'delivery_city',
        'delivery_postal_code',
        'delivery_price',
        'comment',
        'total_price',
        'payment_method',
        'payment_status',
        'status',
    ];

    protected $appends = [
        'display_order_number',
    ];

    protected static function booted(): void
    {
        static::created(function (Order $order): void {
            if ($order->order_number) {
                return;
            }

            $order->forceFill([
                'order_number' => $order->formatOrderNumber(),
            ])->saveQuietly();
        });
    }

    protected function casts(): array
    {
        return [
            'total_price' => 'decimal:2',
            'delivery_price' => 'decimal:2',
        ];
    }

    public function deliveryPoint(): BelongsTo
    {
        return $this->belongsTo(DeliveryPoint::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
