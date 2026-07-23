#!/usr/bin/env bash
set -euo pipefail

if [ ! -f artisan ]; then
    echo "ERROR: Jalankan dari folder utama project Laravel."
    exit 1
fi

STAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP_DIR="storage/app/member-feature-backup-$STAMP"
mkdir -p "$BACKUP_DIR"

for file in app/Models/User.php routes/web.php resources/views/layouts/navigation.blade.php resources/views/components/layouts/admin.blade.php resources/views/layouts/footer.blade.php; do
    if [ -f "$file" ]; then
        mkdir -p "$BACKUP_DIR/$(dirname "$file")"
        cp "$file" "$BACKUP_DIR/$file"
    fi
done

mkdir -p app/Http/Middleware app/Http/Controllers/Admin app/Http/Controllers/Member app/Models database/migrations database/seeders resources/views/courses resources/views/member/courses resources/views/member/lessons resources/views/admin/membership-plans resources/views/admin/members resources/views/admin/courses

cat > database/migrations/2026_07_22_220001_create_membership_learning_tables.php <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('price')->default(0);
            $table->unsignedInteger('duration_days')->nullable();
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('membership_plan_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('pending');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['user_id', 'status']);
            $table->index(['status', 'expires_at']);
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('level')->default('pemula');
            $table->string('technology')->nullable();
            $table->unsignedInteger('estimated_minutes')->default(0);
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('course_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('course_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_module_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('video_type')->default('upload');
            $table->string('video_path')->nullable();
            $table->text('video_url')->nullable();
            $table->string('attachment_path')->nullable();
            $table->unsignedInteger('duration_minutes')->default(0);
            $table->boolean('is_preview')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('course_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->timestamp('enrolled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'course_id']);
        });

        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_lesson_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('watched_seconds')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'course_lesson_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_progress');
        Schema::dropIfExists('course_enrollments');
        Schema::dropIfExists('course_lessons');
        Schema::dropIfExists('course_modules');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('memberships');
        Schema::dropIfExists('membership_plans');
    }
};
PHP

cat > app/Models/MembershipPlan.php <<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'price', 'duration_days',
        'features', 'is_active', 'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'duration_days' => 'integer',
            'features' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
PHP

cat > app/Models/Membership.php <<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [
        'user_id', 'membership_plan_id', 'status', 'starts_at',
        'expires_at', 'activated_at', 'notes', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'activated_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(MembershipPlan::class, 'membership_plan_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('status', 'active')
            ->where(fn (Builder $q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn (Builder $q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()));
    }

    public function isActive(): bool
    {
        return $this->status === 'active'
            && (! $this->starts_at || $this->starts_at->lte(now()))
            && (! $this->expires_at || $this->expires_at->gt(now()));
    }
}
PHP

cat > app/Models/Course.php <<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title', 'slug', 'subtitle', 'description', 'thumbnail', 'level',
        'technology', 'estimated_minutes', 'is_published', 'is_featured', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'estimated_minutes' => 'integer',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function modules()
    {
        return $this->hasMany(CourseModule::class)->orderBy('sort_order')->orderBy('id');
    }

    public function lessons()
    {
        return $this->hasManyThrough(
            CourseLesson::class,
            CourseModule::class,
            'course_id',
            'course_module_id'
        );
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function getFormattedDurationAttribute(): string
    {
        $hours = intdiv($this->estimated_minutes, 60);
        $minutes = $this->estimated_minutes % 60;

        return $hours > 0
            ? $hours . ' jam' . ($minutes ? ' ' . $minutes . ' menit' : '')
            : $minutes . ' menit';
    }
}
PHP

cat > app/Models/CourseModule.php <<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseModule extends Model
{
    protected $fillable = ['course_id', 'title', 'description', 'sort_order'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(CourseLesson::class)->orderBy('sort_order')->orderBy('id');
    }
}
PHP

cat > app/Models/CourseLesson.php <<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CourseLesson extends Model
{
    protected $fillable = [
        'course_module_id', 'title', 'slug', 'description', 'video_type',
        'video_path', 'video_url', 'attachment_path', 'duration_minutes',
        'is_preview', 'is_published', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'duration_minutes' => 'integer',
            'is_preview' => 'boolean',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'course_module_id');
    }

    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function embedUrl(): ?string
    {
        if (! $this->video_url) {
            return null;
        }

        $url = trim($this->video_url);

        if ($this->video_type === 'youtube') {
            if (str_contains($url, 'youtube.com/embed/')) {
                return $url;
            }

            if (preg_match('~youtu\.be/([^?&/]+)~', $url, $m)) {
                return 'https://www.youtube.com/embed/' . $m[1];
            }

            if (preg_match('~[?&]v=([^?&/]+)~', $url, $m)) {
                return 'https://www.youtube.com/embed/' . $m[1];
            }
        }

        if ($this->video_type === 'vimeo' && preg_match('~vimeo\.com/(?:video/)?(\d+)~', $url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }

        return $url;
    }
}
PHP

cat > app/Models/CourseEnrollment.php <<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseEnrollment extends Model
{
    protected $fillable = ['user_id', 'course_id', 'enrolled_at', 'completed_at'];

    protected function casts(): array
    {
        return ['enrolled_at' => 'datetime', 'completed_at' => 'datetime'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
PHP

cat > app/Models/LessonProgress.php <<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonProgress extends Model
{
    protected $table = 'lesson_progress';

    protected $fillable = [
        'user_id', 'course_lesson_id', 'watched_seconds',
        'is_completed', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'watched_seconds' => 'integer',
            'is_completed' => 'boolean',
            'completed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(CourseLesson::class, 'course_lesson_id');
    }
}
PHP

php <<'PHP'
<?php
$path = 'app/Models/User.php';
$content = file_get_contents($path);

if (! str_contains($content, 'function memberships()')) {
    $methods = <<<'CODE'

    public function memberships()
    {
        return $this->hasMany(\App\Models\Membership::class);
    }

    public function courseEnrollments()
    {
        return $this->hasMany(\App\Models\CourseEnrollment::class);
    }

    public function lessonProgress()
    {
        return $this->hasMany(\App\Models\LessonProgress::class);
    }

    public function activeMembership()
    {
        return $this->hasOne(\App\Models\Membership::class)
            ->ofMany('id', 'max', fn ($query) => $query->active());
    }

    public function hasActiveMembership(): bool
    {
        return $this->memberships()->active()->exists();
    }
CODE;

    $position = strrpos($content, '}');

    if ($position === false) {
        throw new RuntimeException('User model tidak valid.');
    }

    file_put_contents(
        $path,
        substr($content, 0, $position) . $methods . "\n}\n"
    );
}
PHP

cat > app/Http/Middleware/EnsureActiveMembership.php <<'PHP'
<?php

namespace App\Http\Middleware;

use App\Models\Membership;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveMembership
{
    public function handle(Request $request, Closure $next): Response
    {
        $membership = Membership::query()
            ->with('plan')
            ->where('user_id', $request->user()->id)
            ->active()
            ->latest('id')
            ->first();

        if (! $membership) {
            return redirect()
                ->route('courses.index')
                ->with('error', 'Akses kelas membutuhkan membership aktif.');
        }

        $request->attributes->set('activeMembership', $membership);

        return $next($request);
    }
}
PHP

cat > app/Http/Controllers/CourseCatalogController.php <<'PHP'
<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Membership;
use Illuminate\Http\Request;

class CourseCatalogController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('q', ''));
        $level = trim((string) $request->get('level', ''));

        $courses = Course::query()
            ->published()
            ->withCount([
                'modules',
                'lessons as lessons_count' => fn ($query) => $query->where('is_published', true),
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('subtitle', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('technology', 'like', "%{$search}%");
                });
            })
            ->when($level !== '', fn ($query) => $query->where('level', $level))
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $hasActiveMembership = auth()->check()
            && Membership::query()
                ->where('user_id', auth()->id())
                ->active()
                ->exists();

        return view('courses.index', compact(
            'courses',
            'search',
            'level',
            'hasActiveMembership'
        ));
    }

    public function show(Course $course)
    {
        abort_if(! $course->is_published, 404);

        $course->load([
            'modules.lessons' => fn ($query) => $query
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->orderBy('id'),
        ]);

        $hasActiveMembership = auth()->check()
            && Membership::query()
                ->where('user_id', auth()->id())
                ->active()
                ->exists();

        return view('courses.show', compact('course', 'hasActiveMembership'));
    }
}
PHP

cat > app/Http/Controllers/Member/DashboardController.php <<'PHP'
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
PHP

cat > app/Http/Controllers/Member/CourseController.php <<'PHP'
<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\LessonProgress;

class CourseController extends Controller
{
    public function show(Course $course)
    {
        abort_if(! $course->is_published, 404);

        CourseEnrollment::firstOrCreate(
            ['user_id' => auth()->id(), 'course_id' => $course->id],
            ['enrolled_at' => now()]
        );

        $course->load([
            'modules.lessons' => fn ($query) => $query
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->orderBy('id'),
        ]);

        $completedLessonIds = LessonProgress::query()
            ->where('user_id', auth()->id())
            ->where('is_completed', true)
            ->pluck('course_lesson_id');

        $allLessons = $course->modules->flatMap->lessons;
        $totalLessons = $allLessons->count();
        $completedLessons = $allLessons->whereIn('id', $completedLessonIds)->count();
        $percentage = $totalLessons > 0
            ? (int) round(($completedLessons / $totalLessons) * 100)
            : 0;

        return view('member.courses.show', compact(
            'course',
            'completedLessonIds',
            'totalLessons',
            'completedLessons',
            'percentage'
        ));
    }
}
PHP

