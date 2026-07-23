<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('q', ''));
        $category = trim((string) $request->get('category', ''));
        $technology = trim((string) $request->get('technology', ''));
        $sort = trim((string) $request->get('sort', 'latest'));

        $productsQuery = Product::query()
            ->with('category')
            ->where('is_active', true);

        /*
        |--------------------------------------------------------------------------
        | Pencarian
        |--------------------------------------------------------------------------
        */
        if ($search !== '') {
            $productsQuery->where(function (Builder $query) use ($search) {
                $query
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('short_description', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('technology', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function (Builder $categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter kategori
        |--------------------------------------------------------------------------
        */
        if ($category !== '') {
            $productsQuery->whereHas('category', function (Builder $query) use ($category) {
                $query->where('slug', $category);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter teknologi
        |--------------------------------------------------------------------------
        */
        if ($technology !== '') {
            $productsQuery->where(
                'technology',
                'like',
                '%' . $technology . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pengurutan
        |--------------------------------------------------------------------------
        */
        match ($sort) {
            'featured' => $productsQuery
                ->orderByDesc('is_featured')
                ->latest(),

            'price_low' => $productsQuery
                ->orderByRaw('COALESCE(discount_price, price) ASC'),

            'price_high' => $productsQuery
                ->orderByRaw('COALESCE(discount_price, price) DESC'),

            'name_asc' => $productsQuery->orderBy('name'),

            'name_desc' => $productsQuery->orderByDesc('name'),

            'oldest' => $productsQuery->oldest(),

            default => $productsQuery->latest(),
        };

        $products = $productsQuery
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()
            ->where('is_active', true)
            ->withCount([
                'products' => function (Builder $query) {
                    $query->where('is_active', true);
                },
            ])
            ->orderBy('name')
            ->get();

        $technologies = Product::query()
            ->where('is_active', true)
            ->whereNotNull('technology')
            ->where('technology', '!=', '')
            ->pluck('technology')
            ->flatMap(function ($item) {
                return preg_split('/[,|\/]+/', $item);
            })
            ->map(function ($item) {
                return trim($item);
            })
            ->filter()
            ->unique(function ($item) {
                return strtolower($item);
            })
            ->sort()
            ->values();

        return view('products.index', compact(
            'products',
            'categories',
            'technologies',
            'search',
            'category',
            'technology',
            'sort'
        ));
    }

    public function show(Product $product)
    {
        abort_if(! $product->is_active, 404);

        $product->load('category');

        $relatedProducts = Product::query()
            ->with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->latest()
            ->limit(4)
            ->get();

        return view('products.show', compact(
            'product',
            'relatedProducts'
        ));
    }
}
