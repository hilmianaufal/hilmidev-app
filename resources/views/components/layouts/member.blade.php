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