cat > app/Http/Controllers/Member/LessonController.php <<'PHP'
<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\CourseEnrollment;
use App\Models\CourseLesson;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    public function show(CourseLesson $lesson)
    {
        $lesson->load('module.course');

        abort_if(! $lesson->is_published || ! $lesson->module->course->is_published, 404);

        $course = $lesson->module->course;

        CourseEnrollment::firstOrCreate(
            ['user_id' => auth()->id(), 'course_id' => $course->id],
            ['enrolled_at' => now()]
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

        $allLessons = $course->modules->flatMap->lessons->values();
        $currentIndex = $allLessons->search(fn ($item) => $item->id === $lesson->id);
        $previousLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
        $nextLesson = $currentIndex !== false && $currentIndex < $allLessons->count() - 1
            ? $allLessons[$currentIndex + 1]
            : null;

        $completedLessonIds = LessonProgress::query()
            ->where('user_id', auth()->id())
            ->where('is_completed', true)
            ->pluck('course_lesson_id');

        return view('member.lessons.show', compact(
            'lesson',
            'course',
            'progress',
            'previousLesson',
            'nextLesson',
            'completedLessonIds'
        ));
    }

    public function video(CourseLesson $lesson)
    {
        $lesson->load('module.course');

        abort_if(! $lesson->is_published || ! $lesson->module->course->is_published, 404);
        abort_unless($lesson->video_type === 'upload' && $lesson->video_path, 404);
        abort_unless(Storage::disk('local')->exists($lesson->video_path), 404);

        return response()->file(
            Storage::disk('local')->path($lesson->video_path),
            [
                'Content-Type' => Storage::disk('local')->mimeType($lesson->video_path) ?: 'video/mp4',
                'Accept-Ranges' => 'bytes',
                'Cache-Control' => 'private, no-store, max-age=0',
            ]
        );
    }

    public function attachment(CourseLesson $lesson)
    {
        $lesson->load('module.course');

        abort_if(! $lesson->is_published || ! $lesson->module->course->is_published, 404);
        abort_unless($lesson->attachment_path, 404);
        abort_unless(Storage::disk('local')->exists($lesson->attachment_path), 404);

        return Storage::disk('local')->download($lesson->attachment_path);
    }
}
PHP

cat > app/Http/Controllers/Member/LessonProgressController.php <<'PHP'
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
PHP

cat > app/Http/Controllers/Admin/MembershipPlanController.php <<'PHP'
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MembershipPlanController extends Controller
{
    public function index()
    {
        $plans = MembershipPlan::withCount('memberships')->latest()->paginate(12);

        return view('admin.membership-plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.membership-plans.create');
    }

    public function store(Request $request)
    {
        MembershipPlan::create($this->validatedData($request));

        return redirect()
            ->route('admin.membership-plans.index')
            ->with('success', 'Paket membership berhasil ditambahkan.');
    }

    public function edit(MembershipPlan $membershipPlan)
    {
        return view('admin.membership-plans.edit', compact('membershipPlan'));
    }

    public function update(Request $request, MembershipPlan $membershipPlan)
    {
        $membershipPlan->update($this->validatedData($request, $membershipPlan));

        return redirect()
            ->route('admin.membership-plans.index')
            ->with('success', 'Paket membership berhasil diperbarui.');
    }

    public function destroy(MembershipPlan $membershipPlan)
    {
        if ($membershipPlan->memberships()->exists()) {
            $membershipPlan->update(['is_active' => false]);

            return back()->with(
                'success',
                'Paket sudah digunakan sehingga dinonaktifkan, bukan dihapus.'
            );
        }

        $membershipPlan->delete();

        return back()->with('success', 'Paket membership berhasil dihapus.');
    }

    private function validatedData(
        Request $request,
        ?MembershipPlan $membershipPlan = null
    ): array {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('membership_plans', 'name')->ignore($membershipPlan?->id),
            ],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'duration_days' => ['nullable', 'integer', 'min:1'],
            'features' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = Str::slug($data['name'])
            . ($membershipPlan ? '' : '-' . Str::lower(Str::random(4)));

        $data['features'] = $request->filled('features')
            ? array_values(array_filter(array_map(
                'trim',
                preg_split('/\r\n|\r|\n/', (string) $request->features)
            )))
            : null;

        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        return $data;
    }
}
PHP

cat > app/Http/Controllers/Admin/MemberController.php <<'PHP'
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('q', ''));

        $members = User::query()
            ->where('role', '!=', 'admin')
            ->with(['memberships.plan'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.members.index', compact('members', 'search'));
    }

    public function create()
    {
        $plans = MembershipPlan::active()->orderBy('price')->get();

        return view('admin.members.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'membership_plan_id' => ['required', 'exists:membership_plans,id'],
            'status' => ['required', 'in:pending,active,suspended,expired,cancelled'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after:starts_at'],
            'notes' => ['nullable', 'string'],
        ]);

        $user = new User();
        $user->forceFill([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'client',
        ])->save();

        $this->saveMembership($user, $data);

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Akun member berhasil dibuat.');
    }

    public function edit(User $user)
    {
        abort_if($user->role === 'admin', 404);

        $user->load('memberships.plan');
        $plans = MembershipPlan::active()->orderBy('price')->get();
        $membership = $user->memberships->sortByDesc('id')->first();

        return view('admin.members.edit', compact('user', 'plans', 'membership'));
    }

    public function update(Request $request, User $user)
    {
        abort_if($user->role === 'admin', 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'membership_plan_id' => ['required', 'exists:membership_plans,id'],
            'status' => ['required', 'in:pending,active,suspended,expired,cancelled'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after:starts_at'],
            'notes' => ['nullable', 'string'],
        ]);

        $user->forceFill([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        $this->saveMembership($user, $data);

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Data dan akses member berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        abort_if($user->role === 'admin', 404);

        $user->memberships()
            ->whereIn('status', ['pending', 'active'])
            ->update(['status' => 'cancelled']);

        return back()->with('success', 'Akses membership berhasil dinonaktifkan.');
    }

    private function saveMembership(User $user, array $data): void
    {
        $plan = MembershipPlan::findOrFail($data['membership_plan_id']);
        $startsAt = ! empty($data['starts_at'])
            ? Carbon::parse($data['starts_at'])
            : now();

        $expiresAt = ! empty($data['expires_at'])
            ? Carbon::parse($data['expires_at'])
            : ($plan->duration_days
                ? $startsAt->copy()->addDays($plan->duration_days)
                : null);

        $membership = $user->memberships()->latest('id')->first();

        $payload = [
            'membership_plan_id' => $plan->id,
            'status' => $data['status'],
            'starts_at' => $startsAt,
            'expires_at' => $expiresAt,
            'activated_at' => $data['status'] === 'active'
                ? ($membership?->activated_at ?: now())
                : null,
            'notes' => $data['notes'] ?? null,
            'created_by' => auth()->id(),
        ];

        if ($membership) {
            $membership->update($payload);
        } else {
            $user->memberships()->create($payload);
        }
    }
}
PHP

cat > app/Http/Controllers/Admin/CourseController.php <<'PHP'
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('q', ''));

        $courses = Course::query()
            ->withCount(['modules', 'lessons'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('technology', 'like', "%{$search}%");
                });
            })
            ->orderBy('sort_order')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.courses.index', compact('courses', 'search'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')
                ->store('courses/thumbnails', 'public');
        }

        Course::create($data);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Kelas coding berhasil dibuat.');
    }

    public function edit(Course $course)
    {
        $course->load('modules.lessons');

        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $data = $this->validatedData($request, $course);

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }

            $data['thumbnail'] = $request->file('thumbnail')
                ->store('courses/thumbnails', 'public');
        }

        $course->update($data);

        return back()->with('success', 'Informasi kelas berhasil diperbarui.');
    }

    public function destroy(Course $course)
    {
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        foreach ($course->lessons as $lesson) {
            if ($lesson->video_path) {
                Storage::disk('local')->delete($lesson->video_path);
            }

            if ($lesson->attachment_path) {
                Storage::disk('local')->delete($lesson->attachment_path);
            }
        }

        $course->delete();

        return back()->with('success', 'Kelas coding berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Course $course = null): array
    {
        $data = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('courses', 'title')->ignore($course?->id),
            ],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'level' => ['required', 'in:pemula,menengah,mahir'],
            'technology' => ['nullable', 'string', 'max:255'],
            'estimated_minutes' => ['nullable', 'integer', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'is_published' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = Str::slug($data['title'])
            . ($course ? '' : '-' . Str::lower(Str::random(4)));
        $data['estimated_minutes'] = $data['estimated_minutes'] ?? 0;
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_published'] = $request->boolean('is_published');
        $data['is_featured'] = $request->boolean('is_featured');

        return $data;
    }
}
PHP

cat > app/Http/Controllers/Admin/CourseModuleController.php <<'PHP'
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseModule;
use Illuminate\Http\Request;

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
        $module->delete();

        return back()->with('success', 'Modul kelas berhasil dihapus.');
    }
}
PHP

