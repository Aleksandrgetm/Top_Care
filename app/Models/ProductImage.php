<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'image_path',
        'thumbnail_path',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (ProductImage $productImage): void {
            if ($productImage->image_path && str_starts_with($productImage->image_path, 'products/')) {
                Storage::disk('public')->delete($productImage->image_path);
            }

            if ($productImage->thumbnail_path && str_starts_with($productImage->thumbnail_path, 'products/')) {
                Storage::disk('public')->delete($productImage->thumbnail_path);
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function imageUrl(): ?string
    {
        return $this->image_path ? '/storage/'.ltrim($this->image_path, '/') : null;
    }

    public function thumbnailUrl(): ?string
    {
        if ($this->thumbnail_path) {
            return '/storage/'.ltrim($this->thumbnail_path, '/');
        }

        return $this->imageUrl();
    }
}
