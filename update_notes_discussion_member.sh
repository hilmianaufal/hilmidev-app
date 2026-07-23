#!/usr/bin/env bash

set -euo pipefail

if [ ! -f artisan ]; then
    echo "ERROR: Jalankan script dari folder utama project Laravel."
    exit 1
fi

STAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP_DIR="storage/app/notes-discussion-backup-$STAMP"

mkdir -p "$BACKUP_DIR"

FILES=(
    "routes/web.php"
    "app/Models/User.php"
    "app/Models/CourseLesson.php"
    "app/Http/Controllers/Member/LessonController.php"
    "resources/views/member/lessons/show.blade.php"
    "resources/views/components/layouts/admin.blade.php"
)

for file in "${FILES[@]}"; do
    if [ -f "$file" ]; then
        mkdir -p "$BACKUP_DIR/$(dirname "$file")"
        cp "$file" "$BACKUP_DIR/$file"
    fi
done

mkdir -p app/Models
mkdir -p app/Http/Controllers/Member
mkdir -p app/Http/Controllers/Admin
mkdir -p resources/views/admin/lesson-discussions

echo "Backup dibuat di: $BACKUP_DIR"

# ============================================================
# MIGRATION CATATAN
# ============================================================

if ! compgen -G "database/migrations/*_create_lesson_notes_table.php" > /dev/null; then
    php artisan make:migration create_lesson_notes_table
fi

NOTE_MIGRATION="$(ls -t database/migrations/*_create_lesson_notes_table.php | head -n 1)"

cat > "$NOTE_MIGRATION" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_notes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('course_lesson_id')
                ->constrained('course_lessons')
                ->cascadeOnDelete();

            $table->text('content');
            $table->timestamps();

            $table->unique(
                ['user_id', 'course_lesson_id'],
                'lesson_notes_user_lesson_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_notes');
    }
};
PHP

# ============================================================
# MIGRATION DISKUSI
# ============================================================

if ! compgen -G "database/migrations/*_create_lesson_discussions_table.php" > /dev/null; then
    php artisan make:migration create_lesson_discussions_table
fi

DISCUSSION_MIGRATION="$(ls -t database/migrations/*_create_lesson_discussions_table.php | head -n 1)"

cat > "$DISCUSSION_MIGRATION" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_discussions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('course_lesson_id')
                ->constrained('course_lessons')
                ->cascadeOnDelete();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('lesson_discussions')
                ->cascadeOnDelete();

            $table->text('message');
            $table->boolean('is_answered')->default(false);
            $table->timestamp('answered_at')->nullable();
            $table->timestamps();

            $table->index(
                ['course_lesson_id', 'parent_id'],
                'lesson_discussions_lesson_parent_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_discussions');
    }
};
PHP

# ============================================================
# MODEL LESSON NOTE
# ============================================================

cat > app/Models/LessonNote.php <<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonNote extends Model
{
    protected $fillable = [
        'user_id',
        'course_lesson_id',
        'content',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(
            CourseLesson::class,
            'course_lesson_id'
        );
    }
}
PHP

# ============================================================
# MODEL LESSON DISCUSSION
# ============================================================

cat > app/Models/LessonDiscussion.php <<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LessonDiscussion extends Model
{
    protected $fillable = [
        'user_id',
        'course_lesson_id',
        'parent_id',
        'message',
        'is_answered',
        'answered_at',
    ];

    protected function casts(): array
    {
        return [
            'is_answered' => 'boolean',
            'answered_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(
            CourseLesson::class,
            'course_lesson_id'
        );
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            self::class,
            'parent_id'
        );
    }

    public function replies(): HasMany
    {
        return $this->hasMany(
            self::class,
            'parent_id'
        )->oldest('id');
    }
}
PHP

# ============================================================
# TAMBAHKAN RELASI MODEL
# ============================================================

php <<'PHP'
<?php

function insertMethods(string $path, string $check, string $methods): void
{
    if (! file_exists($path)) {
        echo "File tidak ditemukan: {$path}\n";
        return;
    }

    $content = file_get_contents($path);

    if (str_contains($content, $check)) {
        echo "Relasi sudah ada: {$path}\n";
        return;
    }

    $position = strrpos($content, '}');

    if ($position === false) {
        throw new RuntimeException("Class penutup tidak ditemukan: {$path}");
    }

    $content = substr($content, 0, $position)
        . "\n"
        . $methods
        . "\n"
        . substr($content, $position);

    file_put_contents($path, $content);
}

