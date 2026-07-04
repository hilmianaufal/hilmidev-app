<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class ClientController extends Controller
{
    public function index()
    {
        $clients = User::withCount(['orders', 'projectRequests'])
            ->where('role', '!=', 'admin')
            ->latest()
            ->paginate(15);

        return view('admin.clients.index', compact('clients'));
    }


    public function show(User $user)
    {
        abort_if($user->role === 'admin', 404);

        $user->load([
            'orders.items.product',
            'projectRequests.service',
        ]);

        $totalSpent = $user->orders()
            ->where('payment_status', 'paid')
            ->sum('total_price');

        return view('admin.clients.show', compact('user', 'totalSpent'));
    }
}