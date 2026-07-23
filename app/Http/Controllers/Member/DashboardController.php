<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\LessonProgress;
use App\Models\Membership;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $membership = Membership::query()
            ->with('plan')
            ->where('user_id', $userId)
            ->active()
            ->latest('id')
            ->firstOrFail();

        $courses = Course::query()
            ->published()
            ->withCount([
                'lessons as lessons_count' => fn ($query) => $query->where('is_published', true),
            ])
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->get();

        $completedLessonIds = LessonProgress::query()
            ->where('user_id', $userId)
            ->where('is_completed', true)
            ->pluck('course_lesson_id');

        $courseCards = $courses->map(function (Course $course) use ($completedLessonIds) {
            $lessonIds = $course->lessons()
                ->where('course_lessons.is_published', true)
                ->pluck('course_lessons.id');

            $completed = $lessonIds->intersect($completedLessonIds)->count();
            $total = $lessonIds->count();

            return [
                'course' => $course,
                'completed' => $completed,
                'total' => $total,
                'percentage' => $total > 0 ? (int) round(($completed / $total) * 100) : 0,
            ];
        });

        $lastProgress = LessonProgress::query()
            ->with('lesson.module.course')
            ->where('user_id', $userId)
            ->latest('updated_at')
            ->first();

        return view('member.dashboard', [
            'membership' => $membership,
            'courseCards' => $courseCards,
            'totalCourses' => $courses->count(),
            'completedLessons' => $completedLessonIds->count(),
            'enrolledCourses' => CourseEnrollment::where('user_id', $userId)->count(),
            'lastProgress' => $lastProgress,
        ]);
    }
}
