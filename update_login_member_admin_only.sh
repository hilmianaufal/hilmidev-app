#!/usr/bin/env bash

set -euo pipefail

if [ ! -f artisan ]; then
    echo "ERROR: Jalankan script dari folder utama project Laravel."
    exit 1
fi

STAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP_DIR="storage/app/login-member-backup-$STAMP"

mkdir -p "$BACKUP_DIR"

FILES=(
    "routes/auth.php"
    "resources/views/auth/login.blade.php"
    "resources/views/auth/register.blade.php"
    "resources/views/home.blade.php"
    "resources/views/layouts/navigation.blade.php"
    "resources/views/layouts/footer.blade.php"
    "resources/views/courses/index.blade.php"
    "resources/views/courses/show.blade.php"
    "resources/views/courses/preview.blade.php"
    "resources/views/services/index.blade.php"
    "resources/views/admin/clients/index.blade.php"
)

for file in "${FILES[@]}"; do
    if [ -f "$file" ]; then
        mkdir -p "$BACKUP_DIR/$(dirname "$file")"
        cp "$file" "$BACKUP_DIR/$file"
    fi
done

echo "Backup dibuat di: $BACKUP_DIR"

# ============================================================
# 1. NONAKTIFKAN REGISTRASI PUBLIK
# ============================================================

cat > routes/auth.php <<'PHP'
<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Registrasi publik dinonaktifkan
    |--------------------------------------------------------------------------
    |
    | Route ini hanya menjadi redirect agar link lama menuju /register
    | tidak menghasilkan error. Pembuatan akun member dilakukan admin
    | melalui menu Admin > Manajemen Member.
    |
    */
    Route::get('register', function () {
        return redirect()
            ->route('login')
            ->with(
                'info',
                'Pendaftaran akun member dilakukan langsung oleh admin HilmiDev.'
            );
    })->name('register');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get(
        'forgot-password',
        [PasswordResetLinkController::class, 'create']
    )->name('password.request');

    Route::post(
        'forgot-password',
        [PasswordResetLinkController::class, 'store']
    )->name('password.email');

    Route::get(
        'reset-password/{token}',
        [NewPasswordController::class, 'create']
    )->name('password.reset');

    Route::post(
        'reset-password',
        [NewPasswordController::class, 'store']
    )->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get(
        'verify-email',
        EmailVerificationPromptController::class
    )->name('verification.notice');

    Route::get(
        'verify-email/{id}/{hash}',
        VerifyEmailController::class
    )
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post(
        'email/verification-notification',
        [EmailVerificationNotificationController::class, 'store']
    )
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get(
        'confirm-password',
        [ConfirmablePasswordController::class, 'show']
    )->name('password.confirm');

    Route::post(
        'confirm-password',
        [ConfirmablePasswordController::class, 'store']
    );

    Route::put(
        'password',
        [PasswordController::class, 'update']
    )->name('password.update');

    Route::post(
        'logout',
        [AuthenticatedSessionController::class, 'destroy']
    )->name('logout');
});
PHP

# ============================================================
# 2. HAPUS VIEW REGISTER PUBLIK
# ============================================================

rm -f resources/views/auth/register.blade.php

# ============================================================
# 3. TAMPILAN LOGIN BARU
# ============================================================

cat > resources/views/auth/login.blade.php <<'BLADE'
<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="h-full"
>
<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>Login | HilmiDev</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet"
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body
    x-data="{
        showPassword: false,
        loading: false
    }"
    class="min-h-full bg-slate-950 text-slate-900 antialiased"
