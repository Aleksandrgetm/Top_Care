<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeforeAfterItem extends Model
{
    protected $fillable = [
        'title',
        'description',
        'before_image',
        'after_image',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
