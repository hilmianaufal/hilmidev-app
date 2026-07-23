<?php

namespace App\Http\Controllers;

use App\Models\CourseLesson;
use Illuminate\Support\Facades\Storage;

class CoursePreviewController extends Controller
{
    public function show(CourseLesson $lesson)
    {
        $lesson->load('module.course');

        abort_if(
            ! $lesson->is_preview
            || ! $lesson->is_published
            || ! $lesson->module
            || ! $lesson->module->course
            || ! $lesson->module->course->is_published,
            404
        );

        $course = $lesson->module->course;

        return view('courses.preview', compact('lesson', 'course'));
    }

    public function video(CourseLesson $lesson)
    {
        $lesson->load('module.course');

        abort_if(
            ! $lesson->is_preview
            || ! $lesson->is_published
            || ! $lesson->module
            || ! $lesson->module->course
            || ! $lesson->module->course->is_published,
            404
        );

        abort_unless(
            $lesson->video_type === 'upload'
            && $lesson->video_path
            && Storage::disk('local')->exists($lesson->video_path),
            404
        );

        return response()->file(
            Storage::disk('local')->path($lesson->video_path),
            [
                'Content-Type' => Storage::disk('local')->mimeType($lesson->video_path) ?: 'video/mp4',
                'Cache-Control' => 'private, no-store, max-age=0',
            ]
        );
    }
}
