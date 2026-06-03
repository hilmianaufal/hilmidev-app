<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'features' => ['nullable', 'string'],
            'technology' => ['nullable', 'string', 'max:255'],
            'demo_url' => ['nullable', 'url'],
            'video_url' => ['nullable', 'url'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'file_path' => ['nullable', 'file', 'mimes:zip,rar,7z', 'max:51200'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);

        $data['features'] = $request->features
            ? array_values(array_filter(array_map('trim', explode("\n", $request->features))))
            : null;

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')
                ->store('products/thumbnails', 'public');
        }

        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')
                ->store('products/files', 'local');
        }

        Product::create($data);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        return redirect()->route('products.show', $product);
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'features' => ['nullable', 'string'],
            'technology' => ['nullable', 'string', 'max:255'],
            'demo_url' => ['nullable', 'url'],
            'video_url' => ['nullable', 'url'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'file_path' => ['nullable', 'file', 'mimes:zip,rar,7z', 'max:51200'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->name !== $product->name) {
            $data['slug'] = Str::slug($request->name) . '-' . Str::random(5);
        }

        $data['features'] = $request->features
            ? array_values(array_filter(array_map('trim', explode("\n", $request->features))))
            : null;

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }

            $data['thumbnail'] = $request->file('thumbnail')
                ->store('products/thumbnails', 'public');
        }

        if ($request->hasFile('file_path')) {
            if ($product->file_path) {
                Storage::disk('local')->delete($product->file_path);
            }

            $data['file_path'] = $request->file('file_path')
                ->store('products/files', 'local');
        }

        $product->update($data);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        if ($product->file_path) {
            Storage::disk('local')->delete($product->file_path);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}