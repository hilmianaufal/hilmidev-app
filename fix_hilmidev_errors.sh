#!/usr/bin/env bash
set -euo pipefail

if [ ! -f artisan ]; then
    echo "ERROR: Jalankan script ini dari folder utama project Laravel."
    exit 1
fi

STAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP_DIR="storage/app/fix-hilmidev-$STAMP"

FILES=(
    "app/Models/Project.php"
    "routes/web.php"
    "app/Http/Controllers/PostController.php"
    "app/Http/Controllers/DashboardRedirectController.php"
    "app/Http/Controllers/CoursePreviewController.php"
    "app/Http/Controllers/Auth/RegisteredUserController.php"
    "app/Http/Controllers/PaymentProofController.php"
    "app/Http/Controllers/ProductDownloadController.php"
    "app/Http/Controllers/Admin/PaymentController.php"
    "app/Http/Controllers/Admin/CourseModuleController.php"
    "app/Http/Controllers/Admin/CourseLessonController.php"
    "app/Http/Controllers/Admin/MemberController.php"
    "resources/views/admin/payments/index.blade.php"
    "resources/views/admin/payments/show.blade.php"
    "resources/views/admin/dashboard.blade.php"
    "resources/views/courses/show.blade.php"
    "resources/views/courses/preview.blade.php"
)

mkdir -p "$BACKUP_DIR"

for file in "${FILES[@]}"; do
    if [ -f "$file" ]; then
        mkdir -p "$BACKUP_DIR/$(dirname "$file")"
        cp "$file" "$BACKUP_DIR/$file"
    fi
done

echo "Backup dibuat di: $BACKUP_DIR"

# ---------------------------------------------------------------------
# 1. Hapus model duplikat
# ---------------------------------------------------------------------
rm -f app/Models/Project.php

