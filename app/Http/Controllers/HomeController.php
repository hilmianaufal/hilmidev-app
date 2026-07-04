<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use App\Models\Testimonial;

class HomeController extends Controller
{
 public function index()
{
        $featuredProducts = Product::with('category')
            ->where('is_active', true)
            ->latest()
            ->limit(12)
            ->get();

        $categories = Category::where('is_active', true)->get();

        $featuredServices = Service::where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->limit(6)
            ->get();

        $testimonials = Testimonial::where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->get();

        return view('home', compact(
            'featuredProducts',
            'categories',
            'featuredServices',
            'testimonials'
        ));
    }
}
