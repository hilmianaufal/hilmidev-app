<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseModuleController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $course->modules()->create([
            ...$data,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return back()->with('success', 'Modul kelas berhasil ditambahkan.');
    }

    public function update(Request $request, CourseModule $module)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $module->update([
            ...$data,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return back()->with('success', 'Modul kelas berhasil diperbarui.');
    }

    public function destroy(CourseModule $module)
    {
        $module->load('lessons');

        foreach ($module->lessons as $lesson) {
            if ($lesson->video_path) {
                Storage::disk('local')->delete($lesson->video_path);
            }

            if ($lesson->attachment_path) {
                Storage::disk('local')->delete($lesson->attachment_path);
            }
        }

        $module->delete();

        return back()->with('success', 'Modul kelas berhasil dihapus.');
    }
}
