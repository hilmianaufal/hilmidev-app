<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentProofController extends Controller
{
    public function store(Request $request, Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);

        abort_unless(
            in_array($order->payment_status, ['unpaid', 'failed', 'review'], true),
            422,
            'Bukti pembayaran tidak dapat diubah pada status ini.'
        );

        $data = $request->validate([
            'payment_method' => ['required', 'string', 'max:100'],
            'payment_proof' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if (
            $order->payment_proof
            && Storage::disk('public')->exists($order->payment_proof)
        ) {
            Storage::disk('public')->delete($order->payment_proof);
        }

        $path = $request->file('payment_proof')
            ->store('payment-proofs', 'public');

        $order->update([
            'payment_method' => $data['payment_method'],
            'payment_proof' => $path,
            'payment_status' => 'review',
            'status' => 'processing',
        ]);

        return back()->with(
            'success',
            'Bukti pembayaran berhasil dikirim dan menunggu verifikasi admin.'
        );
    }
}