# ---------------------------------------------------------------------
# 2. Controller blog publik
# ---------------------------------------------------------------------
cat > app/Http/Controllers/PostController.php <<'PHP'
<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::query()
            ->with('author')
            ->where('is_published', true)
            ->where(function ($query) {
                $query
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->latest('published_at')
            ->latest('id')
            ->paginate(9);

        return view('blog.index', compact('posts'));
    }

    public function show(Post $post)
    {
        abort_if(
            ! $post->is_published
            || ($post->published_at && $post->published_at->isFuture()),
            404
        );

        $post->load('author');

        $relatedPosts = Post::query()
            ->with('author')
            ->where('id', '!=', $post->id)
            ->where('is_published', true)
            ->where(function ($query) {
                $query
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->latest('published_at')
            ->latest('id')
            ->limit(3)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
PHP

# ---------------------------------------------------------------------
# 3. Redirect dashboard berdasarkan akses user
# ---------------------------------------------------------------------
cat > app/Http/Controllers/DashboardRedirectController.php <<'PHP'
<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardRedirectController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasActiveMembership()) {
            return redirect()->route('member.dashboard');
        }

        return redirect()->route('client.dashboard');
    }
}
PHP

# ---------------------------------------------------------------------
# 4. Preview video publik untuk lesson yang diizinkan
# ---------------------------------------------------------------------
cat > app/Http/Controllers/CoursePreviewController.php <<'PHP'
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
PHP

mkdir -p resources/views/courses

cat > resources/views/courses/preview.blade.php <<'BLADE'
<x-app-layout>
    <div class="min-h-screen bg-white">
        <section class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
                <nav class="flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-400">
                    <a href="{{ route('courses.index') }}" class="hover:text-blue-700">
                        Kelas Coding
                    </a>

                    <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>

                    <a href="{{ route('courses.show', $course) }}" class="hover:text-blue-700">
                        {{ $course->title }}
                    </a>

                    <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>

                    <span class="text-slate-700">
                        Preview
                    </span>
                </nav>

                <div class="mt-6">
                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-2 text-xs font-black text-emerald-700">
                        <i data-lucide="play-circle" class="h-4 w-4"></i>
                        Preview Gratis
                    </span>

                    <h1 class="mt-4 text-3xl font-black text-slate-950">
                        {{ $lesson->title }}
                    </h1>

                    <p class="mt-3 text-sm font-medium leading-7 text-slate-500">
                        {{ $lesson->description ?: $course->subtitle }}
                    </p>
                </div>
            </div>
        </section>

        <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-slate-950 shadow-2xl">
                @if ($lesson->video_type === 'upload' && $lesson->video_path)
                    <video controls controlsList="nodownload" class="aspect-video w-full bg-black">
                        <source src="{{ route('courses.lessons.preview-video', $lesson) }}">
                        Browser tidak mendukung pemutar video.
                    </video>
                @elseif ($lesson->embedUrl())
                    <iframe
                        src="{{ $lesson->embedUrl() }}"
                        class="aspect-video w-full"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                @else
                    <div class="flex aspect-video items-center justify-center text-center text-white">
                        <div>
                            <i data-lucide="video-off" class="mx-auto h-12 w-12 text-slate-400"></i>
                            <p class="mt-4 font-bold">Video preview belum tersedia.</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-8 rounded-[1.75rem] border border-blue-200 bg-blue-50 p-6 sm:flex sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-black text-slate-950">
                        Akses seluruh video pembelajaran
                    </h2>

                    <p class="mt-2 text-sm font-medium leading-7 text-slate-600">
                        Login menggunakan akun member aktif untuk membuka seluruh modul,
                        video, progress belajar, dan file materi.
                    </p>
                </div>

                @auth
                    <a href="{{ auth()->user()->hasActiveMembership() ? route('member.dashboard') : route('courses.index') }}"
                       class="mt-5 inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-700 px-6 py-4 text-sm font-black text-white sm:mt-0">
                        Buka Member Area
                        <i data-lucide="arrow-right" class="h-5 w-5"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="mt-5 inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-700 px-6 py-4 text-sm font-black text-white sm:mt-0">
                        Login Member
                        <i data-lucide="log-in" class="h-5 w-5"></i>
                    </a>
                @endauth
            </div>
        </main>
    </div>
</x-app-layout>
BLADE

# ---------------------------------------------------------------------
# 5. Register selalu sebagai client
# ---------------------------------------------------------------------
cat > app/Http/Controllers/Auth/RegisteredUserController.php <<'PHP'
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
PHP

# ---------------------------------------------------------------------
# 6. Upload bukti pembayaran aman
# ---------------------------------------------------------------------
cat > app/Http/Controllers/PaymentProofController.php <<'PHP'
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentProofController extends Controller
{
    public function store(Request $request, Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);

        abort_unless(
            in_array($order->payment_status, ['unpaid', 'failed', 'review'], true),
            422,
            'Bukti pembayaran tidak dapat diubah pada status ini.'
        );

        $data = $request->validate([
            'payment_method' => ['required', 'string', 'max:100'],
            'payment_proof' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if (
            $order->payment_proof
            && Storage::disk('public')->exists($order->payment_proof)
        ) {
            Storage::disk('public')->delete($order->payment_proof);
        }

        $path = $request->file('payment_proof')
            ->store('payment-proofs', 'public');

        $order->update([
            'payment_method' => $data['payment_method'],
            'payment_proof' => $path,
            'payment_status' => 'review',
            'status' => 'processing',
        ]);

        return back()->with(
            'success',
            'Bukti pembayaran berhasil dikirim dan menunggu verifikasi admin.'
        );
    }
}
PHP

# ---------------------------------------------------------------------
# 7. Ekstensi download mengikuti file asli
# ---------------------------------------------------------------------
cat > app/Http/Controllers/ProductDownloadController.php <<'PHP'
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductDownloadController extends Controller
{
    public function download(Order $order, OrderItem $item)
    {
        abort_if($order->user_id !== auth()->id(), 403);
        abort_if($order->payment_status !== 'paid', 403);
        abort_if($item->order_id !== $order->id, 403);

        $product = $item->product;

        abort_if(! $product, 404);
        abort_if(! $product->file_path, 404);
        abort_if(! Storage::disk('local')->exists($product->file_path), 404);

        $extension = pathinfo($product->file_path, PATHINFO_EXTENSION) ?: 'zip';
        $filename = Str::slug($item->product_name ?: $product->name)
            . '.'
            . strtolower($extension);

        return Storage::disk('local')->download(
            $product->file_path,
            $filename
        );
    }
}
PHP

# ---------------------------------------------------------------------
# 8. Verifikasi pembayaran konsisten
# ---------------------------------------------------------------------
cat > app/Http/Controllers/Admin/PaymentController.php <<'PHP'
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $orders = Order::query()
            ->with('user')
            ->whereNotNull('payment_proof')
            ->latest()
            ->paginate(10);

        return view('admin.payments.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);

        return view('admin.payments.show', compact('order'));
    }

    public function approve(Order $order)
    {
        abort_unless(
            $order->payment_status === 'review',
            422,
            'Pembayaran ini tidak sedang menunggu verifikasi.'
        );

        $order->update([
            'payment_status' => 'paid',
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        return redirect()
            ->route('admin.payments.show', $order)
            ->with('success', 'Pembayaran berhasil disetujui.');
    }

    public function reject(Request $request, Order $order)
    {
        abort_unless(
            $order->payment_status === 'review',
            422,
            'Pembayaran ini tidak sedang menunggu verifikasi.'
        );

        $request->validate([
            'rejection_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $order->update([
            'payment_status' => 'failed',
            'status' => 'pending',
            'paid_at' => null,
        ]);

        return redirect()
            ->route('admin.payments.show', $order)
            ->with('success', 'Pembayaran berhasil ditolak.');
    }
}
PHP

# ---------------------------------------------------------------------
# 9. Hapus file video saat modul dihapus
# ---------------------------------------------------------------------
cat > app/Http/Controllers/Admin/CourseModuleController.php <<'PHP'
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
PHP

# ---------------------------------------------------------------------
# 10. Validasi video upload / URL
# ---------------------------------------------------------------------
cat > app/Http/Controllers/Admin/CourseLessonController.php <<'PHP'
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
PHP

# ---------------------------------------------------------------------
# 11. Manajemen member hanya menampilkan user yang punya membership
# ---------------------------------------------------------------------
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
            ->whereHas('memberships')
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
        $plans = MembershipPlan::active()
            ->orderBy('price')
            ->get();

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

        $membership = $user->memberships()
            ->with('plan')
            ->latest('id')
            ->first();

        $plans = MembershipPlan::query()
            ->where(function ($query) use ($membership) {
                $query->where('is_active', true);

                if ($membership?->membership_plan_id) {
                    $query->orWhereKey($membership->membership_plan_id);
                }
            })
            ->orderBy('price')
            ->get();

        return view(
            'admin.members.edit',
            compact('user', 'plans', 'membership')
        );
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
            : (
                $plan->duration_days
                    ? $startsAt->copy()->addDays($plan->duration_days)
                    : null
            );

        $membership = $user->memberships()
            ->latest('id')
            ->first();

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

# ---------------------------------------------------------------------
# 12. Route bersih dan konsisten
# ---------------------------------------------------------------------
cat > routes/web.php <<'PHP'
<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\PortfolioController as AdminPortfolioController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProjectRequestController as AdminProjectRequestController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\CourseCatalogController;
use App\Http\Controllers\CoursePreviewController;
use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoicePdfController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentProofController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDownloadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectRequestController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/source-code', [ProductController::class, 'index'])
    ->name('products.index');

Route::get('/source-code/{product:slug}', [ProductController::class, 'show'])
    ->name('products.show');

Route::get('/services', [ServiceController::class, 'index'])
    ->name('services.index');

Route::get('/services/{service:slug}', [ServiceController::class, 'show'])
    ->name('services.show');

Route::get('/blog', [PostController::class, 'index'])
    ->name('blog.index');

Route::get('/blog/{post:slug}', [PostController::class, 'show'])
    ->name('blog.show');

Route::get('/kelas-coding', [CourseCatalogController::class, 'index'])
    ->name('courses.index');

Route::get('/kelas-coding/preview/{lesson}', [CoursePreviewController::class, 'show'])
    ->name('courses.lessons.preview');

Route::get('/kelas-coding/preview/{lesson}/video', [CoursePreviewController::class, 'video'])
    ->name('courses.lessons.preview-video');

Route::get('/kelas-coding/{course}', [CourseCatalogController::class, 'show'])
    ->name('courses.show');

Route::get('/dashboard', DashboardRedirectController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/checkout/{product:slug}', [CheckoutController::class, 'store'])
        ->name('checkout.store');

    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders.index');

    Route::get('/orders/{order}', [OrderController::class, 'show'])
        ->name('orders.show');

    Route::post('/orders/{order}/payment-proof', [PaymentProofController::class, 'store'])
        ->name('orders.payment-proof.store');

    Route::get('/orders/{order}/items/{item}/download', [ProductDownloadController::class, 'download'])
        ->name('orders.items.download');

    Route::get('/orders/{order}/invoice-pdf', [InvoicePdfController::class, 'download'])
        ->name('orders.invoice-pdf');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get('/project-requests', [ProjectRequestController::class, 'index'])
        ->name('project-requests.index');

    Route::get('/services/{service:slug}/request', [ProjectRequestController::class, 'create'])
        ->name('project-requests.create');

    Route::post('/services/{service:slug}/request', [ProjectRequestController::class, 'store'])
        ->name('project-requests.store');

    Route::get('/project-requests/{projectRequest}', [ProjectRequestController::class, 'show'])
        ->name('project-requests.show');

    Route::get('/client/dashboard', [ClientDashboardController::class, 'index'])
        ->name('client.dashboard');
});

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
        Route::get('/', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('categories', AdminCategoryController::class)
            ->except('show');

        Route::resource('products', AdminProductController::class);

        Route::resource('services', AdminServiceController::class)
            ->except('show');

        Route::resource('portfolios', AdminPortfolioController::class)
            ->except('show');

        Route::resource('testimonials', AdminTestimonialController::class)
            ->except('show');

        Route::resource('posts', AdminPostController::class)
            ->except('show');

        Route::get('orders', [AdminOrderController::class, 'index'])
            ->name('orders.index');

        Route::get('orders/{order}', [AdminOrderController::class, 'show'])
            ->name('orders.show');

        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])
            ->name('orders.update-status');

        Route::get('project-requests', [AdminProjectRequestController::class, 'index'])
            ->name('project-requests.index');

        Route::get('project-requests/{projectRequest}', [AdminProjectRequestController::class, 'show'])
            ->name('project-requests.show');

        Route::patch(
            'project-requests/{projectRequest}/status',
            [AdminProjectRequestController::class, 'updateStatus']
        )->name('project-requests.update-status');

        Route::get('payments', [AdminPaymentController::class, 'index'])
            ->name('payments.index');

        Route::get('payments/{order}', [AdminPaymentController::class, 'show'])
            ->name('payments.show');

        Route::patch('payments/{order}/approve', [AdminPaymentController::class, 'approve'])
            ->name('payments.approve');

        Route::patch('payments/{order}/reject', [AdminPaymentController::class, 'reject'])
            ->name('payments.reject');

        Route::get('clients', [AdminClientController::class, 'index'])
            ->name('clients.index');

        Route::get('clients/{user}', [AdminClientController::class, 'show'])
            ->name('clients.show');

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

require __DIR__ . '/auth.php';
PHP

# ---------------------------------------------------------------------
# 13. Rapikan tampilan pembayaran dan null relationship
# ---------------------------------------------------------------------
php <<'PHP'
<?php

$files = [
    'resources/views/admin/payments/index.blade.php',
    'resources/views/admin/payments/show.blade.php',
    'resources/views/admin/orders/index.blade.php',
    'resources/views/admin/orders/show.blade.php',
];

foreach ($files as $path) {
    if (! file_exists($path)) {
        continue;
    }

    $content = file_get_contents($path);

    $content = str_replace(
        "\$order->payment_status === 'rejected'",
        "\$order->payment_status === 'failed'",
        $content
    );

    $content = str_replace(
        '$order->user->',
        '$order->user?->',
        $content
    );

    $content = str_replace(
        '{{ $item->product->name }}',
        '{{ $item->product_name }}',
        $content
    );

    $content = str_replace(
        '{{ $item->product->technology ??',
        '{{ $item->product?->technology ??',
        $content
    );

    $content = str_replace(
        'Qty {{ $item->quantity }}',
        'Qty 1',
        $content
    );

    file_put_contents($path, $content);
}
PHP

# ---------------------------------------------------------------------
# 14. Jadikan label preview dapat dibuka
# ---------------------------------------------------------------------
php <<'PHP'
<?php

$path = 'resources/views/courses/show.blade.php';

if (file_exists($path)) {
    $content = file_get_contents($path);

    $old = '<span class="rounded-full bg-emerald-50 px-3 py-1.5 text-[10px] font-black text-emerald-700">PREVIEW</span>';

    $new = '<a href="{{ route(\'courses.lessons.preview\', $lesson) }}" class="rounded-full bg-emerald-50 px-3 py-1.5 text-[10px] font-black text-emerald-700 transition hover:bg-emerald-100">PREVIEW</a>';

    $content = str_replace($old, $new, $content);

    file_put_contents($path, $content);
}
PHP

# ---------------------------------------------------------------------
# 15. Dashboard admin bersih tanpa wrapper bertumpuk
# ---------------------------------------------------------------------
cat > resources/views/admin/dashboard.blade.php <<'BLADE'
<x-layouts.admin>
    @php
        $statistics = [
            [
                'label' => 'Total Produk',
                'value' => number_format($totalProducts),
                'icon' => 'package',
                'class' => 'bg-blue-600',
            ],
            [
                'label' => 'Produk Aktif',
                'value' => number_format($activeProducts),
                'icon' => 'badge-check',
                'class' => 'bg-cyan-500',
            ],
            [
                'label' => 'Kategori',
                'value' => number_format($totalCategories),
                'icon' => 'grid-3x3',
                'class' => 'bg-indigo-500',
            ],
            [
                'label' => 'Client',
                'value' => number_format($totalClients),
                'icon' => 'users',
                'class' => 'bg-sky-500',
            ],
            [
                'label' => 'Total Order',
                'value' => number_format($totalOrders),
                'icon' => 'shopping-cart',
                'class' => 'bg-blue-700',
            ],
            [
                'label' => 'Order Paid',
                'value' => number_format($paidOrders),
                'icon' => 'circle-check',
                'class' => 'bg-emerald-500',
            ],
            [
                'label' => 'Belum Bayar',
                'value' => number_format($pendingOrders),
                'icon' => 'clock',
                'class' => 'bg-amber-500',
            ],
            [
                'label' => 'Revenue',
                'value' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),
                'icon' => 'wallet',
                'class' => 'bg-violet-500',
            ],
            [
                'label' => 'Total Jasa',
                'value' => number_format($totalServices),
                'icon' => 'briefcase-business',
                'class' => 'bg-blue-600',
            ],
            [
                'label' => 'Project Request',
                'value' => number_format($totalProjectRequests),
                'icon' => 'folder-kanban',
                'class' => 'bg-cyan-500',
            ],
            [
                'label' => 'Project Pending',
                'value' => number_format($pendingProjectRequests),
                'icon' => 'hourglass',
                'class' => 'bg-amber-500',
            ],
            [
                'label' => 'Development',
                'value' => number_format($developmentProjectRequests),
                'icon' => 'rocket',
                'class' => 'bg-indigo-500',
            ],
        ];
    @endphp

    <div class="mx-auto max-w-7xl px-4 py-10">
        <section class="relative mb-8 overflow-hidden rounded-[2rem] bg-gradient-to-br from-blue-700 via-blue-600 to-cyan-500 p-8 text-white shadow-2xl shadow-blue-500/20 md:p-10">
            <div class="absolute -right-20 -top-20 h-72 w-72 rounded-full bg-white/15 blur-3xl"></div>

            <div class="relative flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/15 px-4 py-2">
                        <i data-lucide="layout-dashboard" class="h-4 w-4"></i>
                        <span class="text-sm font-bold">Admin Control Center</span>
                    </div>

                    <h1 class="text-3xl font-black md:text-5xl">
                        Dashboard Admin
                    </h1>

                    <p class="mt-3 text-blue-50">
                        Ringkasan marketplace, layanan, pembayaran, dan project HilmiDev.
                    </p>
                </div>

                <a href="{{ route('admin.products.create') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-6 py-4 font-black text-blue-700 shadow-xl">
                    <i data-lucide="plus-circle" class="h-5 w-5"></i>
                    Tambah Produk
                </a>
            </div>
        </section>

        <section class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($statistics as $statistic)
                <article class="rounded-[1.75rem] border border-blue-100 bg-white p-6 shadow-xl shadow-blue-500/5">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl text-white {{ $statistic['class'] }}">
                        <i data-lucide="{{ $statistic['icon'] }}" class="h-6 w-6"></i>
                    </div>

                    <p class="mt-5 text-sm font-semibold text-slate-500">
                        {{ $statistic['label'] }}
                    </p>

                    <h2 class="mt-2 break-words text-3xl font-black text-slate-950">
                        {{ $statistic['value'] }}
                    </h2>
                </article>
            @endforeach
        </section>

        <div class="mt-8 grid gap-8 xl:grid-cols-2">
            <section class="overflow-hidden rounded-[2rem] border border-blue-100 bg-white shadow-xl shadow-blue-500/5">
                <header class="flex items-center justify-between border-b border-blue-50 p-6">
                    <h2 class="font-black text-slate-950">Order Terbaru</h2>

                    <a href="{{ route('admin.orders.index') }}"
                       class="text-sm font-black text-blue-700">
                        Lihat Semua
                    </a>
                </header>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-blue-50 text-left text-blue-700">
                            <tr>
                                <th class="px-5 py-4">Invoice</th>
                                <th class="px-5 py-4">Client</th>
                                <th class="px-5 py-4">Total</th>
                                <th class="px-5 py-4">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-blue-50">
                            @forelse ($latestOrders as $order)
                                <tr>
                                    <td class="px-5 py-4 font-bold text-slate-900">
                                        {{ $order->invoice_number }}
                                    </td>

                                    <td class="px-5 py-4">
                                        {{ $order->user?->name ?? 'User dihapus' }}
                                    </td>

                                    <td class="px-5 py-4 font-black text-blue-700">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                            {{ strtoupper($order->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-12 text-center text-slate-500">
                                        Belum ada order.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="overflow-hidden rounded-[2rem] border border-blue-100 bg-white shadow-xl shadow-blue-500/5">
                <header class="flex items-center justify-between border-b border-blue-50 p-6">
                    <h2 class="font-black text-slate-950">Produk Terbaru</h2>

                    <a href="{{ route('admin.products.index') }}"
                       class="text-sm font-black text-blue-700">
                        Lihat Semua
                    </a>
                </header>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-blue-50 text-left text-blue-700">
                            <tr>
                                <th class="px-5 py-4">Produk</th>
                                <th class="px-5 py-4">Kategori</th>
                                <th class="px-5 py-4">Harga</th>
                                <th class="px-5 py-4">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-blue-50">
                            @forelse ($latestProducts as $product)
                                <tr>
                                    <td class="px-5 py-4 font-bold text-slate-900">
                                        {{ $product->name }}
                                    </td>

                                    <td class="px-5 py-4">
                                        {{ $product->category?->name ?? '-' }}
                                    </td>

                                    <td class="px-5 py-4 font-black text-blue-700">
                                        Rp {{ number_format($product->final_price, 0, ',', '.') }}
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $product->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                            {{ $product->is_active ? 'AKTIF' : 'NONAKTIF' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-12 text-center text-slate-500">
                                        Belum ada produk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <section class="mt-8 overflow-hidden rounded-[2rem] border border-blue-100 bg-white shadow-xl shadow-blue-500/5">
            <header class="flex items-center justify-between border-b border-blue-50 p-6">
                <h2 class="font-black text-slate-950">Project Request Terbaru</h2>

                <a href="{{ route('admin.project-requests.index') }}"
                   class="text-sm font-black text-blue-700">
                    Lihat Semua
                </a>
            </header>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-blue-50 text-left text-blue-700">
                        <tr>
                            <th class="px-5 py-4">Project</th>
                            <th class="px-5 py-4">Client</th>
                            <th class="px-5 py-4">Layanan</th>
                            <th class="px-5 py-4">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-blue-50">
                        @forelse ($latestProjectRequests as $project)
                            <tr>
                                <td class="px-5 py-4 font-bold text-slate-900">
                                    {{ $project->project_name }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ $project->user?->name ?? 'User dihapus' }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ $project->service?->title ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                                        {{ strtoupper($project->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-12 text-center text-slate-500">
                                    Belum ada project request.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-layouts.admin>
BLADE

# ---------------------------------------------------------------------
# 16. Validasi akhir
# ---------------------------------------------------------------------
composer dump-autoload

find app/Http/Controllers app/Models app/Http/Middleware \
    -type f -name "*.php" -print0 \
    | xargs -0 -n1 php -l

php artisan optimize:clear
php artisan route:list > storage/logs/route-list-after-fix.txt
php artisan view:cache
php artisan view:clear
php artisan optimize:clear

echo ""
echo "============================================================"
echo "PERBAIKAN SELESAI"
echo "Backup: $BACKUP_DIR"
echo "Route: storage/logs/route-list-after-fix.txt"
echo "============================================================"
