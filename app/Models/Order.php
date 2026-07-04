<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'invoice_number',
        'user_id',
        'total_price',
        'status',
        'payment_status',
        'payment_method',
        'payment_proof',
        'paid_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

        protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
        ];
}
}