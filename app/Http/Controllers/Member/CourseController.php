<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\LessonProgress;

class CourseController extends Controller
{
    public function show(Course $course)
    {
        abort_if(! $course->is_published, 404);

        CourseEnrollment::firstOrCreate(
            ['user_id' => auth()->id(), 'course_id' => $course->id],
            ['enrolled_at' => now()]
        );

        $course->load([
            'modules.lessons' => fn ($query) => $query
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->orderBy('id'),
        ]);

        $completedLessonIds = LessonProgress::query()
            ->where('user_id', auth()->id())
            ->where('is_completed', true)
            ->pluck('course_lesson_id');

        $allLessons = $course->modules->flatMap->lessons;
        $totalLessons = $allLessons->count();
        $completedLessons = $allLessons->whereIn('id', $completedLessonIds)->count();
        $percentage = $totalLessons > 0
            ? (int) round(($completedLessons / $totalLessons) * 100)
            : 0;

        return view('member.courses.show', compact(
            'course',
            'completedLessonIds',
            'totalLessons',
            'completedLessons',
            'percentage'
        ));
    }
}
