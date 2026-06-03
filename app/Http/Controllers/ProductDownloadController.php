<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductDownloadController extends Controller
{
    public function download(Order $order, OrderItem $item)
    {
        abort_if($order->user_id !== auth()->id(), 403);
        abort_if($order->payment_status !== 'paid', 403);
        abort_if($item->order_id !== $order->id, 403);

        $product = $item->product;

        abort_if(! $product, 404);
        abort_if(! $product->file_path, 404);
        abort_if(! Storage::disk('local')->exists($product->file_path), 404);

        return Storage::disk('local')->download(
            $product->file_path,
            Str::slug($product->name) . '.zip'
        );
    }
}