>
    <main class="relative min-h-screen overflow-hidden">
        {{-- BACKGROUND --}}
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-950 via-blue-900 to-blue-700"></div>

            <div class="absolute -left-40 -top-40 h-[34rem] w-[34rem] rounded-full bg-cyan-400/15 blur-3xl"></div>

            <div class="absolute -bottom-48 right-0 h-[38rem] w-[38rem] rounded-full bg-blue-300/15 blur-3xl"></div>

            <div
                class="absolute inset-0 opacity-[0.04]"
                style="
                    background-image:
                        linear-gradient(rgba(255,255,255,.8) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(255,255,255,.8) 1px, transparent 1px);
                    background-size: 52px 52px;
                "
            ></div>
        </div>

        <div class="relative mx-auto grid min-h-screen max-w-[1500px] lg:grid-cols-12">

            {{-- INFORMASI KIRI --}}
            <section class="hidden px-10 py-12 text-white lg:col-span-7 lg:flex lg:flex-col lg:justify-between xl:px-16">
                <a
                    href="{{ route('home') }}"
                    class="inline-flex w-fit items-center gap-4"
                >
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-white/15 bg-white/10 shadow-xl backdrop-blur-xl">
                        <i
                            data-lucide="code-2"
                            class="h-7 w-7"
                        ></i>
                    </div>

                    <div>
                        <h1 class="text-2xl font-black tracking-tight">
                            Hilmi<span class="text-cyan-300">Dev</span>
                        </h1>

                        <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.22em] text-blue-200">
                            Digital Learning Platform
                        </p>
                    </div>
                </a>

                <div class="max-w-3xl py-16">
                    <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-black text-blue-100 backdrop-blur-xl">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-cyan-300 opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-cyan-300"></span>
                        </span>

                        Platform Resmi Member HilmiDev
                    </div>

                    <h2 class="mt-8 text-5xl font-black leading-[1.15] tracking-tight xl:text-6xl">
                        Belajar membangun aplikasi
                        <span class="text-cyan-300">
                            dari project nyata.
                        </span>
                    </h2>

                    <p class="mt-6 max-w-2xl text-base font-medium leading-8 text-blue-100/75">
                        Masuk untuk mengakses kelas coding, video pembelajaran,
                        source code latihan, progress belajar, pembelian produk,
                        dan layanan project HilmiDev.
                    </p>

                    <div class="mt-10 grid max-w-2xl gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border border-white/10 bg-white/[0.07] p-5 backdrop-blur-xl">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 text-cyan-300">
                                <i
                                    data-lucide="circle-play"
                                    class="h-5 w-5"
                                ></i>
                            </div>

                            <h3 class="mt-5 text-sm font-black">
                                Video Pembelajaran
                            </h3>

                            <p class="mt-2 text-xs font-medium leading-6 text-blue-100/65">
                                Belajar Laravel, Tailwind, database, dan deployment.
                            </p>
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-white/[0.07] p-5 backdrop-blur-xl">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 text-cyan-300">
                                <i
                                    data-lucide="folder-code"
                                    class="h-5 w-5"
                                ></i>
                            </div>

                            <h3 class="mt-5 text-sm font-black">
                                Source Code Materi
                            </h3>

                            <p class="mt-2 text-xs font-medium leading-6 text-blue-100/65">
                                Download file latihan dan project setiap kelas.
                            </p>
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-white/[0.07] p-5 backdrop-blur-xl">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 text-cyan-300">
                                <i
                                    data-lucide="chart-no-axes-column-increasing"
                                    class="h-5 w-5"
                                ></i>
                            </div>

                            <h3 class="mt-5 text-sm font-black">
                                Progress Otomatis
                            </h3>

                            <p class="mt-2 text-xs font-medium leading-6 text-blue-100/65">
                                Pantau video dan materi yang telah diselesaikan.
                            </p>
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-white/[0.07] p-5 backdrop-blur-xl">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 text-cyan-300">
                                <i
                                    data-lucide="shield-check"
                                    class="h-5 w-5"
                                ></i>
                            </div>

                            <h3 class="mt-5 text-sm font-black">
                                Akses Khusus Member
                            </h3>

                            <p class="mt-2 text-xs font-medium leading-6 text-blue-100/65">
                                Akun dan masa aktif dikelola langsung oleh admin.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between border-t border-white/10 pt-7 text-xs font-medium text-blue-200/70">
                    <span>
                        © {{ date('Y') }} HilmiDev
                    </span>

                    <span class="inline-flex items-center gap-2">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-400"></span>
                        </span>

                        Sistem aktif
                    </span>
                </div>
            </section>

            {{-- FORM LOGIN --}}
            <section class="flex min-h-screen items-center justify-center px-4 py-8 sm:px-6 lg:col-span-5 lg:bg-white/[0.04] lg:px-10 lg:backdrop-blur-sm xl:px-14">
                <div class="w-full max-w-md">
                    {{-- LOGO MOBILE --}}
                    <a
                        href="{{ route('home') }}"
                        class="mb-8 flex items-center justify-center gap-3 text-white lg:hidden"
                    >
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/15 bg-white/10">
                            <i
                                data-lucide="code-2"
                                class="h-6 w-6"
                            ></i>
                        </div>

                        <div>
                            <p class="text-xl font-black">
                                Hilmi<span class="text-cyan-300">Dev</span>
                            </p>

                            <p class="text-[9px] font-bold uppercase tracking-wider text-blue-200">
                                Member Platform
                            </p>
                        </div>
                    </a>

                    <div
                        x-data="{ visible: false }"
                        x-init="setTimeout(() => visible = true, 100)"
                        x-show="visible"
                        x-transition:enter="transition duration-500 ease-out"
                        x-transition:enter-start="translate-y-5 opacity-0"
                        x-transition:enter-end="translate-y-0 opacity-100"
                        class="rounded-[2rem] border border-white/30 bg-white p-6 shadow-2xl shadow-blue-950/30 sm:p-8"
                    >
                        <div>
                            <div class="flex h-13 w-13 items-center justify-center rounded-2xl bg-blue-700 text-white shadow-lg shadow-blue-700/20">
                                <i
                                    data-lucide="log-in"
                                    class="h-6 w-6"
                                ></i>
                            </div>

                            <p class="mt-6 text-[10px] font-black uppercase tracking-[0.18em] text-blue-700">
                                Secure Member Login
                            </p>

                            <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-950">
                                Selamat datang kembali
                            </h1>

                            <p class="mt-3 text-sm font-medium leading-7 text-slate-500">
                                Masukkan email dan password akun yang telah diberikan admin.
                            </p>
                        </div>

                        @if (session('info'))
                            <div class="mt-6 flex items-start gap-3 rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3.5 text-sm font-semibold text-blue-700">
                                <i
                                    data-lucide="info"
                                    class="mt-0.5 h-5 w-5 shrink-0"
                                ></i>

                                <span>
                                    {{ session('info') }}
                                </span>
                            </div>
                        @endif

                        @if (session('status'))
                            <div class="mt-6 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm font-semibold text-emerald-700">
                                <i
                                    data-lucide="circle-check"
                                    class="mt-0.5 h-5 w-5 shrink-0"
                                ></i>

                                <span>
                                    {{ session('status') }}
                                </span>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mt-6 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm font-semibold text-red-700">
                                <i
                                    data-lucide="circle-alert"
                                    class="mt-0.5 h-5 w-5 shrink-0"
                                ></i>

                                <div>
                                    <p class="font-black">
                                        Login belum berhasil
                                    </p>

                                    <p class="mt-1 text-xs font-medium leading-5">
                                        Periksa kembali email dan password akun.
                                    </p>
                                </div>
                            </div>
                        @endif

                        <form
                            method="POST"
                            action="{{ route('login') }}"
                            class="mt-7 space-y-5"
                            @submit="loading = true"
                        >
                            @csrf

                            <div>
                                <label
                                    for="email"
                                    class="mb-2 block text-xs font-black text-slate-700"
                                >
                                    Alamat Email
                                </label>

                                <div class="relative">
                                    <i
                                        data-lucide="mail"
                                        class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                    ></i>

                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autofocus
                                        autocomplete="username"
                                        placeholder="nama@email.com"
                                        class="h-14 w-full rounded-2xl border-slate-200 bg-slate-50 pl-12 pr-4 text-sm font-semibold text-slate-900 transition placeholder:font-medium placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"
                                    >
                                </div>

                                @error('email')
                                    <p class="mt-2 text-xs font-semibold text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <div class="mb-2 flex items-center justify-between gap-3">
                                    <label
                                        for="password"
                                        class="block text-xs font-black text-slate-700"
                                    >
                                        Password
                                    </label>

                                    @if (Route::has('password.request'))
                                        <a
                                            href="{{ route('password.request') }}"
                                            class="text-xs font-black text-blue-700 transition hover:text-blue-900"
                                        >
                                            Lupa password?
                                        </a>
                                    @endif
                                </div>

                                <div class="relative">
                                    <i
                                        data-lucide="lock-keyhole"
                                        class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                    ></i>

                                    <input
                                        id="password"
                                        name="password"
                                        :type="showPassword ? 'text' : 'password'"
                                        required
                                        autocomplete="current-password"
                                        placeholder="Masukkan password"
                                        class="h-14 w-full rounded-2xl border-slate-200 bg-slate-50 pl-12 pr-12 text-sm font-semibold text-slate-900 transition placeholder:font-medium placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"
                                    >

                                    <button
                                        type="button"
                                        @click="showPassword = !showPassword"
                                        class="absolute right-3 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-xl text-slate-400 transition hover:bg-blue-50 hover:text-blue-700"
                                        :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'"
                                    >
                                        <i
                                            x-show="!showPassword"
                                            data-lucide="eye"
                                            class="h-4 w-4"
                                        ></i>

                                        <i
                                            x-show="showPassword"
                                            x-cloak
                                            data-lucide="eye-off"
                                            class="h-4 w-4"
                                        ></i>
                                    </button>
                                </div>

                                @error('password')
                                    <p class="mt-2 text-xs font-semibold text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <label class="inline-flex cursor-pointer items-center gap-3">
                                <input
                                    id="remember"
                                    type="checkbox"
                                    name="remember"
                                    class="rounded border-slate-300 text-blue-700 shadow-sm focus:ring-blue-500"
                                >

                                <span class="text-xs font-semibold text-slate-500">
                                    Ingat akun saya
                                </span>
                            </label>

                            <button
                                type="submit"
                                :disabled="loading"
                                class="inline-flex h-14 w-full items-center justify-center gap-2 rounded-2xl bg-blue-700 px-6 text-sm font-black text-white shadow-xl shadow-blue-700/20 transition duration-300 hover:-translate-y-0.5 hover:bg-blue-800 disabled:cursor-wait disabled:opacity-70"
                            >
                                <i
                                    x-show="!loading"
                                    data-lucide="log-in"
                                    class="h-5 w-5"
                                ></i>

                                <svg
                                    x-show="loading"
                                    x-cloak
                                    class="h-5 w-5 animate-spin"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                >
                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    ></circle>

                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                                    ></path>
                                </svg>

                                <span x-text="loading ? 'Memeriksa akun...' : 'Masuk ke Akun'">
                                    Masuk ke Akun
                                </span>
                            </button>
                        </form>

                        <div class="mt-7 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-start gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                    <i
                                        data-lucide="shield-check"
                                        class="h-4 w-4"
                                    ></i>
                                </div>

                                <div>
                                    <p class="text-xs font-black text-slate-800">
                                        Belum memiliki akun?
                                    </p>

                                    <p class="mt-1 text-[11px] font-medium leading-5 text-slate-500">
                                        Pendaftaran member dilakukan melalui admin.
                                        Hubungi admin HilmiDev untuk mendapatkan akun.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <a
                            href="{{ route('home') }}"
                            class="mt-6 inline-flex w-full items-center justify-center gap-2 text-xs font-black text-slate-500 transition hover:text-blue-700"
                        >
                            <i
                                data-lucide="arrow-left"
                                class="h-4 w-4"
                            ></i>

                            Kembali ke halaman utama
                        </a>
                    </div>

                    <p class="mt-6 text-center text-[11px] font-medium text-blue-100/60 lg:hidden">
                        © {{ date('Y') }} HilmiDev. Seluruh hak cipta dilindungi.
                    </p>
                </div>
            </section>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
