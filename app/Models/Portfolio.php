<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category',
        'client_name',
        'project_url',
        'thumbnail',
        'gallery',
        'short_description',
        'description',
        'tech_stack',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'gallery' => 'array',
        'tech_stack' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];
}