<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock_quantity',
        'is_active',
        'supports_courier',
        'supports_dpd',
        'supports_omniva',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'is_active' => 'boolean',
            'supports_courier' => 'boolean',
            'supports_dpd' => 'boolean',
            'supports_omniva' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (Product $product): void {
            $product->productImages()->get()->each->delete();
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->ofMany([
            'sort_order' => 'min',
            'id' => 'min',
        ]);
    }
}