cat > app/Http/Controllers/Admin/CourseLessonController.php <<'PHP'
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseLesson;
use App\Models\CourseModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseLessonController extends Controller
{
    public function store(Request $request, CourseModule $module)
    {
        $data = $this->validatedData($request);
        $data['slug'] = Str::slug($data['title']) . '-' . Str::lower(Str::random(6));
        $this->handleUploads($request, $data);

        $module->lessons()->create($data);

        return back()->with('success', 'Video pembelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, CourseLesson $lesson)
    {
        $data = $this->validatedData($request);

        if ($lesson->title !== $data['title']) {
            $data['slug'] = Str::slug($data['title']) . '-' . Str::lower(Str::random(6));
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
            'video_file' => ['nullable', 'file', 'mimes:mp4,mov,webm,m4v', 'max:512000'],
            'attachment' => ['nullable', 'file', 'mimes:zip,rar,7z,pdf,doc,docx,xls,xlsx', 'max:102400'],
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

    private function handleUploads(
        Request $request,
        array &$data,
        ?CourseLesson $lesson = null
    ): void {
        if ($request->hasFile('video_file')) {
            if ($lesson?->video_path) {
                Storage::disk('local')->delete($lesson->video_path);
            }

            $data['video_path'] = $request->file('video_file')
                ->store('courses/videos', 'local');
            $data['video_url'] = null;
            $data['video_type'] = 'upload';
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
PHP

cat > resources/views/courses/index.blade.php <<'BLADE'
<x-app-layout>
    <div class="min-h-screen bg-white">
        <section class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                @if (session('error'))
                    <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm font-bold text-amber-800">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="flex flex-col gap-7 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-blue-700">
                            HilmiDev Learning
                        </p>

                        <h1 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl lg:text-5xl">
                            Belajar Membuat Website dan Aplikasi
                        </h1>

                        <p class="mt-4 text-sm font-medium leading-7 text-slate-500 sm:text-base">
                            Video pembelajaran terstruktur dari persiapan project,
                            database, CRUD, UI, sampai deploy aplikasi ke server.
                        </p>
                    </div>

                    @auth
                        @if ($hasActiveMembership)
                            <a href="{{ route('member.dashboard') }}"
                               class="inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-700 px-6 py-4 text-sm font-black text-white shadow-lg shadow-blue-700/20 transition hover:bg-blue-800">
                                <i data-lucide="graduation-cap" class="h-5 w-5"></i>
                                Dashboard Member
                            </a>
                        @else
                            <div class="rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4">
                                <p class="text-xs font-black uppercase tracking-wider text-blue-700">Status</p>
                                <p class="mt-1 text-sm font-bold text-slate-700">Membership belum aktif</p>
                            </div>
                        @endif
                    @else
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-700 px-6 py-4 text-sm font-black text-white shadow-lg shadow-blue-700/20 transition hover:bg-blue-800">
                            <i data-lucide="user-plus" class="h-5 w-5"></i>
                            Daftar Akun
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        <section class="sticky top-[78px] z-30 border-b border-slate-200 bg-white/95 backdrop-blur-xl">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <form method="GET" action="{{ route('courses.index') }}" class="grid gap-3 md:grid-cols-12">
                    <div class="relative md:col-span-8">
                        <i data-lucide="search" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"></i>
                        <input type="search"
                               name="q"
                               value="{{ $search }}"
                               placeholder="Cari Laravel, PHP, Tailwind, Flutter..."
                               class="h-14 w-full rounded-2xl border-slate-200 bg-white pl-12 pr-4 text-sm font-semibold focus:border-blue-400 focus:ring-blue-100">
                    </div>

                    <div class="md:col-span-3">
                        <select name="level"
                                onchange="this.form.submit()"
                                class="h-14 w-full rounded-2xl border-slate-200 bg-white px-4 text-sm font-bold focus:border-blue-400 focus:ring-blue-100">
                            <option value="">Semua level</option>
                            <option value="pemula" @selected($level === 'pemula')>Pemula</option>
                            <option value="menengah" @selected($level === 'menengah')>Menengah</option>
                            <option value="mahir" @selected($level === 'mahir')>Mahir</option>
                        </select>
                    </div>

                    <button class="flex h-14 items-center justify-center rounded-2xl bg-blue-700 text-white md:col-span-1">
                        <i data-lucide="search" class="h-5 w-5"></i>
                    </button>
                </form>
            </div>
        </section>

        <main class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            @if ($courses->count())
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($courses as $course)
                        <article class="group flex h-full flex-col overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl">
                            <a href="{{ route('courses.show', $course) }}" class="block p-3 pb-0">
                                <div class="aspect-video overflow-hidden rounded-[1.3rem] border border-slate-100 bg-slate-50">
                                    @if ($course->thumbnail)
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}"
                                             alt="{{ $course->title }}"
                                             class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.03]">
                                    @else
                                        <div class="flex h-full items-center justify-center text-blue-700">
                                            <i data-lucide="graduation-cap" class="h-14 w-14"></i>
                                        </div>
                                    @endif
                                </div>
                            </a>

                            <div class="flex flex-1 flex-col p-6">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-[10px] font-black uppercase text-blue-700">
                                        {{ $course->level }}
                                    </span>

                                    @if ($course->is_featured)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-black text-amber-600">
                                            <i data-lucide="star" class="h-3.5 w-3.5 fill-current"></i>
                                            Pilihan
                                        </span>
                                    @endif
                                </div>

                                <a href="{{ route('courses.show', $course) }}">
                                    <h2 class="mt-4 text-xl font-black leading-7 text-slate-950 group-hover:text-blue-700">
                                        {{ $course->title }}
                                    </h2>
                                </a>

                                <p class="mt-3 line-clamp-3 text-sm font-medium leading-6 text-slate-500">
                                    {{ $course->subtitle ?: $course->description }}
                                </p>

                                <div class="mt-5 grid grid-cols-3 gap-2 border-t border-slate-100 pt-5 text-center">
                                    <div>
                                        <p class="text-base font-black text-slate-950">{{ $course->modules_count }}</p>
                                        <p class="text-[10px] font-bold text-slate-400">Modul</p>
                                    </div>
                                    <div>
                                        <p class="text-base font-black text-slate-950">{{ $course->lessons_count }}</p>
                                        <p class="text-[10px] font-bold text-slate-400">Video</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-950">{{ $course->formatted_duration }}</p>
                                        <p class="text-[10px] font-bold text-slate-400">Durasi</p>
                                    </div>
                                </div>

                                <a href="{{ route('courses.show', $course) }}"
                                   class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-3.5 text-xs font-black text-white transition hover:bg-blue-800">
                                    Lihat Materi
                                    <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-10 border-t border-slate-200 pt-6">
                    {{ $courses->links() }}
                </div>
            @else
                <div class="rounded-[2rem] border border-dashed border-slate-300 px-6 py-20 text-center">
                    <i data-lucide="search-x" class="mx-auto h-12 w-12 text-slate-400"></i>
                    <h2 class="mt-5 text-2xl font-black text-slate-950">Kelas tidak ditemukan</h2>
                </div>
            @endif
        </main>
    </div>
</x-app-layout>
BLADE

cat > resources/views/courses/show.blade.php <<'BLADE'
<x-app-layout>
    <div class="min-h-screen bg-white">
        <section class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
                <nav class="mb-7 flex items-center gap-2 text-xs font-semibold text-slate-400">
                    <a href="{{ route('courses.index') }}" class="hover:text-blue-700">Kelas Coding</a>
                    <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>
                    <span class="truncate font-bold text-slate-700">{{ $course->title }}</span>
                </nav>

                <div class="grid gap-10 lg:grid-cols-12">
                    <div class="lg:col-span-7">
                        <div class="overflow-hidden rounded-[2rem] border border-slate-200 p-3 shadow-xl shadow-slate-900/[0.05]">
                            <div class="aspect-video overflow-hidden rounded-[1.5rem] bg-slate-50">
                                @if ($course->thumbnail)
                                    <img src="{{ asset('storage/' . $course->thumbnail) }}"
                                         alt="{{ $course->title }}"
                                         class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full items-center justify-center text-blue-700">
                                        <i data-lucide="graduation-cap" class="h-20 w-20"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-5">
                        <div class="flex flex-wrap gap-2">
                            <span class="rounded-full bg-blue-50 px-3 py-2 text-xs font-black text-blue-700">
                                {{ strtoupper($course->level) }}
                            </span>

                            @if ($course->technology)
                                <span class="rounded-full border border-slate-200 px-3 py-2 text-xs font-black text-slate-600">
                                    {{ $course->technology }}
                                </span>
                            @endif
                        </div>

                        <h1 class="mt-5 text-3xl font-black leading-tight text-slate-950 sm:text-4xl">
                            {{ $course->title }}
                        </h1>

                        <p class="mt-4 text-sm font-medium leading-7 text-slate-500">
                            {{ $course->subtitle ?: $course->description }}
                        </p>

                        <div class="mt-6 grid grid-cols-3 divide-x divide-slate-200 rounded-2xl border border-slate-200 py-4 text-center">
                            <div>
                                <p class="text-xl font-black">{{ $course->modules->count() }}</p>
                                <p class="text-[10px] font-bold text-slate-400">MODUL</p>
                            </div>
                            <div>
                                <p class="text-xl font-black">{{ $course->modules->flatMap->lessons->count() }}</p>
                                <p class="text-[10px] font-bold text-slate-400">VIDEO</p>
                            </div>
                            <div>
                                <p class="text-sm font-black">{{ $course->formatted_duration }}</p>
                                <p class="text-[10px] font-bold text-slate-400">DURASI</p>
                            </div>
                        </div>

                        <div class="mt-7">
                            @auth
                                @if ($hasActiveMembership)
                                    <a href="{{ route('member.courses.show', $course) }}"
                                       class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-700 px-6 py-4 text-sm font-black text-white shadow-lg shadow-blue-700/20 transition hover:bg-blue-800">
                                        <i data-lucide="play-circle" class="h-5 w-5"></i>
                                        Mulai Belajar
                                    </a>
                                @else
                                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
                                        <p class="font-black text-amber-900">Membership belum aktif</p>
                                        <p class="mt-2 text-sm font-medium text-amber-700">
                                            Hubungi admin untuk mengaktifkan paket bimbingan.
                                        </p>
                                    </div>
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                   class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-700 px-6 py-4 text-sm font-black text-white">
                                    Login untuk Belajar
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <main class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
            <p class="text-xs font-black uppercase tracking-[0.16em] text-blue-700">Kurikulum</p>
            <h2 class="mt-2 text-3xl font-black text-slate-950">Materi yang akan dipelajari</h2>

            <div class="mt-8 space-y-4">
                @foreach ($course->modules as $module)
                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                        <div class="border-b border-slate-100 px-6 py-5">
                            <p class="text-xs font-black text-blue-700">MODUL {{ $loop->iteration }}</p>
                            <h3 class="mt-1 font-black text-slate-950">{{ $module->title }}</h3>
                        </div>

                        @forelse ($module->lessons as $lesson)
                            <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-6 py-4 last:border-0">
                                <div class="flex min-w-0 items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-slate-200 text-slate-500">
                                        <i data-lucide="{{ $lesson->is_preview ? 'play' : 'lock' }}" class="h-4 w-4"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-bold text-slate-700">{{ $lesson->title }}</p>
                                        <p class="mt-1 text-xs text-slate-400">{{ $lesson->duration_minutes }} menit</p>
                                    </div>
                                </div>

                                @if ($lesson->is_preview)
                                    <span class="rounded-full bg-emerald-50 px-3 py-1.5 text-[10px] font-black text-emerald-700">PREVIEW</span>
                                @endif
                            </div>
                        @empty
                            <p class="px-6 py-5 text-sm text-slate-500">Materi belum ditambahkan.</p>
                        @endforelse
                    </section>
                @endforeach
            </div>
        </main>
    </div>
</x-app-layout>
BLADE

cat > resources/views/member/dashboard.blade.php <<'BLADE'
<x-app-layout>
    <div class="min-h-screen bg-white">
        <section class="border-b border-blue-800 bg-blue-700 text-white">
            <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-blue-200">Member Area</p>
                        <h1 class="mt-3 text-3xl font-black sm:text-4xl">Selamat belajar, {{ auth()->user()->name }}</h1>
                        <p class="mt-3 text-sm font-medium text-blue-100">Lanjutkan progress dan bangun aplikasi pertamamu.</p>
                    </div>

                    <div class="rounded-2xl border border-white/15 bg-white/10 px-5 py-4 backdrop-blur">
                        <p class="text-[10px] font-black uppercase tracking-wider text-blue-200">Paket Aktif</p>
                        <p class="mt-1 font-black">{{ $membership->plan->name ?? 'Membership' }}</p>
                        <p class="mt-1 text-xs text-blue-100">
                            @if ($membership->expires_at)
                                Sampai {{ $membership->expires_at->translatedFormat('d F Y') }}
                            @else
                                Akses selamanya
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-bold text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 p-6">
                    <p class="text-xs font-black uppercase tracking-wider text-slate-400">Kelas tersedia</p>
                    <p class="mt-2 text-3xl font-black text-slate-950">{{ $totalCourses }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 p-6">
                    <p class="text-xs font-black uppercase tracking-wider text-slate-400">Kelas diikuti</p>
                    <p class="mt-2 text-3xl font-black text-blue-700">{{ $enrolledCourses }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 p-6">
                    <p class="text-xs font-black uppercase tracking-wider text-slate-400">Video selesai</p>
                    <p class="mt-2 text-3xl font-black text-emerald-600">{{ $completedLessons }}</p>
                </div>
            </div>

            @if ($lastProgress?->lesson?->module?->course)
                <section class="mt-8 rounded-[1.75rem] border border-blue-200 bg-blue-50 p-6 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-wider text-blue-700">Terakhir dipelajari</p>
                        <h2 class="mt-2 text-xl font-black text-slate-950">{{ $lastProgress->lesson->title }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $lastProgress->lesson->module->course->title }}</p>
                    </div>
                    <a href="{{ route('member.lessons.show', $lastProgress->lesson) }}"
                       class="mt-5 inline-flex items-center gap-2 rounded-xl bg-blue-700 px-5 py-3 text-xs font-black text-white sm:mt-0">
                        Lanjutkan
                        <i data-lucide="arrow-right" class="h-4 w-4"></i>
                    </a>
                </section>
            @endif

            <section class="mt-10">
                <div class="mb-6 flex items-end justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.16em] text-blue-700">Kelas Saya</p>
                        <h2 class="mt-2 text-3xl font-black text-slate-950">Pilih kelas dan mulai belajar</h2>
                    </div>
                    <a href="{{ route('courses.index') }}" class="hidden text-sm font-black text-blue-700 sm:block">Lihat katalog</a>
                </div>

                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($courseCards as $card)
                        @php($course = $card['course'])
                        <article class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
                            <div class="p-3 pb-0">
                                <div class="aspect-video overflow-hidden rounded-[1.3rem] bg-slate-50">
                                    @if ($course->thumbnail)
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}"
                                             alt="{{ $course->title }}"
                                             class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full items-center justify-center text-blue-700">
                                            <i data-lucide="graduation-cap" class="h-12 w-12"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="p-6">
                                <h3 class="text-lg font-black text-slate-950">{{ $course->title }}</h3>
                                <p class="mt-2 line-clamp-2 text-sm font-medium text-slate-500">{{ $course->subtitle }}</p>

                                <div class="mt-5">
                                    <div class="flex items-center justify-between text-xs font-bold">
                                        <span class="text-slate-500">{{ $card['completed'] }}/{{ $card['total'] }} video</span>
                                        <span class="text-blue-700">{{ $card['percentage'] }}%</span>
                                    </div>
                                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">
                                        <div class="h-full rounded-full bg-blue-700" style="width: {{ $card['percentage'] }}%"></div>
                                    </div>
                                </div>

                                <a href="{{ route('member.courses.show', $course) }}"
                                   class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-3.5 text-xs font-black text-white">
                                    {{ $card['percentage'] > 0 ? 'Lanjutkan Belajar' : 'Mulai Belajar' }}
                                    <i data-lucide="play" class="h-4 w-4"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        </main>
    </div>
</x-app-layout>
BLADE

cat > resources/views/member/courses/show.blade.php <<'BLADE'
<x-app-layout>
    <div class="min-h-screen bg-white">
        <section class="border-b border-slate-200">
            <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
                <a href="{{ route('member.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-black text-blue-700">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i>
                    Dashboard Member
                </a>

                <div class="mt-6 grid gap-8 lg:grid-cols-12">
                    <div class="lg:col-span-8">
                        <p class="text-xs font-black uppercase tracking-[0.16em] text-blue-700">Kelas Coding</p>
                        <h1 class="mt-3 text-3xl font-black text-slate-950 sm:text-4xl">{{ $course->title }}</h1>
                        <p class="mt-4 text-sm font-medium leading-7 text-slate-500">{{ $course->subtitle }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 p-5 lg:col-span-4">
                        <div class="flex items-center justify-between text-sm font-bold">
                            <span>Progress</span>
                            <span class="text-blue-700">{{ $percentage }}%</span>
                        </div>
                        <div class="mt-3 h-3 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-blue-700" style="width: {{ $percentage }}%"></div>
                        </div>
                        <p class="mt-3 text-xs text-slate-500">{{ $completedLessons }} dari {{ $totalLessons }} video selesai</p>
                    </div>
                </div>
            </div>
        </section>

        <main class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="space-y-5">
                @foreach ($course->modules as $module)
                    <section class="overflow-hidden rounded-2xl border border-slate-200">
                        <div class="border-b border-slate-100 px-6 py-5">
                            <p class="text-xs font-black text-blue-700">MODUL {{ $loop->iteration }}</p>
                            <h2 class="mt-1 text-lg font-black text-slate-950">{{ $module->title }}</h2>
                        </div>

                        @forelse ($module->lessons as $lesson)
                            @php($completed = $completedLessonIds->contains($lesson->id))
                            <a href="{{ route('member.lessons.show', $lesson) }}"
                               class="flex items-center justify-between gap-4 border-b border-slate-100 px-6 py-4 transition last:border-0 hover:bg-blue-50/50">
                                <div class="flex min-w-0 items-center gap-4">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $completed ? 'bg-emerald-50 text-emerald-600' : 'bg-blue-50 text-blue-700' }}">
                                        <i data-lucide="{{ $completed ? 'circle-check' : 'play' }}" class="h-5 w-5"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-black text-slate-800">{{ $lesson->title }}</p>
                                        <p class="mt-1 text-xs text-slate-400">{{ $lesson->duration_minutes }} menit</p>
                                    </div>
                                </div>

                                <i data-lucide="chevron-right" class="h-5 w-5 text-slate-300"></i>
                            </a>
                        @empty
                            <p class="px-6 py-5 text-sm text-slate-500">Belum ada video.</p>
                        @endforelse
                    </section>
                @endforeach
            </div>
        </main>
    </div>
</x-app-layout>
BLADE

cat > resources/views/member/lessons/show.blade.php <<'BLADE'
<x-app-layout>
    <div class="min-h-screen bg-white">
        <div class="mx-auto max-w-[1500px] px-4 py-8 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-bold text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid items-start gap-8 xl:grid-cols-12">
                <main class="xl:col-span-9">
                    <nav class="mb-5 flex items-center gap-2 text-xs font-semibold text-slate-400">
                        <a href="{{ route('member.courses.show', $course) }}" class="hover:text-blue-700">{{ $course->title }}</a>
                        <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>
                        <span class="truncate font-bold text-slate-700">{{ $lesson->title }}</span>
                    </nav>

                    <div class="overflow-hidden rounded-[1.5rem] bg-slate-950 shadow-2xl">
                        <div class="aspect-video">
                            @if ($lesson->video_type === 'upload' && $lesson->video_path)
                                <video id="lesson-video"
                                       class="h-full w-full"
                                       controls
                                       controlsList="nodownload"
                                       preload="metadata">
                                    <source src="{{ route('member.lessons.video', $lesson) }}">
                                </video>
                            @elseif ($lesson->embedUrl())
                                <iframe src="{{ $lesson->embedUrl() }}"
                                        class="h-full w-full"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                </iframe>
                            @else
                                <div class="flex h-full items-center justify-center text-center text-white">
                                    <div>
                                        <i data-lucide="video-off" class="mx-auto h-12 w-12 text-slate-400"></i>
                                        <p class="mt-4 font-black">Video belum tersedia</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 rounded-[1.5rem] border border-slate-200 p-6">
                        <p class="text-xs font-black uppercase tracking-[0.16em] text-blue-700">{{ $lesson->module->title }}</p>
                        <h1 class="mt-2 text-2xl font-black text-slate-950">{{ $lesson->title }}</h1>
                        <p class="mt-4 whitespace-pre-line text-sm font-medium leading-7 text-slate-500">{{ $lesson->description }}</p>

                        <div class="mt-6 flex flex-col gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex gap-3">
                                @if ($previousLesson)
                                    <a href="{{ route('member.lessons.show', $previousLesson) }}"
                                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-3 text-xs font-black text-slate-700">
                                        <i data-lucide="arrow-left" class="h-4 w-4"></i>
                                        Sebelumnya
                                    </a>
                                @endif

                                @if ($nextLesson)
                                    <a href="{{ route('member.lessons.show', $nextLesson) }}"
                                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-3 text-xs font-black text-slate-700">
                                        Selanjutnya
                                        <i data-lucide="arrow-right" class="h-4 w-4"></i>
                                    </a>
                                @endif
                            </div>

                            <form action="{{ route('member.lessons.progress', $lesson) }}" method="POST">
                                @csrf
                                <input type="hidden" name="is_completed" value="1">
                                <input id="watched-seconds-input" type="hidden" name="watched_seconds" value="{{ $progress->watched_seconds }}">

                                <button class="inline-flex items-center justify-center gap-2 rounded-xl {{ $progress->is_completed ? 'bg-emerald-600' : 'bg-blue-700' }} px-5 py-3 text-xs font-black text-white">
                                    <i data-lucide="{{ $progress->is_completed ? 'circle-check' : 'check' }}" class="h-4 w-4"></i>
                                    {{ $progress->is_completed ? 'Sudah Selesai' : 'Tandai Selesai' }}
                                </button>
                            </form>
                        </div>

                        @if ($lesson->attachment_path)
                            <a href="{{ route('member.lessons.attachment', $lesson) }}"
                               class="mt-5 inline-flex items-center gap-2 rounded-xl bg-slate-950 px-5 py-3 text-xs font-black text-white">
                                <i data-lucide="download" class="h-4 w-4"></i>
                                Download File Materi
                            </a>
                        @endif
                    </div>
                </main>

                <aside class="xl:col-span-3">
                    <div class="sticky top-28 max-h-[calc(100vh-8rem)] overflow-y-auto rounded-[1.5rem] border border-slate-200">
                        <div class="border-b border-slate-100 p-5">
                            <p class="text-xs font-black uppercase tracking-wider text-blue-700">Daftar Materi</p>
                            <h2 class="mt-2 font-black text-slate-950">{{ $course->title }}</h2>
                        </div>

                        @foreach ($course->modules as $module)
                            <div class="border-b border-slate-100 last:border-0">
                                <div class="bg-slate-50 px-5 py-3">
                                    <p class="text-xs font-black text-slate-700">{{ $module->title }}</p>
                                </div>

                                @foreach ($module->lessons as $sidebarLesson)
                                    @php($isCurrent = $sidebarLesson->id === $lesson->id)
                                    @php($isCompleted = $completedLessonIds->contains($sidebarLesson->id))

                                    <a href="{{ route('member.lessons.show', $sidebarLesson) }}"
                                       class="flex items-center gap-3 border-t border-slate-100 px-5 py-3 text-xs font-bold transition {{ $isCurrent ? 'bg-blue-700 text-white' : 'text-slate-600 hover:bg-blue-50' }}">
                                        <i data-lucide="{{ $isCompleted ? 'circle-check' : 'play' }}"
                                           class="h-4 w-4 shrink-0 {{ ! $isCurrent && $isCompleted ? 'text-emerald-600' : '' }}"></i>
                                        <span class="line-clamp-2">{{ $sidebarLesson->title }}</span>
                                    </a>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const video = document.getElementById('lesson-video');
            const watchedInput = document.getElementById('watched-seconds-input');

            if (video && watchedInput) {
                video.addEventListener('timeupdate', function () {
                    watchedInput.value = Math.floor(video.currentTime || 0);
                });
            }
        });
    </script>
</x-app-layout>
BLADE

cat > resources/views/admin/membership-plans/index.blade.php <<'BLADE'
<x-layouts.admin>
    <div class="mx-auto max-w-7xl px-4 py-10">
        <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.16em] text-blue-700">Membership</p>
                <h1 class="mt-2 text-3xl font-black text-slate-950">Paket Membership</h1>
                <p class="mt-2 text-sm text-slate-500">Atur harga, durasi, dan fasilitas bimbingan.</p>
            </div>
            <a href="{{ route('admin.membership-plans.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-700 px-5 py-4 text-sm font-black text-white">
                <i data-lucide="plus" class="h-5 w-5"></i>
                Tambah Paket
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 font-bold text-emerald-700">{{ session('success') }}</div>
        @endif

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($plans as $plan)
                <article class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <span class="rounded-full {{ $plan->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }} px-3 py-1.5 text-[10px] font-black uppercase">
                                {{ $plan->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                            <h2 class="mt-4 text-xl font-black text-slate-950">{{ $plan->name }}</h2>
                        </div>

                        @if ($plan->is_featured)
                            <i data-lucide="star" class="h-5 w-5 fill-amber-400 text-amber-400"></i>
                        @endif
                    </div>

                    <p class="mt-4 text-2xl font-black text-blue-700">Rp {{ number_format($plan->price, 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs font-bold text-slate-400">
                        {{ $plan->duration_days ? $plan->duration_days . ' hari' : 'Akses selamanya' }}
                    </p>

                    <p class="mt-4 line-clamp-3 text-sm leading-6 text-slate-500">{{ $plan->description }}</p>

                    <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-5">
                        <span class="text-xs font-bold text-slate-500">{{ $plan->memberships_count }} membership</span>

                        <div class="flex gap-2">
                            <a href="{{ route('admin.membership-plans.edit', $plan) }}" class="rounded-xl border border-slate-200 p-3 text-blue-700">
                                <i data-lucide="pencil" class="h-4 w-4"></i>
                            </a>

                            <form method="POST" action="{{ route('admin.membership-plans.destroy', $plan) }}">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-xl border border-red-100 p-3 text-red-600">
                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-8">{{ $plans->links() }}</div>
    </div>
</x-layouts.admin>
BLADE

cat > resources/views/admin/membership-plans/create.blade.php <<'BLADE'
<x-layouts.admin>
    <div class="mx-auto max-w-3xl px-4 py-10">
        <a href="{{ route('admin.membership-plans.index') }}" class="text-sm font-black text-blue-700">← Kembali</a>
        <h1 class="mt-5 text-3xl font-black text-slate-950">Tambah Paket Membership</h1>

        <form method="POST" action="{{ route('admin.membership-plans.store') }}" class="mt-8 space-y-5 rounded-[2rem] border border-slate-200 bg-white p-7 shadow-sm">
            @csrf
            @include('admin.membership-plans.form', ['plan' => null])
        </form>
    </div>
</x-layouts.admin>
BLADE

cat > resources/views/admin/membership-plans/edit.blade.php <<'BLADE'
<x-layouts.admin>
    <div class="mx-auto max-w-3xl px-4 py-10">
        <a href="{{ route('admin.membership-plans.index') }}" class="text-sm font-black text-blue-700">← Kembali</a>
        <h1 class="mt-5 text-3xl font-black text-slate-950">Edit Paket Membership</h1>

        <form method="POST" action="{{ route('admin.membership-plans.update', $membershipPlan) }}" class="mt-8 space-y-5 rounded-[2rem] border border-slate-200 bg-white p-7 shadow-sm">
            @csrf
            @method('PUT')
            @include('admin.membership-plans.form', ['plan' => $membershipPlan])
        </form>
    </div>
</x-layouts.admin>
BLADE

cat > resources/views/admin/membership-plans/form.blade.php <<'BLADE'
<div>
    <label class="mb-2 block text-sm font-black text-slate-700">Nama Paket</label>
    <input name="name" value="{{ old('name', $plan?->name) }}" class="w-full rounded-2xl border-slate-200" required>
    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label class="mb-2 block text-sm font-black text-slate-700">Deskripsi</label>
    <textarea name="description" rows="4" class="w-full rounded-2xl border-slate-200">{{ old('description', $plan?->description) }}</textarea>
</div>

<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Harga</label>
        <input type="number" min="0" name="price" value="{{ old('price', $plan?->price ?? 0) }}" class="w-full rounded-2xl border-slate-200" required>
    </div>
    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Durasi Hari</label>
        <input type="number" min="1" name="duration_days" value="{{ old('duration_days', $plan?->duration_days) }}" class="w-full rounded-2xl border-slate-200" placeholder="Kosong = selamanya">
    </div>
</div>

<div>
    <label class="mb-2 block text-sm font-black text-slate-700">Fasilitas</label>
    <textarea name="features" rows="6" class="w-full rounded-2xl border-slate-200" placeholder="Satu fasilitas per baris">{{ old('features', $plan?->features ? implode("\n", $plan->features) : '') }}</textarea>
</div>

<div class="flex flex-wrap gap-6">
    <label class="flex items-center gap-2 font-bold text-slate-700">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $plan?->is_active ?? true))>
        Paket aktif
    </label>

    <label class="flex items-center gap-2 font-bold text-slate-700">
        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $plan?->is_featured ?? false))>
        Paket pilihan
    </label>
</div>

<button class="w-full rounded-2xl bg-blue-700 px-6 py-4 font-black text-white">Simpan Paket</button>
BLADE

cat > resources/views/admin/members/index.blade.php <<'BLADE'
<x-layouts.admin>
    <div class="mx-auto max-w-7xl px-4 py-10">
        <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.16em] text-blue-700">Bimbingan Coding</p>
                <h1 class="mt-2 text-3xl font-black text-slate-950">Manajemen Member</h1>
                <p class="mt-2 text-sm text-slate-500">Buat akun dan atur masa aktif peserta.</p>
            </div>

            <a href="{{ route('admin.members.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-blue-700 px-5 py-4 text-sm font-black text-white">
                <i data-lucide="user-plus" class="h-5 w-5"></i>
                Tambah Member
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 font-bold text-emerald-700">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-6 flex gap-3">
            <input name="q" value="{{ $search }}" placeholder="Cari nama atau email..." class="h-14 flex-1 rounded-2xl border-slate-200">
            <button class="rounded-2xl bg-slate-950 px-6 text-white">
                <i data-lucide="search" class="h-5 w-5"></i>
            </button>
        </form>

        <div class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                        <tr>
                            <th class="px-5 py-4">Member</th>
                            <th class="px-5 py-4">Paket</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4">Masa Aktif</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($members as $user)
                            @php($membership = $user->memberships->sortByDesc('id')->first())

                            <tr>
                                <td class="px-5 py-5">
                                    <p class="font-black text-slate-900">{{ $user->name }}</p>
                                    <p class="mt-1 text-xs text-slate-400">{{ $user->email }}</p>
                                </td>

                                <td class="px-5 py-5 font-bold text-slate-700">{{ $membership?->plan?->name ?? '-' }}</td>

                                <td class="px-5 py-5">
                                    <span class="rounded-full px-3 py-1.5 text-[10px] font-black uppercase {{ $membership?->isActive() ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $membership?->status ?? 'belum member' }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 text-xs font-bold text-slate-500">
                                    {{ $membership?->expires_at?->translatedFormat('d M Y') ?? ($membership ? 'Selamanya' : '-') }}
                                </td>

                                <td class="px-5 py-5">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.members.edit', $user) }}" class="rounded-xl border border-slate-200 p-3 text-blue-700">
                                            <i data-lucide="settings-2" class="h-4 w-4"></i>
                                        </a>

                                        @if ($membership)
                                            <form method="POST" action="{{ route('admin.members.destroy', $user) }}">
                                                @csrf
                                                @method('DELETE')

                                                <button class="rounded-xl border border-red-100 p-3 text-red-600">
                                                    <i data-lucide="user-x" class="h-4 w-4"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-16 text-center text-slate-500">
                                    Belum ada client/member.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8">{{ $members->links() }}</div>
    </div>
</x-layouts.admin>
BLADE

cat > resources/views/admin/members/create.blade.php <<'BLADE'
<x-layouts.admin>
    <div class="mx-auto max-w-4xl px-4 py-10">
        <a href="{{ route('admin.members.index') }}" class="text-sm font-black text-blue-700">← Kembali</a>
        <h1 class="mt-5 text-3xl font-black text-slate-950">Buat Akun Member</h1>

        <form method="POST" action="{{ route('admin.members.store') }}" class="mt-8 space-y-6 rounded-[2rem] border border-slate-200 bg-white p-7 shadow-sm">
            @csrf
            @include('admin.members.form', ['user' => null, 'membership' => null])
        </form>
    </div>
</x-layouts.admin>
BLADE

cat > resources/views/admin/members/edit.blade.php <<'BLADE'
<x-layouts.admin>
    <div class="mx-auto max-w-4xl px-4 py-10">
        <a href="{{ route('admin.members.index') }}" class="text-sm font-black text-blue-700">← Kembali</a>
        <h1 class="mt-5 text-3xl font-black text-slate-950">Atur Membership {{ $user->name }}</h1>

        <form method="POST" action="{{ route('admin.members.update', $user) }}" class="mt-8 space-y-6 rounded-[2rem] border border-slate-200 bg-white p-7 shadow-sm">
            @csrf
            @method('PUT')
            @include('admin.members.form', ['user' => $user, 'membership' => $membership])
        </form>
    </div>
</x-layouts.admin>
BLADE

cat > resources/views/admin/members/form.blade.php <<'BLADE'
<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Nama</label>
        <input name="name" value="{{ old('name', $user?->name) }}" class="w-full rounded-2xl border-slate-200" required>
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Email</label>
        <input type="email" name="email" value="{{ old('email', $user?->email) }}" class="w-full rounded-2xl border-slate-200" required>
        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Password {{ $user ? '(opsional)' : '' }}</label>
        <input type="password" name="password" class="w-full rounded-2xl border-slate-200" {{ $user ? '' : 'required' }}>
    </div>

    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="w-full rounded-2xl border-slate-200" {{ $user ? '' : 'required' }}>
    </div>
</div>

<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Paket</label>
        <select name="membership_plan_id" class="w-full rounded-2xl border-slate-200" required>
            <option value="">Pilih paket</option>

            @foreach ($plans as $plan)
                <option value="{{ $plan->id }}" @selected((string) old('membership_plan_id', $membership?->membership_plan_id) === (string) $plan->id)>
                    {{ $plan->name }} — Rp {{ number_format($plan->price, 0, ',', '.') }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Status</label>
        <select name="status" class="w-full rounded-2xl border-slate-200" required>
            @foreach (['pending', 'active', 'suspended', 'expired', 'cancelled'] as $status)
                <option value="{{ $status }}" @selected(old('status', $membership?->status ?? 'active') === $status)>
                    {{ strtoupper($status) }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Mulai Aktif</label>
        <input type="datetime-local"
               name="starts_at"
               value="{{ old('starts_at', $membership?->starts_at?->format('Y-m-d\TH:i')) }}"
               class="w-full rounded-2xl border-slate-200">
    </div>

    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Berakhir</label>
        <input type="datetime-local"
               name="expires_at"
               value="{{ old('expires_at', $membership?->expires_at?->format('Y-m-d\TH:i')) }}"
               class="w-full rounded-2xl border-slate-200">

        <p class="mt-1 text-xs text-slate-400">Kosongkan agar otomatis mengikuti durasi paket.</p>
    </div>
</div>

<div>
    <label class="mb-2 block text-sm font-black text-slate-700">Catatan</label>
    <textarea name="notes" rows="4" class="w-full rounded-2xl border-slate-200">{{ old('notes', $membership?->notes) }}</textarea>
</div>

<button class="w-full rounded-2xl bg-blue-700 px-6 py-4 font-black text-white">Simpan Data Member</button>
BLADE

cat > resources/views/admin/courses/index.blade.php <<'BLADE'
<x-layouts.admin>
    <div class="mx-auto max-w-7xl px-4 py-10">
        <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.16em] text-blue-700">Learning Management</p>
                <h1 class="mt-2 text-3xl font-black text-slate-950">Kelas Coding</h1>
                <p class="mt-2 text-sm text-slate-500">Kelola kelas, modul, video, dan file materi.</p>
            </div>

            <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-blue-700 px-5 py-4 text-sm font-black text-white">
                <i data-lucide="plus" class="h-5 w-5"></i>
                Tambah Kelas
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 font-bold text-emerald-700">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-6 flex gap-3">
            <input name="q" value="{{ $search }}" placeholder="Cari kelas..." class="h-14 flex-1 rounded-2xl border-slate-200">
            <button class="rounded-2xl bg-slate-950 px-6 text-white">
                <i data-lucide="search" class="h-5 w-5"></i>
            </button>
        </form>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($courses as $course)
                <article class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
                    <div class="p-3 pb-0">
                        <div class="aspect-video overflow-hidden rounded-[1.3rem] bg-slate-50">
                            @if ($course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full items-center justify-center text-blue-700">
                                    <i data-lucide="graduation-cap" class="h-12 w-12"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <span class="rounded-full px-3 py-1.5 text-[10px] font-black uppercase {{ $course->is_published ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                {{ $course->is_published ? 'Published' : 'Draft' }}
                            </span>

                            <span class="text-xs font-bold text-slate-400">{{ strtoupper($course->level) }}</span>
                        </div>

                        <h2 class="mt-4 text-xl font-black text-slate-950">{{ $course->title }}</h2>
                        <p class="mt-2 line-clamp-2 text-sm text-slate-500">{{ $course->subtitle }}</p>

                        <div class="mt-5 flex gap-4 border-t border-slate-100 pt-5 text-xs font-bold text-slate-500">
                            <span>{{ $course->modules_count }} modul</span>
                            <span>{{ $course->lessons_count }} video</span>
                        </div>

                        <div class="mt-5 flex gap-2">
                            <a href="{{ route('admin.courses.edit', $course) }}" class="flex-1 rounded-xl bg-blue-700 px-4 py-3 text-center text-xs font-black text-white">
                                Kelola Materi
                            </a>

                            <form method="POST" action="{{ route('admin.courses.destroy', $course) }}">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-xl border border-red-100 p-3 text-red-600">
                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-8">{{ $courses->links() }}</div>
    </div>
</x-layouts.admin>
BLADE

cat > resources/views/admin/courses/create.blade.php <<'BLADE'
<x-layouts.admin>
    <div class="mx-auto max-w-4xl px-4 py-10">
        <a href="{{ route('admin.courses.index') }}" class="text-sm font-black text-blue-700">← Kembali</a>
        <h1 class="mt-5 text-3xl font-black text-slate-950">Tambah Kelas Coding</h1>

        <form method="POST" action="{{ route('admin.courses.store') }}" enctype="multipart/form-data" class="mt-8 space-y-6 rounded-[2rem] border border-slate-200 bg-white p-7 shadow-sm">
            @csrf
            @include('admin.courses.form', ['course' => null])
        </form>
    </div>
</x-layouts.admin>
BLADE

cat > resources/views/admin/courses/form.blade.php <<'BLADE'
<div>
    <label class="mb-2 block text-sm font-black text-slate-700">Judul Kelas</label>
    <input name="title" value="{{ old('title', $course?->title) }}" class="w-full rounded-2xl border-slate-200" required>
</div>

<div>
    <label class="mb-2 block text-sm font-black text-slate-700">Subjudul</label>
    <input name="subtitle" value="{{ old('subtitle', $course?->subtitle) }}" class="w-full rounded-2xl border-slate-200">
</div>

<div>
    <label class="mb-2 block text-sm font-black text-slate-700">Deskripsi</label>
    <textarea name="description" rows="6" class="w-full rounded-2xl border-slate-200">{{ old('description', $course?->description) }}</textarea>
</div>

<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Level</label>
        <select name="level" class="w-full rounded-2xl border-slate-200">
            @foreach (['pemula', 'menengah', 'mahir'] as $level)
                <option value="{{ $level }}" @selected(old('level', $course?->level ?? 'pemula') === $level)>{{ ucfirst($level) }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Teknologi</label>
        <input name="technology" value="{{ old('technology', $course?->technology) }}" class="w-full rounded-2xl border-slate-200" placeholder="Laravel, Tailwind">
    </div>

    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Estimasi Menit</label>
        <input type="number" min="0" name="estimated_minutes" value="{{ old('estimated_minutes', $course?->estimated_minutes ?? 0) }}" class="w-full rounded-2xl border-slate-200">
    </div>

    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Urutan</label>
        <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $course?->sort_order ?? 0) }}" class="w-full rounded-2xl border-slate-200">
    </div>
</div>

<div>
    <label class="mb-2 block text-sm font-black text-slate-700">Thumbnail</label>
    <input type="file" name="thumbnail" accept="image/*" class="w-full rounded-2xl border border-slate-200 p-3">
</div>

<div class="flex flex-wrap gap-6">
    <label class="flex items-center gap-2 font-bold text-slate-700">
        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $course?->is_published ?? false))>
        Published
    </label>

    <label class="flex items-center gap-2 font-bold text-slate-700">
        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $course?->is_featured ?? false))>
        Kelas pilihan
    </label>
