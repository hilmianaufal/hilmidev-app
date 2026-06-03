<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::latest()->paginate(10);

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'features' => ['nullable', 'string'],
            'starting_price' => ['required', 'numeric', 'min:0'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(5);

        $data['features'] = $request->features
            ? array_values(array_filter(array_map('trim', explode("\n", $request->features))))
            : null;

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('services', 'public');
        }

        Service::create($data);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Jasa berhasil ditambahkan.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'features' => ['nullable', 'string'],
            'starting_price' => ['required', 'numeric', 'min:0'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->title !== $service->title) {
            $data['slug'] = Str::slug($request->title) . '-' . Str::random(5);
        }

        $data['features'] = $request->features
            ? array_values(array_filter(array_map('trim', explode("\n", $request->features))))
            : null;

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('thumbnail')) {
            if ($service->thumbnail) {
                Storage::disk('public')->delete($service->thumbnail);
            }

            $data['thumbnail'] = $request->file('thumbnail')->store('services', 'public');
        }

        $service->update($data);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Jasa berhasil diperbarui.');
    }

    public function destroy(Service $service)
    {
        if ($service->thumbnail) {
            Storage::disk('public')->delete($service->thumbnail);
        }

        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Jasa berhasil dihapus.');
    }
}