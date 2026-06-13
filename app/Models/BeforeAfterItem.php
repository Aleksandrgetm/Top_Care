<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeforeAfterItem extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'before_image',
        'after_image',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