BLADE

# ============================================================
# 4. UBAH LINK REGISTRASI PADA HALAMAN PUBLIK
# ============================================================

php <<'PHP'
<?php

/*
|--------------------------------------------------------------------------
| Navbar
|--------------------------------------------------------------------------
| Tombol daftar diubah menjadi informasi program member.
*/
$navigationPath = 'resources/views/layouts/navigation.blade.php';

if (file_exists($navigationPath)) {
    $content = file_get_contents($navigationPath);

    $content = str_replace(
        "route('register')",
        "route('courses.index')",
        $content
    );

    $content = preg_replace(
        '/>\s*Daftar\s*</',
        ">Info Member<",
        $content
    );

    $content = preg_replace(
        '/>\s*Register\s*</',
        ">Info Member<",
        $content
    );

    file_put_contents($navigationPath, $content);
}

/*
|--------------------------------------------------------------------------
| Footer
|--------------------------------------------------------------------------
*/
$footerPath = 'resources/views/layouts/footer.blade.php';

if (file_exists($footerPath)) {
    $content = file_get_contents($footerPath);

    $content = str_replace(
        "route('register')",
        "route('courses.index')",
        $content
    );

    $content = str_replace(
        'Buat Akun',
        'Info Member',
        $content
    );

    file_put_contents($footerPath, $content);
}

