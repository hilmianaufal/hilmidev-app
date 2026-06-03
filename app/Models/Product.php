<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'thumbnail',
        'screenshots',
        'short_description',
        'description',
        'features',
        'technology',
        'demo_url',
        'video_url',
        'file_path',
        'price',
        'discount_price',
        'stock',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'screenshots' => 'array',
        'features' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getFinalPriceAttribute()
    {
        return $this->discount_price ?? $this->price;
    }


    public function orderItems()
    {
        return $this->hasMany(\App\Models\OrderItem::class);
    }
}