</div>

<button class="w-full rounded-2xl bg-blue-700 px-6 py-4 font-black text-white">Simpan Kelas</button>
BLADE

cat > resources/views/admin/courses/edit.blade.php <<'BLADE'
<x-layouts.admin>
    <div class="mx-auto max-w-7xl px-4 py-10">
        <a href="{{ route('admin.courses.index') }}" class="text-sm font-black text-blue-700">← Kembali</a>

        @if (session('success'))
            <div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 font-bold text-emerald-700">{{ session('success') }}</div>
        @endif

        <div class="mt-6 grid items-start gap-8 xl:grid-cols-12">
            <section class="xl:col-span-5">
                <h1 class="text-3xl font-black text-slate-950">Edit Kelas</h1>

                <form method="POST" action="{{ route('admin.courses.update', $course) }}" enctype="multipart/form-data" class="mt-6 space-y-5 rounded-[2rem] border border-slate-200 bg-white p-7 shadow-sm">
                    @csrf
                    @method('PUT')
                    @include('admin.courses.form', ['course' => $course])
                </form>
            </section>

            <section class="xl:col-span-7">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.16em] text-blue-700">Kurikulum</p>
                    <h2 class="mt-2 text-3xl font-black text-slate-950">Modul dan Video</h2>
                </div>

                <form method="POST" action="{{ route('admin.courses.modules.store', $course) }}" class="mt-6 grid gap-3 rounded-2xl border border-blue-200 bg-blue-50 p-5 sm:grid-cols-12">
                    @csrf
                    <input name="title" placeholder="Judul modul baru" class="rounded-xl border-blue-200 sm:col-span-7" required>
                    <input type="number" name="sort_order" min="0" value="0" class="rounded-xl border-blue-200 sm:col-span-2">
                    <button class="rounded-xl bg-blue-700 px-4 py-3 text-xs font-black text-white sm:col-span-3">Tambah Modul</button>
                </form>

                <div class="mt-6 space-y-6">
                    @foreach ($course->modules as $module)
                        <article class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
                            <div class="border-b border-slate-100 p-5">
                                <form method="POST" action="{{ route('admin.modules.update', $module) }}" class="grid gap-3 sm:grid-cols-12">
                                    @csrf
                                    @method('PUT')

                                    <input name="title" value="{{ $module->title }}" class="rounded-xl border-slate-200 font-bold sm:col-span-7">
                                    <input type="number" name="sort_order" value="{{ $module->sort_order }}" min="0" class="rounded-xl border-slate-200 sm:col-span-2">
                                    <button class="rounded-xl border border-slate-200 px-3 text-xs font-black text-blue-700 sm:col-span-2">Simpan</button>
                                </form>

                                <form method="POST" action="{{ route('admin.modules.destroy', $module) }}" class="mt-3">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs font-black text-red-600">Hapus modul</button>
                                </form>
                            </div>

                            <div class="divide-y divide-slate-100">
                                @foreach ($module->lessons as $lesson)
                                    <details>
                                        <summary class="flex cursor-pointer items-center justify-between gap-4 px-5 py-4">
                                            <div>
                                                <p class="text-sm font-black text-slate-800">{{ $lesson->title }}</p>
                                                <p class="mt-1 text-xs text-slate-400">{{ strtoupper($lesson->video_type) }} · {{ $lesson->duration_minutes }} menit</p>
                                            </div>

                                            <span class="rounded-full {{ $lesson->is_published ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }} px-3 py-1.5 text-[10px] font-black">
                                                {{ $lesson->is_published ? 'Published' : 'Draft' }}
                                            </span>
                                        </summary>

                                        <form method="POST" action="{{ route('admin.lessons.update', $lesson) }}" enctype="multipart/form-data" class="grid gap-4 border-t border-slate-100 bg-slate-50 p-5">
                                            @csrf
                                            @method('PUT')

                                            <input name="title" value="{{ $lesson->title }}" class="rounded-xl border-slate-200" required>
                                            <textarea name="description" rows="3" class="rounded-xl border-slate-200">{{ $lesson->description }}</textarea>

                                            <div class="grid gap-3 sm:grid-cols-3">
                                                <select name="video_type" class="rounded-xl border-slate-200">
                                                    @foreach (['upload', 'youtube', 'vimeo', 'external'] as $type)
                                                        <option value="{{ $type }}" @selected($lesson->video_type === $type)>{{ strtoupper($type) }}</option>
                                                    @endforeach
                                                </select>

                                                <input name="video_url" value="{{ $lesson->video_url }}" placeholder="URL video" class="rounded-xl border-slate-200 sm:col-span-2">
                                            </div>

                                            <div class="grid gap-3 sm:grid-cols-2">
                                                <label class="rounded-xl border border-slate-200 bg-white p-3 text-xs font-bold">
                                                    Ganti video upload
                                                    <input type="file" name="video_file" accept="video/mp4,video/webm,video/quicktime" class="mt-2 block w-full">
                                                </label>

                                                <label class="rounded-xl border border-slate-200 bg-white p-3 text-xs font-bold">
                                                    Ganti file materi
                                                    <input type="file" name="attachment" class="mt-2 block w-full">
                                                </label>
                                            </div>

                                            <div class="grid gap-3 sm:grid-cols-2">
                                                <input type="number" min="0" name="duration_minutes" value="{{ $lesson->duration_minutes }}" placeholder="Durasi menit" class="rounded-xl border-slate-200">
                                                <input type="number" min="0" name="sort_order" value="{{ $lesson->sort_order }}" placeholder="Urutan" class="rounded-xl border-slate-200">
                                            </div>

                                            <div class="flex gap-5 text-sm font-bold">
                                                <label><input type="checkbox" name="is_preview" value="1" @checked($lesson->is_preview)> Preview</label>
                                                <label><input type="checkbox" name="is_published" value="1" @checked($lesson->is_published)> Published</label>
                                            </div>

                                            <button class="rounded-xl bg-blue-700 px-5 py-3 text-xs font-black text-white">Simpan Video</button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.lessons.destroy', $lesson) }}" class="bg-slate-50 px-5 pb-5">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-xs font-black text-red-600">Hapus video</button>
                                        </form>
                                    </details>
                                @endforeach
                            </div>

                            <details class="border-t border-slate-100">
                                <summary class="cursor-pointer px-5 py-4 text-sm font-black text-blue-700">+ Tambah Video Pembelajaran</summary>

                                <form method="POST" action="{{ route('admin.modules.lessons.store', $module) }}" enctype="multipart/form-data" class="grid gap-4 bg-blue-50 p-5">
                                    @csrf

                                    <input name="title" placeholder="Judul video" class="rounded-xl border-blue-200" required>
                                    <textarea name="description" rows="3" placeholder="Deskripsi materi" class="rounded-xl border-blue-200"></textarea>

                                    <div class="grid gap-3 sm:grid-cols-3">
                                        <select name="video_type" class="rounded-xl border-blue-200">
                                            <option value="upload">UPLOAD PRIVATE</option>
                                            <option value="youtube">YOUTUBE</option>
                                            <option value="vimeo">VIMEO</option>
                                            <option value="external">EXTERNAL</option>
                                        </select>

                                        <input name="video_url" placeholder="URL video bila tidak upload" class="rounded-xl border-blue-200 sm:col-span-2">
                                    </div>

                                    <div class="grid gap-3 sm:grid-cols-2">
                                        <label class="rounded-xl border border-blue-200 bg-white p-3 text-xs font-bold">
                                            File video
                                            <input type="file" name="video_file" accept="video/mp4,video/webm,video/quicktime" class="mt-2 block w-full">
                                        </label>

                                        <label class="rounded-xl border border-blue-200 bg-white p-3 text-xs font-bold">
                                            File source code/materi
                                            <input type="file" name="attachment" class="mt-2 block w-full">
                                        </label>
                                    </div>

                                    <div class="grid gap-3 sm:grid-cols-2">
                                        <input type="number" min="0" name="duration_minutes" value="0" placeholder="Durasi menit" class="rounded-xl border-blue-200">
                                        <input type="number" min="0" name="sort_order" value="0" placeholder="Urutan" class="rounded-xl border-blue-200">
                                    </div>

                                    <div class="flex gap-5 text-sm font-bold">
                                        <label><input type="checkbox" name="is_preview" value="1"> Preview publik</label>
                                        <label><input type="checkbox" name="is_published" value="1" checked> Published</label>
                                    </div>

                                    <button class="rounded-xl bg-blue-700 px-5 py-3 text-xs font-black text-white">Tambah Video</button>
                                </form>
                            </details>
                        </article>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
