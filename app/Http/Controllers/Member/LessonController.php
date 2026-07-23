<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\CourseEnrollment;
use App\Models\CourseLesson;
use App\Models\LessonDiscussion;
use App\Models\LessonNote;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    public function show(CourseLesson $lesson)
    {
        $lesson->load('module.course');

        abort_if(
            ! $lesson->is_published
            || ! $lesson->module
            || ! $lesson->module->course
            || ! $lesson->module->course->is_published,
            404
        );

        $course = $lesson->module->course;

        CourseEnrollment::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'course_id' => $course->id,
            ],
            [
                'enrolled_at' => now(),
            ]
        );

        $progress = LessonProgress::firstOrCreate([
            'user_id' => auth()->id(),
            'course_lesson_id' => $lesson->id,
        ]);

        $course->load([
            'modules.lessons' => fn ($query) => $query
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->orderBy('id'),
        ]);

        $allLessons = $course->modules
            ->flatMap->lessons
            ->values();

        $currentIndex = $allLessons->search(
            fn ($item) => $item->id === $lesson->id
        );

        $previousLesson = $currentIndex !== false
            && $currentIndex > 0
                ? $allLessons[$currentIndex - 1]
                : null;

        $nextLesson = $currentIndex !== false
            && $currentIndex < $allLessons->count() - 1
                ? $allLessons[$currentIndex + 1]
                : null;

        $completedLessonIds = LessonProgress::query()
            ->where('user_id', auth()->id())
            ->where('is_completed', true)
            ->pluck('course_lesson_id');

        $note = LessonNote::query()
            ->firstOrNew([
                'user_id' => auth()->id(),
                'course_lesson_id' => $lesson->id,
            ]);

        $discussions = LessonDiscussion::query()
            ->where('course_lesson_id', $lesson->id)
            ->whereNull('parent_id')
            ->with([
                'user',
                'replies.user',
            ])
            ->oldest('id')
            ->get();

        return view(
            'member.lessons.show',
            compact(
                'lesson',
                'course',
                'progress',
                'previousLesson',
                'nextLesson',
                'completedLessonIds',
                'note',
                'discussions'
            )
        );
    }

    public function video(CourseLesson $lesson)
    {
        $lesson->load('module.course');

        abort_if(
            ! $lesson->is_published
            || ! $lesson->module
            || ! $lesson->module->course
            || ! $lesson->module->course->is_published,
            404
        );

        abort_unless(
            $lesson->video_type === 'upload'
            && $lesson->video_path,
            404
        );

        abort_unless(
            Storage::disk('local')->exists($lesson->video_path),
            404
        );

        return response()->file(
            Storage::disk('local')->path($lesson->video_path),
            [
                'Content-Type' => Storage::disk('local')
                    ->mimeType($lesson->video_path) ?: 'video/mp4',

                'Accept-Ranges' => 'bytes',
                'Cache-Control' => 'private, no-store, max-age=0',
            ]
        );
    }

    public function attachment(CourseLesson $lesson)
    {
        $lesson->load('module.course');

        abort_if(
            ! $lesson->is_published
            || ! $lesson->module
            || ! $lesson->module->course
            || ! $lesson->module->course->is_published,
            404
        );

        abort_unless($lesson->attachment_path, 404);

        abort_unless(
            Storage::disk('local')->exists(
                $lesson->attachment_path
            ),
            404
        );

        return Storage::disk('local')->download(
            $lesson->attachment_path
        );
    }
}
