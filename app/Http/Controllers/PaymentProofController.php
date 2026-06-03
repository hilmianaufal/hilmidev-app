<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PaymentProofController extends Controller
{
    public function store(Request $request, Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'payment_method' => ['required', 'string', 'max:100'],
            'payment_proof' => ['required', 'image', 'max:2048'],
        ]);

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $order->update([
            'payment_method' => $data['payment_method'],
            'payment_proof' => $path,
            'payment_status' => 'review',
            'status' => 'processing',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil dikirim. Admin akan memverifikasi pembayaran.');
    }
}