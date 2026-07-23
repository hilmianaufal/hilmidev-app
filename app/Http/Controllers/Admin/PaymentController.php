<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $orders = Order::query()
            ->with('user')
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
        abort_unless(
            $order->payment_status === 'review',
            422,
            'Pembayaran ini tidak sedang menunggu verifikasi.'
        );

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
        abort_unless(
            $order->payment_status === 'review',
            422,
            'Pembayaran ini tidak sedang menunggu verifikasi.'
        );

        $request->validate([
            'rejection_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $order->update([
            'payment_status' => 'failed',
            'status' => 'pending',
            'paid_at' => null,
        ]);

        return redirect()
            ->route('admin.payments.show', $order)
            ->with('success', 'Pembayaran berhasil ditolak.');
    }
}
