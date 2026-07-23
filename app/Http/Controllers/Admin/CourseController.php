<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('q', ''));

        $courses = Course::query()
            ->withCount(['modules', 'lessons'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('technology', 'like', "%{$search}%");
                });
            })
            ->orderBy('sort_order')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.courses.index', compact('courses', 'search'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')
                ->store('courses/thumbnails', 'public');
        }

        Course::create($data);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Kelas coding berhasil dibuat.');
    }

    public function edit(Course $course)
    {
        $course->load('modules.lessons');

        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $data = $this->validatedData($request, $course);

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }

            $data['thumbnail'] = $request->file('thumbnail')
                ->store('courses/thumbnails', 'public');
        }

        $course->update($data);

        return back()->with('success', 'Informasi kelas berhasil diperbarui.');
    }

    public function destroy(Course $course)
    {
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        foreach ($course->lessons as $lesson) {
            if ($lesson->video_path) {
                Storage::disk('local')->delete($lesson->video_path);
            }

            if ($lesson->attachment_path) {
                Storage::disk('local')->delete($lesson->attachment_path);
            }
        }

        $course->delete();

        return back()->with('success', 'Kelas coding berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Course $course = null): array
    {
        $data = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('courses', 'title')->ignore($course?->id),
            ],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'level' => ['required', 'in:pemula,menengah,mahir'],
            'technology' => ['nullable', 'string', 'max:255'],
            'estimated_minutes' => ['nullable', 'integer', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'is_published' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = Str::slug($data['title'])
            . ($course ? '' : '-' . Str::lower(Str::random(4)));
        $data['estimated_minutes'] = $data['estimated_minutes'] ?? 0;
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_published'] = $request->boolean('is_published');
        $data['is_featured'] = $request->boolean('is_featured');

        return $data;
    }
}
