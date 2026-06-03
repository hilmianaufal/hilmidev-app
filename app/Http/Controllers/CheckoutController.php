<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function store(Product $product)
    {
        abort_if(! $product->is_active, 404);

        $order = Order::create([
            'invoice_number' => 'INV-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(5)),
            'user_id' => auth()->id(),
            'total_price' => $product->final_price,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => $product->final_price,
        ]);

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Invoice berhasil dibuat.');
    }
}