/*
|--------------------------------------------------------------------------
| Halaman publik lainnya
|--------------------------------------------------------------------------
*/
$publicFiles = [
    'resources/views/home.blade.php',
    'resources/views/courses/index.blade.php',
    'resources/views/courses/show.blade.php',
    'resources/views/courses/preview.blade.php',
    'resources/views/services/index.blade.php',
];

$replacements = [
    "route('register')" => "route('login')",
    'Daftar Menjadi Member' => 'Login Member',
    'Daftar Sekarang' => 'Login Member',
    'Daftar dan Mulai' => 'Login Member',
    'Daftar Akun' => 'Login Member',
    'Buat Akun Member' => 'Login Member',
    'atau daftar menjadi member bimbingan coding HilmiDev.'
        => 'atau masuk ke akun member bimbingan coding HilmiDev.',
    'Setelah mendaftar, akses membership dapat diaktifkan oleh admin HilmiDev.'
        => 'Akun member dibuat dan diaktifkan langsung oleh admin HilmiDev.',
];

foreach ($publicFiles as $path) {
    if (! file_exists($path)) {
        continue;
    }

    $content = file_get_contents($path);
    $content = str_replace(
        array_keys($replacements),
        array_values($replacements),
        $content
    );

    file_put_contents($path, $content);
}