insertMethods(
    'app/Models/User.php',
    'function lessonNotes(',
    <<<'CODE'
    public function lessonNotes()
    {
        return $this->hasMany(\App\Models\LessonNote::class);
    }

    public function lessonDiscussions()
    {
        return $this->hasMany(\App\Models\LessonDiscussion::class);
    }
CODE
);

insertMethods(
    'app/Models/CourseLesson.php',
    'function notes(',
    <<<'CODE'
    public function notes()
    {
        return $this->hasMany(
            \App\Models\LessonNote::class,
            'course_lesson_id'
        );
    }

    public function discussions()
    {
        return $this->hasMany(
            \App\Models\LessonDiscussion::class,
            'course_lesson_id'
        );
    }
CODE
);

echo "Relasi model berhasil diperbarui.\n";
PHP

# ============================================================
# CONTROLLER CATATAN MEMBER
# ============================================================

cat > app/Http/Controllers/Member/LessonNoteController.php <<'PHP'
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
PHP

# ============================================================
# CONTROLLER DISKUSI MEMBER
# ============================================================

cat > app/Http/Controllers/Member/LessonDiscussionController.php <<'PHP'
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
PHP

# ============================================================
# CONTROLLER DISKUSI ADMIN
# ============================================================

cat > app/Http/Controllers/Admin/LessonDiscussionController.php <<'PHP'
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
PHP

# ============================================================
# PERBARUI LESSON CONTROLLER MEMBER
# ============================================================

cat > app/Http/Controllers/Member/LessonController.php <<'PHP'
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
PHP

# ============================================================
# TAMBAHKAN ROUTE
# ============================================================

php <<'PHP'
<?php

$path = 'routes/web.php';
$content = file_get_contents($path);

$memberMarker = <<<'CODE'
        Route::post('/materi/{lesson}/progress', [
            \App\Http\Controllers\Member\LessonProgressController::class,
            'store',
        ])->name('lessons.progress');
CODE;

$memberRoutes = <<<'CODE'

        Route::post('/materi/{lesson}/catatan', [
            \App\Http\Controllers\Member\LessonNoteController::class,
            'store',
        ])->name('lessons.notes.store');

        Route::delete('/materi/{lesson}/catatan', [
            \App\Http\Controllers\Member\LessonNoteController::class,
            'destroy',
        ])->name('lessons.notes.destroy');

        Route::post('/materi/{lesson}/diskusi', [
            \App\Http\Controllers\Member\LessonDiscussionController::class,
            'store',
        ])->name('lessons.discussions.store');

        Route::delete('/diskusi/{discussion}', [
            \App\Http\Controllers\Member\LessonDiscussionController::class,
            'destroy',
        ])->name('discussions.destroy');
CODE;

if (
    ! str_contains($content, "name('lessons.notes.store')")
    && str_contains($content, $memberMarker)
) {
    $content = str_replace(
        $memberMarker,
        $memberMarker . $memberRoutes,
        $content
    );
}

$adminMarker = <<<'CODE'
        Route::delete('/lessons/{lesson}', [
            \App\Http\Controllers\Admin\CourseLessonController::class,
            'destroy',
        ])->name('lessons.destroy');
CODE;

$adminRoutes = <<<'CODE'

        Route::get('/lesson-discussions', [
            \App\Http\Controllers\Admin\LessonDiscussionController::class,
            'index',
        ])->name('lesson-discussions.index');

        Route::get('/lesson-discussions/{discussion}', [
            \App\Http\Controllers\Admin\LessonDiscussionController::class,
            'show',
        ])->name('lesson-discussions.show');

        Route::post('/lesson-discussions/{discussion}/reply', [
            \App\Http\Controllers\Admin\LessonDiscussionController::class,
            'reply',
        ])->name('lesson-discussions.reply');

        Route::patch('/lesson-discussions/{discussion}/status', [
            \App\Http\Controllers\Admin\LessonDiscussionController::class,
            'updateStatus',
        ])->name('lesson-discussions.status');

        Route::delete('/lesson-discussions/{discussion}', [
            \App\Http\Controllers\Admin\LessonDiscussionController::class,
            'destroy',
        ])->name('lesson-discussions.destroy');
