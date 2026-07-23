<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LessonDiscussion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LessonDiscussionController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->get('q', ''));
        $status = (string) $request->get('status', '');

        $discussions = LessonDiscussion::query()
            ->whereNull('parent_id')
            ->with([
                'user',
                'lesson.module.course',
                'replies.user',
            ])
            ->withCount('replies')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder
                        ->where('message', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('lesson', function ($lessonQuery) use ($search) {
                            $lessonQuery->where(
                                'title',
                                'like',
                                "%{$search}%"
                            );
                        })
                        ->orWhereHas(
                            'lesson.module.course',
                            function ($courseQuery) use ($search) {
                                $courseQuery->where(
                                    'title',
                                    'like',
                                    "%{$search}%"
                                );
                            }
                        );
                });
            })
            ->when(
                $status === 'answered',
                fn ($query) => $query->where('is_answered', true)
            )
            ->when(
                $status === 'unanswered',
                fn ($query) => $query->where('is_answered', false)
            )
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.lesson-discussions.index',
            compact('discussions', 'search', 'status')
        );
    }

    public function show(
        LessonDiscussion $discussion
    ): View {
        abort_if($discussion->parent_id !== null, 404);

        $discussion->load([
            'user',
            'lesson.module.course',
            'replies.user',
        ]);

        return view(
            'admin.lesson-discussions.show',
            compact('discussion')
        );
    }

    public function reply(
        Request $request,
        LessonDiscussion $discussion
    ): RedirectResponse {
        abort_if($discussion->parent_id !== null, 404);

        $data = $request->validate([
            'message' => [
                'required',
                'string',
                'min:3',
                'max:5000',
            ],
        ]);

        $discussion->replies()->create([
            'user_id' => $request->user()->id,
            'course_lesson_id' => $discussion->course_lesson_id,
            'message' => $data['message'],
        ]);

        $discussion->update([
            'is_answered' => true,
            'answered_at' => now(),
        ]);

        return back()->with(
            'success',
            'Jawaban admin berhasil dikirim.'
        );
    }

    public function updateStatus(
        Request $request,
        LessonDiscussion $discussion
    ): RedirectResponse {
        abort_if($discussion->parent_id !== null, 404);

        $data = $request->validate([
            'is_answered' => [
                'required',
                'boolean',
            ],
        ]);

        $isAnswered = (bool) $data['is_answered'];

        $discussion->update([
            'is_answered' => $isAnswered,
            'answered_at' => $isAnswered
                ? ($discussion->answered_at ?: now())
                : null,
        ]);

        return back()->with(
            'success',
            'Status diskusi berhasil diperbarui.'
        );
    }

    public function destroy(
        LessonDiscussion $discussion
    ): RedirectResponse {
        abort_if($discussion->parent_id !== null, 404);

        $discussion->delete();

        return redirect()
            ->route('admin.lesson-discussions.index')
            ->with('success', 'Diskusi berhasil dihapus.');
    }
}