</x-layouts.admin>
BLADE

cat > database/seeders/MemberLearningSeeder.php <<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\MembershipPlan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MemberLearningSeeder extends Seeder
{
    public function run(): void
    {
        $monthly = MembershipPlan::updateOrCreate(
            ['slug' => 'member-1-bulan'],
            [
                'name' => 'Member 1 Bulan',
                'description' => 'Akses seluruh video pembelajaran selama satu bulan.',
                'price' => 299000,
                'duration_days' => 30,
                'features' => [
                    'Akses seluruh kelas coding',
                    'Download source code materi',
                    'Progress belajar otomatis',
                    'Support konsultasi',
                ],
                'is_active' => true,
                'is_featured' => false,
            ]
        );

        MembershipPlan::updateOrCreate(
            ['slug' => 'member-3-bulan'],
            [
                'name' => 'Member 3 Bulan',
                'description' => 'Paket intensif untuk menyelesaikan project website.',
                'price' => 699000,
                'duration_days' => 90,
                'features' => [
                    'Akses seluruh kelas coding',
                    'Download source code materi',
                    'Progress belajar otomatis',
                    'Support konsultasi',
                    'Review project',
                ],
                'is_active' => true,
                'is_featured' => true,
            ]
        );

        MembershipPlan::updateOrCreate(
            ['slug' => 'member-lifetime'],
            [
                'name' => 'Member Lifetime',
                'description' => 'Akses kelas dan update materi tanpa batas waktu.',
                'price' => 1499000,
                'duration_days' => null,
                'features' => [
                    'Akses selamanya',
                    'Semua kelas baru',
                    'Download source code',
                    'Support prioritas',
                ],
                'is_active' => true,
                'is_featured' => false,
            ]
        );

        $course = Course::updateOrCreate(
            ['slug' => 'laravel-dari-nol'],
            [
                'title' => 'Membuat Aplikasi Laravel dari Nol',
                'subtitle' => 'Belajar Laravel, Tailwind CSS, database, CRUD, autentikasi, dan deploy.',
                'description' => 'Kelas terstruktur untuk pemula yang ingin mampu membuat aplikasi website profesional dari awal sampai online.',
                'level' => 'pemula',
                'technology' => 'Laravel, PHP, MySQL, Tailwind CSS',
                'estimated_minutes' => 420,
                'is_published' => true,
                'is_featured' => true,
                'sort_order' => 1,
            ]
        );

        $modules = [
            [
                'title' => 'Persiapan Project',
                'sort_order' => 1,
                'lessons' => [
                    'Pengenalan Alur Belajar',
                    'Instalasi Laravel dan Composer',
                    'Mengenal Struktur Folder Laravel',
                ],
            ],
            [
                'title' => 'Database dan CRUD',
                'sort_order' => 2,
                'lessons' => [
                    'Konfigurasi Database MySQL',
                    'Membuat Migration dan Model',
                    'Membuat CRUD Data',
                ],
            ],
            [
                'title' => 'UI dan Deployment',
                'sort_order' => 3,
                'lessons' => [
                    'Membuat Dashboard dengan Tailwind',
                    'Validasi dan SweetAlert',
                    'Deploy Aplikasi ke Server',
                ],
            ],
        ];

        foreach ($modules as $moduleData) {
            $module = $course->modules()->updateOrCreate(
                ['title' => $moduleData['title']],
                [
                    'description' => null,
                    'sort_order' => $moduleData['sort_order'],
                ]
            );

            foreach ($moduleData['lessons'] as $index => $title) {
                $module->lessons()->updateOrCreate(
                    ['slug' => Str::slug($course->slug . '-' . $title)],
                    [
                        'title' => $title,
                        'description' => 'Tambahkan video pembelajaran dan file materi melalui admin panel.',
                        'video_type' => 'upload',
                        'duration_minutes' => 30,
                        'is_preview' => $moduleData['sort_order'] === 1 && $index === 0,
                        'is_published' => true,
                        'sort_order' => $index + 1,
                    ]
                );
            }
        }

        $user = User::firstOrNew(['email' => 'member@hilmidev.test']);

        $user->forceFill([
            'name' => 'Member Demo',
            'password' => Hash::make('password'),
            'role' => 'client',
        ])->save();

        $user->memberships()->updateOrCreate(
            ['membership_plan_id' => $monthly->id],
            [
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => now()->addYear(),
                'activated_at' => now(),
                'notes' => 'Akun demo hasil seeder.',
            ]
        );
    }
}
PHP

