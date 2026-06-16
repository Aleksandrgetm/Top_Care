<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryPoint extends Model
{
    protected $fillable = [
        'provider',
        'external_id',
        'name',
        'country',
        'city',
        'address',
        'postal_code',
        'latitude',
        'longitude',
        'raw_data',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'raw_data' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
