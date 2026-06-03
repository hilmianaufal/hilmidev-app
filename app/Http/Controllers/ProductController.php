<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->get();

        $products = Product::with('category')
            ->where('is_active', true)
            ->when(request('category'), function ($query) {
                $query->whereHas('category', function ($categoryQuery) {
                    $categoryQuery->where('slug', request('category'));
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('products.index', compact('categories', 'products'));
    }

    public function show(Product $product)
    {
        abort_if(! $product->is_active, 404);

        $product->load('category');

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}