if ! grep -q "HILMIDEV MEMBER LEARNING" routes/web.php; then
cat >> routes/web.php <<'PHP'

/*
|--------------------------------------------------------------------------
| HILMIDEV MEMBER LEARNING
|--------------------------------------------------------------------------
*/
Route::get('/kelas-coding', [
    \App\Http\Controllers\CourseCatalogController::class,
    'index',
])->name('courses.index');

Route::get('/kelas-coding/{course}', [
    \App\Http\Controllers\CourseCatalogController::class,
    'show',
])->name('courses.show');

Route::middleware([
    'auth',
    \App\Http\Middleware\EnsureActiveMembership::class,
])
    ->prefix('member')
    ->name('member.')
    ->group(function () {
        Route::get('/dashboard', [
            \App\Http\Controllers\Member\DashboardController::class,
            'index',
        ])->name('dashboard');

        Route::get('/kelas/{course}', [
            \App\Http\Controllers\Member\CourseController::class,
            'show',
        ])->name('courses.show');

        Route::get('/materi/{lesson}', [
            \App\Http\Controllers\Member\LessonController::class,
            'show',
        ])->name('lessons.show');

        Route::get('/materi/{lesson}/video', [
            \App\Http\Controllers\Member\LessonController::class,
            'video',
        ])->name('lessons.video');

        Route::get('/materi/{lesson}/attachment', [
            \App\Http\Controllers\Member\LessonController::class,
            'attachment',
        ])->name('lessons.attachment');

        Route::post('/materi/{lesson}/progress', [
            \App\Http\Controllers\Member\LessonProgressController::class,
            'store',
        ])->name('lessons.progress');
    });

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource(
            'membership-plans',
            \App\Http\Controllers\Admin\MembershipPlanController::class
        )->except('show');

        Route::resource(
            'members',
            \App\Http\Controllers\Admin\MemberController::class
        )->except('show');

        Route::resource(
            'courses',
            \App\Http\Controllers\Admin\CourseController::class
        )->except('show');

        Route::post('/courses/{course}/modules', [
            \App\Http\Controllers\Admin\CourseModuleController::class,
            'store',
        ])->name('courses.modules.store');

        Route::put('/modules/{module}', [
            \App\Http\Controllers\Admin\CourseModuleController::class,
            'update',
        ])->name('modules.update');

        Route::delete('/modules/{module}', [
            \App\Http\Controllers\Admin\CourseModuleController::class,
            'destroy',
        ])->name('modules.destroy');

        Route::post('/modules/{module}/lessons', [
            \App\Http\Controllers\Admin\CourseLessonController::class,
            'store',
        ])->name('modules.lessons.store');

        Route::put('/lessons/{lesson}', [
            \App\Http\Controllers\Admin\CourseLessonController::class,
            'update',
        ])->name('lessons.update');

        Route::delete('/lessons/{lesson}', [
            \App\Http\Controllers\Admin\CourseLessonController::class,
            'destroy',
        ])->name('lessons.destroy');
    });
