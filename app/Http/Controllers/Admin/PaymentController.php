<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')
            ->whereNotNull('payment_proof')
            ->latest()
            ->paginate(10);

        return view('admin.payments.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);

        return view('admin.payments.show', compact('order'));
    }

    public function approve(Order $order)
    {
        $order->update([
            'payment_status' => 'paid',
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        return redirect()
            ->route('admin.payments.show', $order)
            ->with('success', 'Pembayaran berhasil disetujui.');
    }

    public function reject(Request $request, Order $order)
    {
        $request->validate([
            'rejection_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $order->update([
            'payment_status' => 'rejected',
            'status' => 'pending',
        ]);

        return redirect()
            ->route('admin.payments.show', $order)
            ->with('success', 'Pembayaran berhasil ditolak.');
    }
}