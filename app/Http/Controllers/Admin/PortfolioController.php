<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::latest()->paginate(10);

        return view('admin.portfolios.index', compact('portfolios'));
    }

    public function create()
    {
        return view('admin.portfolios.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'client_name' => ['nullable', 'string', 'max:255'],
            'project_url' => ['nullable', 'url'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'tech_stack' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'gallery.*' => ['nullable', 'image', 'max:2048'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(5);

        $data['tech_stack'] = $request->tech_stack
            ? array_values(array_filter(array_map('trim', explode("\n", $request->tech_stack))))
            : null;

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')
                ->store('portfolios/thumbnails', 'public');
        }

        if ($request->hasFile('gallery')) {
            $gallery = [];

            foreach ($request->file('gallery') as $image) {
                $gallery[] = $image->store('portfolios/gallery', 'public');
            }

            $data['gallery'] = $gallery;
        }

        Portfolio::create($data);

        return redirect()
            ->route('admin.portfolios.index')
            ->with('success', 'Portfolio berhasil ditambahkan.');
    }

    public function edit(Portfolio $portfolio)
    {
        return view('admin.portfolios.edit', compact('portfolio'));
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'client_name' => ['nullable', 'string', 'max:255'],
            'project_url' => ['nullable', 'url'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'tech_stack' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'gallery.*' => ['nullable', 'image', 'max:2048'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->title !== $portfolio->title) {
            $data['slug'] = Str::slug($request->title) . '-' . Str::random(5);
        }

        $data['tech_stack'] = $request->tech_stack
            ? array_values(array_filter(array_map('trim', explode("\n", $request->tech_stack))))
            : null;

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('thumbnail')) {
            if ($portfolio->thumbnail) {
                Storage::disk('public')->delete($portfolio->thumbnail);
            }

            $data['thumbnail'] = $request->file('thumbnail')
                ->store('portfolios/thumbnails', 'public');
        }

        if ($request->hasFile('gallery')) {
            if ($portfolio->gallery) {
                foreach ($portfolio->gallery as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            $gallery = [];

            foreach ($request->file('gallery') as $image) {
                $gallery[] = $image->store('portfolios/gallery', 'public');
            }

            $data['gallery'] = $gallery;
        }

        $portfolio->update($data);

        return redirect()
            ->route('admin.portfolios.index')
            ->with('success', 'Portfolio berhasil diperbarui.');
    }

    public function destroy(Portfolio $portfolio)
    {
        if ($portfolio->thumbnail) {
            Storage::disk('public')->delete($portfolio->thumbnail);
        }

        if ($portfolio->gallery) {
            foreach ($portfolio->gallery as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $portfolio->delete();

        return redirect()
            ->route('admin.portfolios.index')
            ->with('success', 'Portfolio berhasil dihapus.');
    }
}