#!/usr/bin/env bash

set -euo pipefail

if [ ! -f artisan ]; then
    echo "ERROR: Jalankan script dari folder utama project Laravel."
    exit 1
fi

STAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP_DIR="storage/app/member-dashboard-backup-$STAMP"

mkdir -p "$BACKUP_DIR"

FILES=(
    "resources/views/components/layouts/member.blade.php"
    "resources/views/member/dashboard.blade.php"
    "resources/views/member/courses/show.blade.php"
    "resources/views/member/lessons/show.blade.php"
)

for file in "${FILES[@]}"; do
    if [ -f "$file" ]; then
        mkdir -p "$BACKUP_DIR/$(dirname "$file")"
        cp "$file" "$BACKUP_DIR/$file"
    fi
done

mkdir -p resources/views/components/layouts
mkdir -p resources/views/member

echo "Backup dibuat di: $BACKUP_DIR"

# ============================================================
# LAYOUT KHUSUS MEMBER
# ============================================================

cat > resources/views/components/layouts/member.blade.php <<'BLADE'
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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

    <title>Member Area | HilmiDev</title>

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

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        * {
            scrollbar-width: thin;
            scrollbar-color: #64748b transparent;
        }

        *::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        *::-webkit-scrollbar-track {
            background: transparent;
        }

        *::-webkit-scrollbar-thumb {
            background: #64748b;
            border-radius: 999px;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased">
    @php
        $activeMembership = auth()->user()
            ->memberships()
            ->with('plan')
            ->active()
            ->latest('id')
            ->first();

        $pageTitle = match (true) {
            request()->routeIs('member.dashboard') => 'Dashboard Belajar',
            request()->routeIs('member.courses.*') => 'Kelas Saya',
            request()->routeIs('member.lessons.*') => 'Video Pembelajaran',
            request()->routeIs('orders.*') => 'Pesanan Saya',
            request()->routeIs('project-requests.*') => 'Project Saya',
            request()->routeIs('profile.*') => 'Pengaturan Akun',
            default => 'Member Area',
        };

        $memberMenus = [
            [
                'label' => 'Dashboard',
                'route' => 'member.dashboard',
                'patterns' => ['member.dashboard'],
                'icon' => 'layout-dashboard',
            ],
            [
                'label' => 'Katalog Kelas',
                'route' => 'courses.index',
                'patterns' => ['courses.*'],
                'icon' => 'library-big',
            ],
            [
                'label' => 'Kelas Saya',
                'route' => 'member.dashboard',
                'patterns' => ['member.courses.*', 'member.lessons.*'],
                'icon' => 'graduation-cap',
            ],
        ];

        $accountMenus = [
            [
                'label' => 'Source Code',
                'route' => 'products.index',
                'patterns' => ['products.*'],
                'icon' => 'folder-code',
            ],
            [
                'label' => 'Jasa Website',
                'route' => 'services.index',
                'patterns' => ['services.*'],
                'icon' => 'briefcase-business',
            ],
            [
                'label' => 'Pesanan Saya',
                'route' => 'orders.index',
                'patterns' => ['orders.*'],
                'icon' => 'receipt-text',
            ],
            [
                'label' => 'Project Saya',
                'route' => 'project-requests.index',
                'patterns' => ['project-requests.*'],
                'icon' => 'folder-kanban',
            ],
            [
                'label' => 'Profil',
                'route' => 'profile.edit',
                'patterns' => ['profile.*'],
                'icon' => 'user-round-cog',
            ],
        ];
    @endphp

    <div
        x-data="{
            sidebarOpen: false,
            sidebarCollapsed: false
        }"
        x-init="
            sidebarCollapsed =
                localStorage.getItem('memberSidebarCollapsed') === 'true';

            $watch('sidebarCollapsed', value => {
                localStorage.setItem(
                    'memberSidebarCollapsed',
                    value
                );
            });
        "
        class="min-h-screen"
    >
        {{-- OVERLAY MOBILE --}}
        <div
            x-show="sidebarOpen"
            x-cloak
            x-transition.opacity
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-slate-950/70 backdrop-blur-sm lg:hidden"
        ></div>

        {{-- SIDEBAR --}}
        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col overflow-hidden border-r border-white/10 bg-gradient-to-b from-blue-950 via-blue-900 to-blue-800 text-white shadow-2xl shadow-blue-950/30 transition-all duration-300 lg:translate-x-0"
            :class="{
                'translate-x-0': sidebarOpen,
                '-translate-x-full': !sidebarOpen,
                'lg:w-24': sidebarCollapsed,
                'lg:w-72': !sidebarCollapsed
            }"
        >
            {{-- BACKGROUND --}}
            <div class="pointer-events-none absolute inset-0 overflow-hidden">
                <div class="absolute -left-24 -top-24 h-72 w-72 rounded-full bg-cyan-400/15 blur-3xl"></div>

                <div class="absolute -bottom-32 -right-24 h-80 w-80 rounded-full bg-blue-300/10 blur-3xl"></div>

                <div
                    class="absolute inset-0 opacity-[0.035]"
                    style="
                        background-image:
                            linear-gradient(rgba(255,255,255,.8) 1px, transparent 1px),
                            linear-gradient(90deg, rgba(255,255,255,.8) 1px, transparent 1px);
                        background-size: 44px 44px;
                    "
                ></div>
            </div>

            {{-- BRAND --}}
            <div class="relative flex h-24 shrink-0 items-center border-b border-white/10 px-5">
                <a
                    href="{{ route('member.dashboard') }}"
                    class="flex min-w-0 flex-1 items-center gap-3"
                    :class="sidebarCollapsed ? 'lg:justify-center' : ''"
                >
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-white/15 bg-white/10 shadow-xl backdrop-blur-xl">
                        <i
                            data-lucide="graduation-cap"
                            class="h-6 w-6"
                        ></i>
                    </div>

                    <div
                        class="min-w-0"
                        :class="sidebarCollapsed ? 'lg:hidden' : ''"
                    >
                        <h1 class="truncate text-xl font-black tracking-tight">
                            Hilmi<span class="text-cyan-300">Dev</span>
                        </h1>

                        <p class="mt-1 truncate text-[9px] font-bold uppercase tracking-[0.2em] text-blue-300">
                            Member Learning Area
                        </p>
                    </div>
                </a>

                <button
                    type="button"
                    @click="sidebarOpen = false"
                    class="ml-2 flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-blue-100 lg:hidden"
                >
                    <i
                        data-lucide="x"
                        class="h-5 w-5"
                    ></i>
                </button>
            </div>

            {{-- MEMBERSHIP CARD --}}
            <div
                class="relative border-b border-white/10 p-3"
                :class="sidebarCollapsed ? 'lg:hidden' : ''"
            >
                <div class="rounded-2xl border border-white/10 bg-white/[0.07] p-4 backdrop-blur-xl">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-[0.16em] text-blue-300">
                                Paket Aktif
                            </p>

                            <p class="mt-2 truncate text-sm font-black text-white">
                                {{ $activeMembership?->plan?->name ?? 'Membership' }}
                            </p>
                        </div>

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-400/15 text-emerald-300">
                            <i
                                data-lucide="badge-check"
                                class="h-5 w-5"
                            ></i>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center gap-2 text-[10px] font-semibold text-blue-200">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>

                            <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-400"></span>
                        </span>

                        @if ($activeMembership?->expires_at)
                            Aktif sampai
                            {{ $activeMembership->expires_at->translatedFormat('d M Y') }}
                        @else
                            Akses selamanya
                        @endif
                    </div>
                </div>
            </div>

            {{-- NAVIGATION --}}
            <div class="relative flex-1 overflow-y-auto px-3 py-5">
                <nav class="space-y-6">
                    <div>
                        <p
                            class="mb-2 px-3 text-[9px] font-black uppercase tracking-[0.2em] text-blue-300/60"
                            :class="sidebarCollapsed ? 'lg:hidden' : ''"
                        >
                            Pembelajaran
                        </p>

                        <div class="space-y-1.5">
                            @foreach ($memberMenus as $item)
                                @php
                                    $isActive = request()->routeIs(
                                        ...$item['patterns']
                                    );
                                @endphp

                                <a
                                    href="{{ route($item['route']) }}"
                                    @click="sidebarOpen = false"
                                    title="{{ $item['label'] }}"
                                    class="group relative flex min-h-12 items-center gap-3 overflow-hidden rounded-2xl px-3.5 py-3 text-sm transition duration-200"
                                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-3' : ''"
                                    @class([
                                        'bg-white font-black text-blue-950 shadow-xl' => $isActive,
                                        'font-semibold text-blue-100/75 hover:bg-white/10 hover:text-white' => ! $isActive,
                                    ])
                                >
                                    @if ($isActive)
                                        <span class="absolute inset-y-3 left-0 w-1 rounded-r-full bg-cyan-400"></span>
                                    @endif

                                    <span
                                        @class([
                                            'flex h-8 w-8 shrink-0 items-center justify-center rounded-xl',
                                            'bg-blue-50 text-blue-700' => $isActive,
                                            'bg-white/[0.06] text-blue-200 group-hover:bg-white/10 group-hover:text-white' => ! $isActive,
                                        ])
                                    >
                                        <i
                                            data-lucide="{{ $item['icon'] }}"
                                            class="h-4 w-4"
                                        ></i>
                                    </span>

                                    <span
                                        class="min-w-0 flex-1 truncate"
                                        :class="sidebarCollapsed ? 'lg:hidden' : ''"
                                    >
                                        {{ $item['label'] }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <p
                            class="mb-2 px-3 text-[9px] font-black uppercase tracking-[0.2em] text-blue-300/60"
                            :class="sidebarCollapsed ? 'lg:hidden' : ''"
                        >
                            Akun dan Layanan
                        </p>

                        <div class="space-y-1.5">
                            @foreach ($accountMenus as $item)
                                @php
                                    $isActive = request()->routeIs(
                                        ...$item['patterns']
                                    );
                                @endphp

                                <a
                                    href="{{ route($item['route']) }}"
                                    @click="sidebarOpen = false"
                                    title="{{ $item['label'] }}"
                                    class="group relative flex min-h-12 items-center gap-3 overflow-hidden rounded-2xl px-3.5 py-3 text-sm transition duration-200"
                                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-3' : ''"
                                    @class([
                                        'bg-white font-black text-blue-950 shadow-xl' => $isActive,
                                        'font-semibold text-blue-100/75 hover:bg-white/10 hover:text-white' => ! $isActive,
                                    ])
                                >
                                    <span
                                        @class([
                                            'flex h-8 w-8 shrink-0 items-center justify-center rounded-xl',
                                            'bg-blue-50 text-blue-700' => $isActive,
                                            'bg-white/[0.06] text-blue-200 group-hover:bg-white/10 group-hover:text-white' => ! $isActive,
                                        ])
                                    >
                                        <i
                                            data-lucide="{{ $item['icon'] }}"
                                            class="h-4 w-4"
                                        ></i>
                                    </span>

                                    <span
                                        class="min-w-0 flex-1 truncate"
                                        :class="sidebarCollapsed ? 'lg:hidden' : ''"
                                    >
                                        {{ $item['label'] }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </nav>
            </div>

            {{-- USER --}}
            <div class="relative shrink-0 border-t border-white/10 p-3">
                <div
                    class="rounded-2xl border border-white/10 bg-white/[0.06] p-3"
                    :class="sidebarCollapsed ? 'lg:p-2' : ''"
                >
                    <div
                        class="flex items-center gap-3"
                        :class="sidebarCollapsed ? 'lg:justify-center' : ''"
                    >
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-sm font-black text-blue-900">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>

                        <div
                            class="min-w-0 flex-1"
                            :class="sidebarCollapsed ? 'lg:hidden' : ''"
                        >
                            <p class="truncate text-xs font-black">
                                {{ auth()->user()->name }}
                            </p>

                            <p class="mt-1 truncate text-[9px] font-medium text-blue-300">
                                {{ auth()->user()->email }}
                            </p>
                        </div>
                    </div>

                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                        class="mt-3"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-red-500/15 px-3 py-2.5 text-[10px] font-bold text-red-200 transition hover:bg-red-500 hover:text-white"
                        >
                            <i
                                data-lucide="log-out"
                                class="h-4 w-4"
                            ></i>

                            <span :class="sidebarCollapsed ? 'lg:hidden' : ''">
                                Keluar
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- CONTENT --}}
        <div
            class="min-h-screen transition-all duration-300"
            :class="sidebarCollapsed ? 'lg:pl-24' : 'lg:pl-72'"
        >
            {{-- TOPBAR --}}
            <header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/90 backdrop-blur-xl">
                <div class="flex h-20 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                    <div class="flex min-w-0 items-center gap-3">
                        <button
                            type="button"
                            @click="sidebarOpen = true"
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm lg:hidden"
                        >
                            <i
                                data-lucide="menu"
                                class="h-5 w-5"
                            ></i>
                        </button>

                        <button
                            type="button"
                            @click="sidebarCollapsed = !sidebarCollapsed"
                            class="hidden h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 lg:flex"
                        >
                            <i
                                data-lucide="panel-left"
                                class="h-5 w-5"
                            ></i>
                        </button>

                        <div class="min-w-0">
                            <p class="text-[9px] font-black uppercase tracking-[0.18em] text-blue-600">
                                HilmiDev Learning
                            </p>

                            <h2 class="mt-1 truncate text-lg font-black text-slate-950 sm:text-xl">
                                {{ $pageTitle }}
                            </h2>
                        </div>
                    </div>

                    <div class="flex shrink-0 items-center gap-2">
                        <a
                            href="{{ route('courses.index') }}"
                            class="hidden h-11 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-xs font-black text-slate-600 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 sm:inline-flex"
                        >
                            <i
                                data-lucide="library-big"
                                class="h-4 w-4"
                            ></i>

                            Semua Kelas
                        </a>

                        <a
                            href="{{ route('home') }}"
                            class="flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700"
                            title="Halaman utama"
                        >
                            <i
                                data-lucide="house"
                                class="h-5 w-5"
                            ></i>
                        </a>
                    </div>
                </div>
            </header>

            <main class="min-w-0">
                {{ $slot }}
            </main>

            <footer class="border-t border-slate-200 bg-white">
                <div class="flex flex-col gap-3 px-5 py-5 text-xs font-medium text-slate-400 sm:flex-row sm:items-center sm:justify-between lg:px-8">
                    <p>
                        © {{ date('Y') }} HilmiDev Member Learning.
                    </p>

                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Membership aktif
                    </div>
                </div>
            </footer>
        </div>
    </div>

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
# DASHBOARD MEMBER PREMIUM
# ============================================================

