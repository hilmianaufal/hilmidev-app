<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProjectRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->latest()
            ->limit(5)
            ->get();

        $projectRequests = ProjectRequest::with('service')
            ->where('user_id', auth()->id())
            ->latest()
            ->limit(5)
            ->get();

        return view('client.dashboard', [
            'totalOrders' => Order::where('user_id', auth()->id())->count(),
            'paidOrders' => Order::where('user_id', auth()->id())
                ->where('payment_status', 'paid')
                ->count(),
            'totalInvoices' => Order::where('user_id', auth()->id())->count(),
            'activeProjects' => ProjectRequest::where('user_id', auth()->id())
                ->whereIn('status', ['pending', 'review', 'quotation', 'development'])
                ->count(),
            'orders' => $orders,
            'projectRequests' => $projectRequests,
        ]);
    }
}