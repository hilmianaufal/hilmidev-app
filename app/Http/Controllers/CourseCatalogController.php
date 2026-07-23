<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Membership;
use Illuminate\Http\Request;

class CourseCatalogController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('q', ''));
        $level = trim((string) $request->get('level', ''));

        $courses = Course::query()
            ->published()
            ->withCount([
                'modules',
                'lessons as lessons_count' => fn ($query) => $query->where('is_published', true),
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('subtitle', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('technology', 'like', "%{$search}%");
                });
            })
            ->when($level !== '', fn ($query) => $query->where('level', $level))
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $hasActiveMembership = auth()->check()
            && Membership::query()
                ->where('user_id', auth()->id())
                ->active()
                ->exists();

        return view('courses.index', compact(
            'courses',
            'search',
            'level',
            'hasActiveMembership'
        ));
    }

    public function show(Course $course)
    {
        abort_if(! $course->is_published, 404);

        $course->load([
            'modules.lessons' => fn ($query) => $query
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->orderBy('id'),
        ]);

        $hasActiveMembership = auth()->check()
            && Membership::query()
                ->where('user_id', auth()->id())
                ->active()
                ->exists();

        return view('courses.show', compact('course', 'hasActiveMembership'));
    }
}
