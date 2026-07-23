<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseLesson;
use App\Models\CourseModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CourseLessonController extends Controller
{
    public function store(Request $request, CourseModule $module)
    {
        $data = $this->validatedData($request);
        $this->ensureVideoSource($request, $data);

        $data['slug'] = Str::slug($data['title'])
            . '-'
            . Str::lower(Str::random(6));

        $this->handleUploads($request, $data);

        $module->lessons()->create($data);

        return back()->with('success', 'Video pembelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, CourseLesson $lesson)
    {
        $data = $this->validatedData($request);
        $this->ensureVideoSource($request, $data, $lesson);

        if ($lesson->title !== $data['title']) {
            $data['slug'] = Str::slug($data['title'])
                . '-'
                . Str::lower(Str::random(6));
        }

        $this->handleUploads($request, $data, $lesson);

        $lesson->update($data);

        return back()->with('success', 'Video pembelajaran berhasil diperbarui.');
    }

    public function destroy(CourseLesson $lesson)
    {
        if ($lesson->video_path) {
            Storage::disk('local')->delete($lesson->video_path);
        }

        if ($lesson->attachment_path) {
            Storage::disk('local')->delete($lesson->attachment_path);
        }

        $lesson->delete();

        return back()->with('success', 'Materi pembelajaran berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'video_type' => ['required', 'in:upload,youtube,vimeo,external'],
            'video_url' => ['nullable', 'url'],
            'video_file' => [
                'nullable',
                'file',
                'mimes:mp4,mov,webm,m4v',
                'max:512000',
            ],
            'attachment' => [
                'nullable',
                'file',
                'mimes:zip,rar,7z,pdf,doc,docx,xls,xlsx',
                'max:102400',
            ],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_preview' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $data['duration_minutes'] = $data['duration_minutes'] ?? 0;
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_preview'] = $request->boolean('is_preview');
        $data['is_published'] = $request->boolean('is_published');

        unset($data['video_file'], $data['attachment']);

        return $data;
    }

    private function ensureVideoSource(
        Request $request,
        array $data,
        ?CourseLesson $lesson = null
    ): void {
        if ($data['video_type'] === 'upload') {
            if (! $request->hasFile('video_file') && ! $lesson?->video_path) {
                throw ValidationException::withMessages([
                    'video_file' => 'File video wajib diunggah untuk tipe upload.',
                ]);
            }

            return;
        }

        if (blank($data['video_url'] ?? null)) {
            throw ValidationException::withMessages([
                'video_url' => 'URL video wajib diisi untuk tipe video ini.',
            ]);
        }
    }

    private function handleUploads(
        Request $request,
        array &$data,
        ?CourseLesson $lesson = null
    ): void {
        if ($data['video_type'] === 'upload') {
            $data['video_url'] = null;

            if ($request->hasFile('video_file')) {
                if ($lesson?->video_path) {
                    Storage::disk('local')->delete($lesson->video_path);
                }

                $data['video_path'] = $request->file('video_file')
                    ->store('courses/videos', 'local');
            }
        } else {
            if ($lesson?->video_path) {
                Storage::disk('local')->delete($lesson->video_path);
            }

            $data['video_path'] = null;
        }

        if ($request->hasFile('attachment')) {
            if ($lesson?->attachment_path) {
                Storage::disk('local')->delete($lesson->attachment_path);
            }

            $data['attachment_path'] = $request->file('attachment')
                ->store('courses/attachments', 'local');
        }
    }
}