cat > resources/views/member/dashboard.blade.php <<'BLADE'
<x-layouts.member>
    @php
        $totalLessons = $courseCards->sum('total');

        $overallPercentage = $totalLessons > 0
            ? (int) round(($completedLessons / $totalLessons) * 100)
            : 0;

        $remainingDays = $membership->expires_at
            ? max(
                0,
                (int) now()->diffInDays(
                    $membership->expires_at,
                    false
                )
            )
            : null;

        $lastLesson = $lastProgress?->lesson;
        $lastCourse = $lastLesson?->module?->course;
    @endphp

    <div class="min-h-screen bg-slate-50">
        {{-- HERO --}}
        <section class="px-4 pt-6 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-blue-950 via-blue-800 to-blue-600 px-6 py-9 text-white shadow-2xl shadow-blue-900/20 sm:px-9 lg:px-10 lg:py-11">
                <div class="pointer-events-none absolute inset-0">
                    <div class="absolute -right-28 -top-28 h-80 w-80 rounded-full bg-cyan-300/15 blur-3xl"></div>

                    <div class="absolute -bottom-32 left-1/3 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
                </div>

                <div class="relative grid items-center gap-8 lg:grid-cols-12">
                    <div class="lg:col-span-8">
                        <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-black text-blue-100 backdrop-blur-xl">
                            <span class="relative flex h-2 w-2">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-300 opacity-75"></span>

                                <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-300"></span>
                            </span>

                            Membership Aktif
                        </div>

                        <h1 class="mt-6 text-3xl font-black leading-tight tracking-tight sm:text-4xl lg:text-5xl">
                            Selamat belajar,
                            {{ auth()->user()->name }}
                        </h1>

                        <p class="mt-4 max-w-2xl text-sm font-medium leading-7 text-blue-100/80 sm:text-base">
                            Lanjutkan pembelajaran, selesaikan setiap materi,
                            dan mulai membangun aplikasi website dari project nyata.
                        </p>

                        <div class="mt-7 flex flex-wrap gap-3">
                            @if ($lastLesson)
                                <a
                                    href="{{ route('member.lessons.show', $lastLesson) }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-6 py-4 text-sm font-black text-blue-900 shadow-xl transition hover:-translate-y-0.5 hover:bg-blue-50"
                                >
                                    <i
                                        data-lucide="circle-play"
                                        class="h-5 w-5"
                                    ></i>

                                    Lanjutkan Materi
                                </a>
                            @else
                                <a
                                    href="#daftar-kelas"
                                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-6 py-4 text-sm font-black text-blue-900 shadow-xl transition hover:-translate-y-0.5 hover:bg-blue-50"
                                >
                                    <i
                                        data-lucide="circle-play"
                                        class="h-5 w-5"
                                    ></i>

                                    Mulai Belajar
                                </a>
                            @endif

                            <a
                                href="{{ route('courses.index') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/20 bg-white/10 px-6 py-4 text-sm font-black text-white backdrop-blur transition hover:bg-white/15"
                            >
                                <i
                                    data-lucide="library-big"
                                    class="h-5 w-5"
                                ></i>

                                Katalog Kelas
                            </a>
                        </div>
                    </div>

                    <div class="lg:col-span-4">
                        <div class="rounded-[1.75rem] border border-white/15 bg-white/10 p-6 backdrop-blur-xl">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.18em] text-blue-200">
                                        Paket Member
                                    </p>

                                    <p class="mt-3 text-xl font-black">
                                        {{ $membership->plan?->name ?? 'Membership' }}
                                    </p>
                                </div>

                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 text-cyan-300">
                                    <i
                                        data-lucide="badge-check"
                                        class="h-6 w-6"
                                    ></i>
                                </div>
                            </div>

                            <div class="mt-6 border-t border-white/10 pt-5">
                                <p class="text-xs font-medium text-blue-100/70">
                                    Masa aktif
                                </p>

                                <p class="mt-2 text-sm font-black">
                                    @if ($membership->expires_at)
                                        Sampai
                                        {{ $membership->expires_at->translatedFormat('d F Y') }}
                                    @else
                                        Akses selamanya
                                    @endif
                                </p>

                                @if ($remainingDays !== null)
                                    <p class="mt-2 text-xs font-semibold text-cyan-300">
                                        {{ number_format($remainingDays) }}
                                        hari tersisa
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <main class="px-4 py-8 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-bold text-emerald-700">
                    <i
                        data-lucide="circle-check"
                        class="mt-0.5 h-5 w-5 shrink-0"
                    ></i>

                    {{ session('success') }}
                </div>
            @endif

            {{-- STATISTICS --}}
            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <article class="rounded-[1.5rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.14em] text-slate-400">
                                Kelas Tersedia
                            </p>

                            <p class="mt-3 text-3xl font-black text-slate-950">
                                {{ number_format($totalCourses) }}
                            </p>
                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-700">
                            <i
                                data-lucide="library-big"
                                class="h-6 w-6"
                            ></i>
                        </div>
                    </div>
                </article>

                <article class="rounded-[1.5rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.14em] text-slate-400">
                                Kelas Diikuti
                            </p>

                            <p class="mt-3 text-3xl font-black text-blue-700">
                                {{ number_format($enrolledCourses) }}
                            </p>
                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-700">
                            <i
                                data-lucide="graduation-cap"
                                class="h-6 w-6"
                            ></i>
                        </div>
                    </div>
                </article>

                <article class="rounded-[1.5rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.14em] text-slate-400">
                                Materi Selesai
                            </p>

                            <p class="mt-3 text-3xl font-black text-emerald-600">
                                {{ number_format($completedLessons) }}
                            </p>
                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                            <i
                                data-lucide="circle-check-big"
                                class="h-6 w-6"
                            ></i>
                        </div>
                    </div>
                </article>

                <article class="rounded-[1.5rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.14em] text-slate-400">
                                Progress Total
                            </p>

                            <p class="mt-3 text-3xl font-black text-violet-600">
                                {{ $overallPercentage }}%
                            </p>
                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-50 text-violet-600">
                            <i
                                data-lucide="chart-no-axes-column-increasing"
                                class="h-6 w-6"
                            ></i>
                        </div>
                    </div>
                </article>
            </section>

            {{-- LAST ACTIVITY --}}
            @if ($lastLesson)
                <section class="mt-8">
                    <div class="mb-5 flex items-end justify-between gap-4">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.16em] text-blue-700">
                                Aktivitas Terakhir
                            </p>

                            <h2 class="mt-2 text-2xl font-black text-slate-950">
                                Lanjutkan pembelajaran
                            </h2>
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
                        <div class="grid items-center lg:grid-cols-12">
                            <div class="relative min-h-64 bg-blue-950 lg:col-span-5 lg:min-h-full">
                                @if ($lastCourse?->thumbnail)
                                    <img
                                        src="{{ asset('storage/' . $lastCourse->thumbnail) }}"
                                        alt="{{ $lastCourse->title }}"
                                        class="absolute inset-0 h-full w-full object-cover opacity-60"
                                    >
                                @endif

                                <div class="absolute inset-0 bg-gradient-to-r from-blue-950/90 to-blue-900/40"></div>

                                <div class="relative flex min-h-64 items-center justify-center p-8">
                                    <div class="flex h-20 w-20 items-center justify-center rounded-full border border-white/20 bg-white/15 text-white shadow-2xl backdrop-blur">
                                        <i
                                            data-lucide="play"
                                            class="ml-1 h-9 w-9"
                                        ></i>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 sm:p-8 lg:col-span-7">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-full bg-blue-50 px-3 py-2 text-[10px] font-black uppercase tracking-wider text-blue-700">
                                        {{ $lastCourse?->title ?? 'Kelas Coding' }}
                                    </span>

                                    @if ($lastProgress->is_completed)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-2 text-[10px] font-black text-emerald-700">
                                            <i
                                                data-lucide="circle-check"
                                                class="h-3.5 w-3.5"
                                            ></i>

                                            Selesai
                                        </span>
                                    @endif
                                </div>

                                <h3 class="mt-5 text-2xl font-black leading-8 text-slate-950">
                                    {{ $lastLesson->title }}
                                </h3>

                                <p class="mt-3 line-clamp-3 text-sm font-medium leading-7 text-slate-500">
                                    {{ $lastLesson->description ?: 'Lanjutkan video pembelajaran terakhir yang kamu buka.' }}
                                </p>

                                <div class="mt-6 flex flex-wrap items-center gap-5 text-xs font-bold text-slate-500">
                                    <span class="inline-flex items-center gap-2">
                                        <i
                                            data-lucide="clock-3"
                                            class="h-4 w-4 text-blue-700"
                                        ></i>

                                        {{ $lastLesson->duration_minutes }}
                                        menit
                                    </span>

                                    <span class="inline-flex items-center gap-2">
                                        <i
                                            data-lucide="folder"
                                            class="h-4 w-4 text-blue-700"
                                        ></i>

                                        {{ $lastLesson->module?->title }}
                                    </span>
                                </div>

                                <a
                                    href="{{ route('member.lessons.show', $lastLesson) }}"
                                    class="mt-7 inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-700 px-6 py-4 text-sm font-black text-white shadow-lg shadow-blue-700/20 transition hover:-translate-y-0.5 hover:bg-blue-800"
                                >
                                    <i
                                        data-lucide="circle-play"
                                        class="h-5 w-5"
                                    ></i>

                                    Lanjutkan Menonton
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            @endif

            {{-- COURSE LIST --}}
            <section
                id="daftar-kelas"
                class="mt-10 scroll-mt-28"
            >
                <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.16em] text-blue-700">
                            Kelas Member
                        </p>

                        <h2 class="mt-2 text-2xl font-black text-slate-950 sm:text-3xl">
                            Pilih kelas dan mulai belajar
                        </h2>

                        <p class="mt-2 text-sm font-medium text-slate-500">
                            Seluruh kelas berikut dapat diakses selama membership aktif.
                        </p>
                    </div>

                    <a
                        href="{{ route('courses.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-black text-blue-700 transition hover:text-blue-900"
                    >
                        Lihat Semua Kelas

                        <i
                            data-lucide="arrow-right"
                            class="h-4 w-4"
                        ></i>
                    </a>
                </div>

                @if ($courseCards->count())
                    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($courseCards as $card)
                            @php
                                $course = $card['course'];
                            @endphp

                            <article class="group flex h-full flex-col overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-700/[0.06]">
                                <a
                                    href="{{ route('member.courses.show', $course) }}"
                                    class="block p-3 pb-0"
                                >
                                    <div class="relative aspect-video overflow-hidden rounded-[1.3rem] bg-slate-100">
                                        @if ($course->thumbnail)
                                            <img
                                                src="{{ asset('storage/' . $course->thumbnail) }}"
                                                alt="{{ $course->title }}"
                                                loading="lazy"
                                                class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.04]"
                                            >
                                        @else
                                            <div class="flex h-full items-center justify-center bg-blue-50 text-blue-700">
                                                <i
                                                    data-lucide="graduation-cap"
                                                    class="h-12 w-12"
                                                ></i>
                                            </div>
                                        @endif

                                        @if ($course->is_featured)
                                            <span class="absolute left-4 top-4 inline-flex items-center gap-1.5 rounded-full bg-white/95 px-3 py-2 text-[10px] font-black text-blue-700 shadow-md">
                                                <i
                                                    data-lucide="star"
                                                    class="h-3.5 w-3.5 fill-current"
                                                ></i>

                                                Kelas Pilihan
                                            </span>
                                        @endif

                                        <div class="absolute bottom-4 right-4 flex h-11 w-11 items-center justify-center rounded-xl bg-blue-700 text-white shadow-lg">
                                            <i
                                                data-lucide="play"
                                                class="ml-0.5 h-5 w-5"
                                            ></i>
                                        </div>
                                    </div>
                                </a>

                                <div class="flex flex-1 flex-col p-6">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="rounded-full bg-blue-50 px-3 py-1.5 text-[9px] font-black uppercase tracking-wider text-blue-700">
                                            {{ $course->level }}
                                        </span>

                                        @if ($course->technology)
                                            <span class="rounded-full bg-slate-100 px-3 py-1.5 text-[9px] font-black uppercase tracking-wider text-slate-500">
                                                {{ $course->technology }}
                                            </span>
                                        @endif
                                    </div>

                                    <a href="{{ route('member.courses.show', $course) }}">
                                        <h3 class="mt-5 line-clamp-2 text-xl font-black leading-7 text-slate-950 transition group-hover:text-blue-700">
                                            {{ $course->title }}
                                        </h3>
                                    </a>

                                    <p class="mt-3 line-clamp-2 text-sm font-medium leading-6 text-slate-500">
                                        {{ $course->subtitle }}
                                    </p>

                                    <div class="mt-5 flex flex-wrap gap-4 text-xs font-bold text-slate-500">
                                        <span class="inline-flex items-center gap-1.5">
                                            <i
                                                data-lucide="circle-play"
                                                class="h-4 w-4 text-blue-700"
                                            ></i>

                                            {{ $card['total'] }}
                                            video
                                        </span>

                                        <span class="inline-flex items-center gap-1.5">
                                            <i
                                                data-lucide="clock-3"
                                                class="h-4 w-4 text-blue-700"
                                            ></i>

                                            {{ $course->formatted_duration }}
                                        </span>
                                    </div>

                                    <div class="mt-auto pt-6">
                                        <div class="border-t border-slate-100 pt-5">
                                            <div class="flex items-center justify-between gap-3 text-xs font-bold">
                                                <span class="text-slate-500">
                                                    {{ $card['completed'] }}
                                                    dari
                                                    {{ $card['total'] }}
                                                    video
                                                </span>

                                                <span class="text-blue-700">
                                                    {{ $card['percentage'] }}%
                                                </span>
                                            </div>

                                            <div class="mt-3 h-2.5 overflow-hidden rounded-full bg-slate-100">
                                                <div
                                                    class="h-full rounded-full bg-blue-700 transition-all duration-500"
                                                    style="width: {{ $card['percentage'] }}%"
                                                ></div>
                                            </div>

                                            <a
                                                href="{{ route('member.courses.show', $course) }}"
                                                class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-3.5 text-xs font-black text-white transition hover:bg-blue-800"
                                            >
                                                {{ $card['percentage'] > 0
                                                    ? 'Lanjutkan Belajar'
                                                    : 'Mulai Belajar' }}

                                                <i
                                                    data-lucide="arrow-up-right"
                                                    class="h-4 w-4"
                                                ></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[1.75rem] bg-blue-50 text-blue-700">
                            <i
                                data-lucide="book-open-text"
                                class="h-9 w-9"
                            ></i>
                        </div>

                        <h3 class="mt-6 text-2xl font-black text-slate-950">
                            Kelas belum tersedia
                        </h3>

                        <p class="mx-auto mt-3 max-w-lg text-sm font-medium leading-7 text-slate-500">
                            Admin belum mempublikasikan kelas coding.
                            Kelas yang aktif akan tampil otomatis di halaman ini.
                        </p>
                    </div>
                @endif
            </section>
        </main>
    </div>
</x-layouts.member>
BLADE

# ============================================================
# GUNAKAN LAYOUT MEMBER DI HALAMAN KELAS DAN VIDEO
# ============================================================

php <<'PHP'
<?php

$files = [
    'resources/views/member/courses/show.blade.php',
    'resources/views/member/lessons/show.blade.php',
];

foreach ($files as $path) {
    if (! file_exists($path)) {
        continue;
    }

    $content = file_get_contents($path);

    $content = str_replace(
        '<x-app-layout>',
        '<x-layouts.member>',
        $content
    );

    $content = str_replace(
        '</x-app-layout>',
        '</x-layouts.member>',
        $content
    );

    file_put_contents($path, $content);
}

echo "Layout halaman kelas dan video berhasil diperbarui.\n";
PHP

# ============================================================
# VALIDASI
# ============================================================

php artisan optimize:clear

php artisan view:cache

php artisan view:clear

php artisan route:list --path=member

echo ""
echo "============================================================"
echo "DASHBOARD MEMBER PREMIUM BERHASIL DIBUAT"
echo "============================================================"
echo "Dashboard : /member/dashboard"
echo "Backup    : $BACKUP_DIR"
echo "============================================================"
