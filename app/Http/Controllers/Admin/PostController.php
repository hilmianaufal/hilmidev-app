<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('author')
            ->latest()
            ->paginate(10);

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $data['user_id'] = auth()->id();
        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(5);
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published'] ? now() : null;

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')
                ->store('posts/thumbnails', 'public');
        }

        Post::create($data);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Artikel blog berhasil ditambahkan.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        if ($request->title !== $post->title) {
            $data['slug'] = Str::slug($request->title) . '-' . Str::random(5);
        }

        $data['is_published'] = $request->boolean('is_published');

        if ($data['is_published'] && ! $post->published_at) {
            $data['published_at'] = now();
        }

        if (! $data['is_published']) {
            $data['published_at'] = null;
        }

        if ($request->hasFile('thumbnail')) {
            if ($post->thumbnail) {
                Storage::disk('public')->delete($post->thumbnail);
            }

            $data['thumbnail'] = $request->file('thumbnail')
                ->store('posts/thumbnails', 'public');
        }

        $post->update($data);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Artikel blog berhasil diperbarui.');
    }

    public function destroy(Post $post)
    {
        if ($post->thumbnail) {
            Storage::disk('public')->delete($post->thumbnail);
        }

        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Artikel blog berhasil dihapus.');
    }
}