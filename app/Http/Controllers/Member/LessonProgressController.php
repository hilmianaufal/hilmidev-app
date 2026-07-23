<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\CourseEnrollment;
use App\Models\CourseLesson;
use App\Models\LessonProgress;
use Illuminate\Http\Request;

class LessonProgressController extends Controller
{
    public function store(Request $request, CourseLesson $lesson)
    {
        $lesson->load('module.course');

        abort_if(! $lesson->is_published || ! $lesson->module->course->is_published, 404);

        $data = $request->validate([
            'watched_seconds' => ['nullable', 'integer', 'min:0'],
            'is_completed' => ['nullable', 'boolean'],
        ]);

        $progress = LessonProgress::firstOrNew([
            'user_id' => auth()->id(),
            'course_lesson_id' => $lesson->id,
        ]);

        $progress->watched_seconds = max(
            $progress->watched_seconds ?? 0,
            (int) ($data['watched_seconds'] ?? 0)
        );

        if ($request->boolean('is_completed')) {
            $progress->is_completed = true;
            $progress->completed_at = $progress->completed_at ?: now();
        }

        $progress->save();

        $course = $lesson->module->course;
        $totalLessons = $course->lessons()
            ->where('course_lessons.is_published', true)
            ->count();

        $completedLessons = LessonProgress::query()
            ->where('user_id', auth()->id())
            ->where('is_completed', true)
            ->whereHas('lesson.module', fn ($query) => $query->where('course_id', $course->id))
            ->count();

        if ($totalLessons > 0 && $completedLessons >= $totalLessons) {
            CourseEnrollment::query()
                ->where('user_id', auth()->id())
                ->where('course_id', $course->id)
                ->update(['completed_at' => now()]);
        }

        return back()->with('success', 'Progress pembelajaran berhasil disimpan.');
    }
}