CODE;

if (
    ! str_contains($content, "name('lesson-discussions.index')")
    && str_contains($content, $adminMarker)
) {
    $content = str_replace(
        $adminMarker,
        $adminMarker . $adminRoutes,
        $content
    );
}

file_put_contents($path, $content);

echo "Route catatan dan diskusi berhasil ditambahkan.\n";
PHP

# ============================================================
# TAMBAHKAN FORM KE HALAMAN VIDEO
# ============================================================

php <<'PHP'
<?php

$path = 'resources/views/member/lessons/show.blade.php';

if (! file_exists($path)) {
    throw new RuntimeException(
        'View member/lessons/show.blade.php tidak ditemukan.'
    );
}

$content = file_get_contents($path);

if (str_contains($content, 'HILMIDEV NOTES DISCUSSIONS')) {
    echo "Tampilan catatan dan diskusi sudah tersedia.\n";
    exit;
}

$marker = '                    {{-- PREVIOUS NEXT --}}';

$block = <<<'BLADE'

                    {{-- HILMIDEV NOTES DISCUSSIONS --}}
                    <section class="mt-6 grid items-start gap-6 lg:grid-cols-2">
                        {{-- CATATAN PRIBADI --}}
                        <article class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.16em] text-blue-700">
                                        Catatan Pribadi
                                    </p>

                                    <h2 class="mt-2 text-xl font-black text-slate-950">
                                        Tulis poin penting materi
                                    </h2>

                                    <p class="mt-2 text-xs font-medium leading-6 text-slate-500">
                                        Catatan ini bersifat pribadi dan hanya dapat dibaca melalui akun Anda.
                                    </p>
                                </div>

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-700">
                                    <i data-lucide="notebook-pen" class="h-5 w-5"></i>
                                </div>
                            </div>

                            <form method="POST"
                                  action="{{ route('member.lessons.notes.store', $lesson) }}"
                                  class="mt-6">
                                @csrf

                                <textarea
                                    name="content"
                                    rows="8"
                                    required
                                    maxlength="10000"
                                    placeholder="Contoh: Cara membuat migration, relasi model, fungsi controller..."
                                    class="w-full rounded-2xl border-slate-200 bg-slate-50 text-sm font-medium leading-7 focus:border-blue-500 focus:bg-white focus:ring-blue-100"
                                >{{ old('content', $note->content) }}</textarea>

                                @error('content')
                                    <p class="mt-2 text-xs font-semibold text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                                <div class="mt-4 flex flex-col gap-3 sm:flex-row">
                                    <button
                                        type="submit"
                                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-blue-700 px-5 py-3.5 text-xs font-black text-white transition hover:bg-blue-800"
                                    >
                                        <i data-lucide="save" class="h-4 w-4"></i>
                                        Simpan Catatan
                                    </button>
                                </div>
                            </form>

                            @if ($note->exists)
                                <form
                                    method="POST"
                                    action="{{ route('member.lessons.notes.destroy', $lesson) }}"
                                    class="mt-3"
                                    onsubmit="return confirm('Hapus catatan pribadi materi ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-red-100 bg-red-50 px-5 py-3 text-xs font-black text-red-600 transition hover:bg-red-100"
                                    >
                                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        Hapus Catatan
                                    </button>
                                </form>
                            @endif
                        </article>

                        {{-- FORUM DISKUSI --}}
                        <article class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.16em] text-blue-700">
                                        Forum Diskusi
                                    </p>

                                    <h2 class="mt-2 text-xl font-black text-slate-950">
                                        Tanya tentang materi
                                    </h2>

                                    <p class="mt-2 text-xs font-medium leading-6 text-slate-500">
                                        Tulis kendala atau pertanyaan agar dapat dijawab oleh admin.
                                    </p>
                                </div>

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-700">
                                    <i data-lucide="messages-square" class="h-5 w-5"></i>
                                </div>
                            </div>

                            <form
                                method="POST"
                                action="{{ route('member.lessons.discussions.store', $lesson) }}"
                                class="mt-6"
                            >
                                @csrf

                                <textarea
                                    name="message"
                                    rows="4"
                                    required
                                    maxlength="5000"
                                    placeholder="Tuliskan pertanyaan tentang materi ini..."
                                    class="w-full rounded-2xl border-slate-200 bg-slate-50 text-sm font-medium leading-7 focus:border-blue-500 focus:bg-white focus:ring-blue-100"
                                >{{ old('message') }}</textarea>

                                @error('message')
                                    <p class="mt-2 text-xs font-semibold text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                                <button
                                    type="submit"
                                    class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 py-3.5 text-xs font-black text-white transition hover:bg-blue-800"
                                >
                                    <i data-lucide="send" class="h-4 w-4"></i>
                                    Kirim Pertanyaan
                                </button>
                            </form>
                        </article>
                    </section>

                    {{-- DAFTAR DISKUSI --}}
                    <section class="mt-6 rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.16em] text-blue-700">
                                    Diskusi Materi
                                </p>

                                <h2 class="mt-2 text-2xl font-black text-slate-950">
                                    Pertanyaan dan jawaban
                                </h2>
                            </div>

                            <span class="inline-flex w-fit items-center gap-2 rounded-full bg-blue-50 px-4 py-2 text-xs font-black text-blue-700">
                                <i data-lucide="message-circle" class="h-4 w-4"></i>
                                {{ $discussions->count() }} diskusi
                            </span>
                        </div>

                        <div class="mt-7 space-y-5">
                            @forelse ($discussions as $discussion)
                                <article class="rounded-2xl border border-slate-200 bg-slate-50/60 p-5">
                                    <div class="flex items-start gap-4">
                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-700 text-sm font-black text-white">
                                            {{ strtoupper(substr($discussion->user?->name ?? 'M', 0, 1)) }}
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <p class="text-sm font-black text-slate-900">
                                                    {{ $discussion->user?->name ?? 'Member' }}
                                                </p>

                                                @if ($discussion->is_answered)
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-[9px] font-black text-emerald-700">
                                                        <i data-lucide="circle-check" class="h-3 w-3"></i>
                                                        Terjawab
                                                    </span>
                                                @else
                                                    <span class="rounded-full bg-amber-50 px-2.5 py-1 text-[9px] font-black text-amber-700">
                                                        Menunggu jawaban
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="mt-1 text-[10px] font-semibold text-slate-400">
                                                {{ $discussion->created_at->translatedFormat('d M Y H:i') }}
                                            </p>

                                            <p class="mt-4 whitespace-pre-line text-sm font-medium leading-7 text-slate-600">{{ $discussion->message }}</p>

                                            @if ($discussion->user_id === auth()->id())
                                                <form
                                                    method="POST"
                                                    action="{{ route('member.discussions.destroy', $discussion) }}"
                                                    class="mt-4"
                                                    onsubmit="return confirm('Hapus pertanyaan ini?')"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center gap-1.5 text-[10px] font-black text-red-600"
                                                    >
                                                        <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                                        Hapus pertanyaan
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($discussion->replies->count())
                                        <div class="ml-0 mt-5 space-y-3 border-l-2 border-blue-200 pl-4 sm:ml-14">
                                            @foreach ($discussion->replies as $reply)
                                                <div class="rounded-2xl border border-blue-100 bg-white p-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-cyan-100 text-xs font-black text-cyan-800">
                                                            {{ strtoupper(substr($reply->user?->name ?? 'A', 0, 1)) }}
                                                        </div>

                                                        <div>
                                                            <div class="flex flex-wrap items-center gap-2">
                                                                <p class="text-xs font-black text-slate-900">
                                                                    {{ $reply->user?->name ?? 'Admin' }}
                                                                </p>

                                                                @if ($reply->user?->isAdmin())
                                                                    <span class="rounded-full bg-blue-700 px-2 py-1 text-[8px] font-black uppercase text-white">
                                                                        Admin
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            <p class="mt-1 text-[9px] font-semibold text-slate-400">
                                                                {{ $reply->created_at->translatedFormat('d M Y H:i') }}
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <p class="mt-3 whitespace-pre-line text-sm font-medium leading-7 text-slate-600">{{ $reply->message }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </article>
                            @empty
                                <div class="rounded-2xl border border-dashed border-slate-300 px-6 py-12 text-center">
                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-700">
                                        <i data-lucide="message-circle-question" class="h-7 w-7"></i>
                                    </div>

                                    <p class="mt-5 font-black text-slate-900">
                                        Belum ada diskusi
                                    </p>

                                    <p class="mt-2 text-xs font-medium text-slate-500">
                                        Jadilah member pertama yang bertanya tentang materi ini.
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </section>

BLADE;

if (! str_contains($content, $marker)) {
    throw new RuntimeException(
        'Marker PREVIOUS NEXT tidak ditemukan pada view materi.'
    );
}

$content = str_replace(
    $marker,
    $block . "\n" . $marker,
    $content
);

file_put_contents($path, $content);

echo "Tampilan catatan dan diskusi berhasil ditambahkan.\n";
PHP

# ============================================================
# VIEW ADMIN INDEX DISKUSI
# ============================================================

cat > resources/views/admin/lesson-discussions/index.blade.php <<'BLADE'
<x-layouts.admin>
    <div class="mx-auto max-w-7xl px-4 py-10">
        <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.16em] text-blue-700">
                    Bimbingan Coding
                </p>

                <h1 class="mt-2 text-3xl font-black text-slate-950">
                    Diskusi Member
                </h1>

                <p class="mt-2 text-sm font-medium text-slate-500">
                    Jawab pertanyaan member pada setiap materi pembelajaran.
                </p>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-bold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET"
              class="mb-6 grid gap-3 rounded-[1.5rem] border border-slate-200 bg-white p-4 shadow-sm sm:grid-cols-12">
            <input
                name="q"
                value="{{ $search }}"
                placeholder="Cari member, materi, kelas, atau pertanyaan..."
                class="h-13 rounded-xl border-slate-200 sm:col-span-8"
            >

            <select
                name="status"
                class="h-13 rounded-xl border-slate-200 sm:col-span-2"
            >
                <option value="">Semua status</option>
                <option value="unanswered" @selected($status === 'unanswered')>
                    Belum dijawab
                </option>
                <option value="answered" @selected($status === 'answered')>
                    Sudah dijawab
                </option>
            </select>

            <button
                class="rounded-xl bg-blue-700 px-5 py-3 text-xs font-black text-white sm:col-span-2"
            >
                Cari Diskusi
            </button>
        </form>

        <div class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-sm">
                    <thead class="bg-slate-50 text-left text-[10px] font-black uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-4">Member</th>
                            <th class="px-5 py-4">Kelas dan Materi</th>
                            <th class="px-5 py-4">Pertanyaan</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($discussions as $discussion)
                            <tr class="transition hover:bg-blue-50/30">
                                <td class="px-5 py-5">
                                    <p class="font-black text-slate-900">
                                        {{ $discussion->user?->name ?? '-' }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $discussion->user?->email ?? '-' }}
                                    </p>

                                    <p class="mt-2 text-[10px] font-semibold text-slate-400">
                                        {{ $discussion->created_at->translatedFormat('d M Y H:i') }}
                                    </p>
                                </td>

                                <td class="px-5 py-5">
                                    <p class="font-black text-blue-700">
                                        {{ $discussion->lesson?->module?->course?->title ?? '-' }}
                                    </p>

                                    <p class="mt-1 max-w-xs text-xs font-semibold text-slate-500">
                                        {{ $discussion->lesson?->title ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-5 py-5">
                                    <p class="max-w-md line-clamp-3 text-sm font-medium leading-6 text-slate-600">
                                        {{ $discussion->message }}
                                    </p>

                                    <p class="mt-2 text-[10px] font-black text-blue-700">
                                        {{ $discussion->replies_count }} jawaban
                                    </p>
                                </td>

                                <td class="px-5 py-5">
                                    @if ($discussion->is_answered)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-2 text-[10px] font-black text-emerald-700">
                                            <i data-lucide="circle-check" class="h-3.5 w-3.5"></i>
                                            Terjawab
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-2 text-[10px] font-black text-amber-700">
                                            <i data-lucide="clock-3" class="h-3.5 w-3.5"></i>
                                            Menunggu
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-5 text-right">
                                    <a
                                        href="{{ route('admin.lesson-discussions.show', $discussion) }}"
                                        class="inline-flex items-center gap-2 rounded-xl bg-blue-700 px-4 py-3 text-xs font-black text-white"
                                    >
                                        <i data-lucide="message-square-reply" class="h-4 w-4"></i>
                                        Buka
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-blue-700">
                                        <i data-lucide="messages-square" class="h-8 w-8"></i>
                                    </div>

                                    <p class="mt-5 font-black text-slate-900">
                                        Belum ada diskusi member
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-7">
            {{ $discussions->links() }}
        </div>
    </div>
</x-layouts.admin>
BLADE

# ============================================================
# VIEW ADMIN DETAIL DISKUSI
# ============================================================

cat > resources/views/admin/lesson-discussions/show.blade.php <<'BLADE'
<x-layouts.admin>
    <div class="mx-auto max-w-5xl px-4 py-10">
        <a
            href="{{ route('admin.lesson-discussions.index') }}"
            class="inline-flex items-center gap-2 text-sm font-black text-blue-700"
        >
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Kembali ke Diskusi
        </a>

        @if (session('success'))
            <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-bold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-7 grid items-start gap-7 lg:grid-cols-12">
            <main class="space-y-6 lg:col-span-8">
                <article class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-700 text-sm font-black text-white">
                            {{ strtoupper(substr($discussion->user?->name ?? 'M', 0, 1)) }}
                        </div>

                        <div class="min-w-0">
                            <h1 class="text-lg font-black text-slate-950">
                                {{ $discussion->user?->name ?? 'Member' }}
                            </h1>

                            <p class="mt-1 text-xs font-medium text-slate-400">
                                {{ $discussion->user?->email }}
                                ·
                                {{ $discussion->created_at->translatedFormat('d M Y H:i') }}
                            </p>
                        </div>
                    </div>

                    <p class="mt-6 whitespace-pre-line text-sm font-medium leading-8 text-slate-600">{{ $discussion->message }}</p>
                </article>

                <section class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <h2 class="text-xl font-black text-slate-950">
                        Riwayat Jawaban
                    </h2>

                    <div class="mt-6 space-y-4">
                        @forelse ($discussion->replies as $reply)
                            <article class="rounded-2xl border border-blue-100 bg-blue-50/50 p-5">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-700 text-xs font-black text-white">
                                        {{ strtoupper(substr($reply->user?->name ?? 'A', 0, 1)) }}
                                    </div>

                                    <div>
                                        <p class="text-sm font-black text-slate-900">
                                            {{ $reply->user?->name ?? 'Admin' }}
                                        </p>

                                        <p class="mt-1 text-[10px] font-semibold text-slate-400">
                                            {{ $reply->created_at->translatedFormat('d M Y H:i') }}
                                        </p>
                                    </div>
                                </div>

                                <p class="mt-4 whitespace-pre-line text-sm font-medium leading-7 text-slate-600">{{ $reply->message }}</p>
                            </article>
                        @empty
                            <p class="rounded-2xl border border-dashed border-slate-300 px-5 py-10 text-center text-sm font-medium text-slate-500">
                                Pertanyaan ini belum memiliki jawaban.
                            </p>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <h2 class="text-xl font-black text-slate-950">
                        Kirim Jawaban Admin
                    </h2>

                    <form
                        method="POST"
                        action="{{ route('admin.lesson-discussions.reply', $discussion) }}"
                        class="mt-6"
                    >
                        @csrf

                        <textarea
                            name="message"
                            rows="7"
                            required
                            maxlength="5000"
                            placeholder="Tulis jawaban atau solusi untuk member..."
                            class="w-full rounded-2xl border-slate-200 bg-slate-50 text-sm font-medium leading-7 focus:border-blue-500 focus:bg-white focus:ring-blue-100"
                        >{{ old('message') }}</textarea>

                        @error('message')
                            <p class="mt-2 text-xs font-semibold text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                        <button
                            class="mt-4 inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-700 px-6 py-4 text-sm font-black text-white shadow-lg shadow-blue-700/20"
                        >
                            <i data-lucide="send" class="h-5 w-5"></i>
                            Kirim Jawaban
                        </button>
                    </form>
                </section>
            </main>

            <aside class="space-y-5 lg:col-span-4">
                <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-[0.15em] text-blue-700">
                        Informasi Materi
                    </p>

                    <h2 class="mt-3 text-lg font-black leading-7 text-slate-950">
                        {{ $discussion->lesson?->title ?? '-' }}
                    </h2>

                    <p class="mt-3 text-xs font-semibold leading-6 text-slate-500">
                        {{ $discussion->lesson?->module?->course?->title ?? '-' }}
                    </p>

                    <div class="mt-6 border-t border-slate-100 pt-5">
                        @if ($discussion->is_answered)
                            <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-4 py-2 text-xs font-black text-emerald-700">
                                <i data-lucide="circle-check" class="h-4 w-4"></i>
                                Sudah terjawab
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 rounded-full bg-amber-50 px-4 py-2 text-xs font-black text-amber-700">
                                <i data-lucide="clock-3" class="h-4 w-4"></i>
                                Menunggu jawaban
                            </span>
                        @endif
                    </div>
                </div>

                <form
                    method="POST"
                    action="{{ route('admin.lesson-discussions.status', $discussion) }}"
                    class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm"
                >
                    @csrf
                    @method('PATCH')

                    <input
                        type="hidden"
                        name="is_answered"
                        value="{{ $discussion->is_answered ? 0 : 1 }}"
                    >

                    <button
                        class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4 text-xs font-black text-blue-700"
                    >
                        <i data-lucide="refresh-cw" class="h-4 w-4"></i>

                        {{ $discussion->is_answered
                            ? 'Tandai Belum Dijawab'
                            : 'Tandai Sudah Dijawab' }}
                    </button>
                </form>

                <form
                    method="POST"
                    action="{{ route('admin.lesson-discussions.destroy', $discussion) }}"
                    onsubmit="return confirm('Hapus diskusi beserta seluruh jawabannya?')"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-xs font-black text-red-600"
                    >
                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                        Hapus Diskusi
                    </button>
                </form>
            </aside>
        </div>
    </div>
</x-layouts.admin>
BLADE

# ============================================================
# TAMBAHKAN MENU SIDEBAR ADMIN
# ============================================================

php <<'PHP'
<?php

$path = 'resources/views/components/layouts/admin.blade.php';

if (! file_exists($path)) {
    echo "Layout admin tidak ditemukan, menu diskusi dilewati.\n";
    exit;
}

$content = file_get_contents($path);

if (! str_contains($content, "admin.lesson-discussions")) {
    $titleMarker = <<<'CODE'
            request()->routeIs('admin.courses.*') => 'Kelas Coding',
CODE;

    $titleInsert = <<<'CODE'
            request()->routeIs('admin.courses.*') => 'Kelas Coding',
            request()->routeIs('admin.lesson-discussions.*') => 'Diskusi Member',
CODE;

    if (str_contains($content, $titleMarker)) {
        $content = str_replace(
            $titleMarker,
            $titleInsert,
            $content
        );
    }

    $courseItemEnd = <<<'CODE'
                        'icon' => 'graduation-cap',
                    ],
CODE;

    $discussionItem = <<<'CODE'
                        'icon' => 'graduation-cap',
                    ],
                    [
                        'label' => 'Diskusi Member',
                        'route' => 'admin.lesson-discussions.index',
                        'patterns' => ['admin.lesson-discussions.*'],
                        'icon' => 'message-circle-question',
                    ],
CODE;

    if (str_contains($content, $courseItemEnd)) {
        $content = str_replace(
            $courseItemEnd,
            $discussionItem,
            $content
        );
    }

    file_put_contents($path, $content);
}

echo "Menu Diskusi Member berhasil ditambahkan.\n";
PHP

# ============================================================
# MIGRATE DAN VALIDASI
# ============================================================

php artisan migrate

composer dump-autoload

php -l app/Models/LessonNote.php
php -l app/Models/LessonDiscussion.php
php -l app/Models/User.php
php -l app/Models/CourseLesson.php

php -l app/Http/Controllers/Member/LessonController.php
php -l app/Http/Controllers/Member/LessonNoteController.php
php -l app/Http/Controllers/Member/LessonDiscussionController.php
php -l app/Http/Controllers/Admin/LessonDiscussionController.php

php artisan optimize:clear
php artisan view:cache
php artisan view:clear

php artisan route:list --path=diskusi
php artisan route:list --path=lesson-discussions

echo ""
echo "============================================================"
echo "CATATAN DAN DISKUSI MEMBER BERHASIL DIBUAT"
echo "============================================================"
echo "Member : buka halaman video pembelajaran"
echo "Admin  : /admin/lesson-discussions"
echo "Backup : $BACKUP_DIR"
echo "============================================================"