PHP
fi

php <<'PHP'
<?php

$path = 'resources/views/layouts/navigation.blade.php';

if (file_exists($path)) {
    $content = file_get_contents($path);

    if (! str_contains($content, "route('courses.index')")) {
        $anchor = <<<'BLADE'

                    <a href="{{ route('courses.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl px-4 py-3 text-sm font-bold transition
                       {{ request()->routeIs('courses.*', 'member.*')
                           ? 'bg-white text-blue-900 shadow-lg'
                           : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                        <i data-lucide="graduation-cap" class="h-4 w-4"></i>
                        Kelas Coding
                    </a>
BLADE;

        $position = strpos($content, '@auth');

        if ($position !== false) {
            file_put_contents(
                $path,
                substr($content, 0, $position)
                . $anchor
                . "\n"
                . substr($content, $position)
            );
        }
    }
}
PHP

php <<'PHP'
<?php

$path = 'resources/views/components/layouts/admin.blade.php';

if (file_exists($path)) {
    $content = file_get_contents($path);

    if (! str_contains($content, "route('admin.members.index')")) {
        $links = <<<'BLADE'

            <a href="{{ route('admin.members.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-blue-50 text-slate-700 font-semibold">
                <i data-lucide="users-round"></i>
                Member
            </a>

            <a href="{{ route('admin.membership-plans.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-blue-50 text-slate-700 font-semibold">
                <i data-lucide="badge-dollar-sign"></i>
                Paket Member
            </a>

            <a href="{{ route('admin.courses.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-blue-50 text-slate-700 font-semibold">
                <i data-lucide="graduation-cap"></i>
                Kelas Coding
            </a>
BLADE;

        $position = strpos($content, '</nav>');

        if ($position !== false) {
            file_put_contents(
                $path,
                substr($content, 0, $position)
                . $links
                . "\n"
                . substr($content, $position)
            );
        }
    }
}
PHP

php <<'PHP'
<?php

$path = 'resources/views/layouts/footer.blade.php';

if (file_exists($path)) {
    $content = file_get_contents($path);

    if (! str_contains($content, "route('courses.index')")) {
        $needle = <<<'BLADE'
                    <a href="{{ route('services.index') }}"
BLADE;

        $link = <<<'BLADE'
                    <a href="{{ route('courses.index') }}"
                       class="group flex items-center gap-2 text-sm font-medium text-blue-100/70 transition hover:text-white">
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-300 transition group-hover:w-3"></span>
                        Kelas Coding
                    </a>

BLADE;

        $position = strpos($content, $needle);

        if ($position !== false) {
            file_put_contents(
                $path,
                substr($content, 0, $position)
                . $link
                . substr($content, $position)
            );
        }
    }
}
PHP

find app/Models app/Http/Controllers app/Http/Middleware database/seeders \
    -type f -name "*.php" -print0 \
    | xargs -0 -n1 php -l >/dev/null

php -l routes/web.php >/dev/null

php artisan migrate --force
php artisan db:seed --class=MemberLearningSeeder --force
php artisan optimize:clear
php artisan storage:link >/dev/null 2>&1 || true

echo ""
echo "============================================================"
echo "FITUR MEMBER BERHASIL DIPASANG"
echo "============================================================"
echo "Katalog kelas : /kelas-coding"
echo "Member area   : /member/dashboard"
echo "Admin member  : /admin/members"
echo "Admin paket   : /admin/membership-plans"
echo "Admin kelas   : /admin/courses"
echo ""
echo "Akun demo:"
echo "Email    : member@hilmidev.test"
echo "Password : password"
echo ""
echo "Backup file lama: $BACKUP_DIR"
echo "============================================================"
