<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\CourseLesson;
use App\Models\LessonNote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LessonNoteController extends Controller
{
    public function store(
        Request $request,
        CourseLesson $lesson
    ): RedirectResponse {
        $this->ensureLessonAvailable($lesson);

        $data = $request->validate([
            'content' => [
                'required',
                'string',
                'max:10000',
            ],
        ]);

        LessonNote::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'course_lesson_id' => $lesson->id,
            ],
            [
                'content' => $data['content'],
            ]
        );

        return back()->with(
            'success',
            'Catatan pribadi berhasil disimpan.'
        );
    }

    public function destroy(
        Request $request,
        CourseLesson $lesson
    ): RedirectResponse {
        $this->ensureLessonAvailable($lesson);

        LessonNote::query()
            ->where('user_id', $request->user()->id)
            ->where('course_lesson_id', $lesson->id)
            ->delete();

        return back()->with(
            'success',
            'Catatan pribadi berhasil dihapus.'
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