/*
|--------------------------------------------------------------------------
| Keterangan halaman client admin
|--------------------------------------------------------------------------
*/
$clientPath = 'resources/views/admin/clients/index.blade.php';

if (file_exists($clientPath)) {
    $content = file_get_contents($clientPath);

    $content = str_replace(
        'Kelola daftar client yang sudah register di HilmiDev.',
        'Kelola akun client dan member yang dibuat melalui admin HilmiDev.',
        $content
    );

    $content = str_replace(
        'User yang register sebagai client akan tampil di sini.',
        'Akun client dan member yang dibuat admin akan tampil di sini.',
        $content
    );

    file_put_contents($clientPath, $content);
}

/*
|--------------------------------------------------------------------------
| Pengamanan referensi register yang masih tertinggal
|--------------------------------------------------------------------------
*/
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(
        'resources/views',
        FilesystemIterator::SKIP_DOTS
    )
);

foreach ($iterator as $file) {
    if (
        ! $file->isFile()
        || ! str_ends_with($file->getFilename(), '.blade.php')
    ) {
        continue;
    }

    $path = $file->getPathname();
    $content = file_get_contents($path);

    if (str_contains($content, "route('register')")) {
        $content = str_replace(
            "route('register')",
            "route('login')",
            $content
        );

        file_put_contents($path, $content);
    }
}

echo "Referensi registrasi publik berhasil diperbarui.\n";
PHP

# ============================================================
# 5. VALIDASI
# ============================================================

php -l routes/auth.php

php artisan optimize:clear

php artisan route:list --path=register

php artisan view:cache

php artisan view:clear

echo ""
echo "============================================================"
echo "LOGIN DAN REGISTRASI MEMBER BERHASIL DIPERBARUI"
echo "============================================================"
echo "Registrasi publik : NONAKTIF"
echo "Pembuatan member  : /admin/members/create"
echo "Halaman login     : /login"
echo "Backup             : $BACKUP_DIR"
echo "============================================================"
