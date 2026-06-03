<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.product'])
            ->latest()
            ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,processing,completed,cancelled'],
            'payment_status' => ['required', 'in:unpaid,review,paid,failed,refunded'],
        ]);

        $order->update([
                'status' => $data['status'],
                'payment_status' => $data['payment_status'],
                'paid_at' => $data['payment_status'] === 'paid' ? now() : $order->paid_at,
            ]);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Status order berhasil diperbarui.');
    }
}