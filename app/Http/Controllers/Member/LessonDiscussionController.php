<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\CourseLesson;
use App\Models\LessonDiscussion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LessonDiscussionController extends Controller
{
    public function store(
        Request $request,
        CourseLesson $lesson
    ): RedirectResponse {
        $this->ensureLessonAvailable($lesson);

        $data = $request->validate([
            'message' => [
                'required',
                'string',
                'min:3',
                'max:5000',
            ],
        ]);

        LessonDiscussion::create([
            'user_id' => $request->user()->id,
            'course_lesson_id' => $lesson->id,
            'message' => $data['message'],
        ]);

        return back()->with(
            'success',
            'Pertanyaan berhasil dikirim ke forum diskusi.'
        );
    }

    public function destroy(
        Request $request,
        LessonDiscussion $discussion
    ): RedirectResponse {
        abort_unless(
            $discussion->user_id === $request->user()->id,
            403
        );

        abort_unless(
            $discussion->parent_id === null,
            403
        );

        $discussion->delete();

        return back()->with(
            'success',
            'Diskusi berhasil dihapus.'
        );
    }

    private function ensureLessonAvailable(
        CourseLesson $lesson
    ): void {
        $lesson->loadMissing('module.course');

        abort_if(
            ! $lesson->is_published
            || ! $lesson->module
            || ! $lesson->module->course
            || ! $lesson->module->course->is_published,
            404
        );
    }
}
