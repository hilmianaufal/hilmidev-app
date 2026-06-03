<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('category')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->limit(6)
            ->get();

        $featuredServices = Service::where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->limit(6)
            ->get();

        return view('home', compact('featuredProducts', 'featuredServices'));
    }
}