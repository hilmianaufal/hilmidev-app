<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProjectRequest;
use App\Models\Service;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalProducts' => Product::count(),
            'activeProducts' => Product::where('is_active', true)->count(),
            'totalCategories' => Category::count(),
            'totalClients' => User::where('role', 'client')->count(),

            'totalServices' => Service::count(),
            'totalProjectRequests' => ProjectRequest::count(),
            'pendingProjectRequests' => ProjectRequest::where('status', 'pending')->count(),
            'developmentProjectRequests' => ProjectRequest::where('status', 'development')->count(),

            'totalOrders' => Order::count(),
            'paidOrders' => Order::where('payment_status', 'paid')->count(),
            'pendingOrders' => Order::where('payment_status', 'unpaid')->count(),
            'totalRevenue' => Order::where('payment_status', 'paid')->sum('total_price'),

            'latestProducts' => Product::with('category')->latest()->limit(5)->get(),
            'latestOrders' => Order::with('user')->latest()->limit(5)->get(),
            'latestProjectRequests' => ProjectRequest::with(['user', 'service'])->latest()->limit(5)->get(),
        ]);
    }
}