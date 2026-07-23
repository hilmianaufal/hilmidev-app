<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>HilmiDev Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
        $pageTitle = match (true) {
            request()->routeIs('admin.products.*') => 'Manajemen Produk',
            request()->routeIs('admin.categories.*') => 'Kategori Produk',
            request()->routeIs('admin.orders.*') => 'Manajemen Order',
            request()->routeIs('admin.payments.*') => 'Verifikasi Pembayaran',
            request()->routeIs('admin.services.*') => 'Layanan Website',
            request()->routeIs('admin.project-requests.*') => 'Project Request',
            request()->routeIs('admin.clients.*') => 'Manajemen Client',
            request()->routeIs('admin.portfolios.*') => 'Portfolio Project',
            request()->routeIs('admin.testimonials.*') => 'Testimonial',
            request()->routeIs('admin.posts.*') => 'Artikel Blog',
            request()->routeIs('admin.members.*') => 'Manajemen Member',
            request()->routeIs('admin.membership-plans.*') => 'Paket Membership',
            request()->routeIs('admin.courses.*') => 'Kelas Coding',
            request()->routeIs('admin.lesson-discussions.*') => 'Diskusi Member',
            default => 'Dashboard Admin',
        };

        $menuGroups = [
            [
                'label' => 'Overview',
                'items' => [
                    [
                        'label' => 'Dashboard',
                        'route' => 'admin.dashboard',
                        'patterns' => ['admin.dashboard'],
                        'icon' => 'layout-dashboard',
                    ],
                ],
            ],
            [
                'label' => 'Marketplace',
                'items' => [
                    [
                        'label' => 'Produk',
                        'route' => 'admin.products.index',
                        'patterns' => ['admin.products.*'],
                        'icon' => 'package-2',
                    ],
                    [
                        'label' => 'Kategori',
                        'route' => 'admin.categories.index',
                        'patterns' => ['admin.categories.*'],
                        'icon' => 'shapes',
                    ],
                    [
                        'label' => 'Order',
                        'route' => 'admin.orders.index',
                        'patterns' => ['admin.orders.*'],
                        'icon' => 'shopping-bag',
                    ],
                    [
                        'label' => 'Pembayaran',
                        'route' => 'admin.payments.index',
                        'patterns' => ['admin.payments.*'],
                        'icon' => 'credit-card',
                    ],
                ],
            ],
            [
                'label' => 'Layanan & Konten',
                'items' => [
                    [
                        'label' => 'Jasa Website',
                        'route' => 'admin.services.index',
                        'patterns' => ['admin.services.*'],
                        'icon' => 'briefcase-business',
                    ],
                    [
                        'label' => 'Project Request',
                        'route' => 'admin.project-requests.index',
                        'patterns' => ['admin.project-requests.*'],
                        'icon' => 'folder-kanban',
                    ],
                    [
                        'label' => 'Client',
                        'route' => 'admin.clients.index',
                        'patterns' => ['admin.clients.*'],
                        'icon' => 'users',
                    ],
                    [
                        'label' => 'Portfolio',
                        'route' => 'admin.portfolios.index',
                        'patterns' => ['admin.portfolios.*'],
                        'icon' => 'panels-top-left',
                    ],
                    [
                        'label' => 'Testimonial',
                        'route' => 'admin.testimonials.index',
                        'patterns' => ['admin.testimonials.*'],
                        'icon' => 'messages-square',
                    ],
                    [
                        'label' => 'Blog',
                        'route' => 'admin.posts.index',
                        'patterns' => ['admin.posts.*'],
                        'icon' => 'newspaper',
                    ],
                ],
            ],
            [
                'label' => 'Bimbingan Coding',
                'items' => [
                    [
                        'label' => 'Member',
                        'route' => 'admin.members.index',
                        'patterns' => ['admin.members.*'],
                        'icon' => 'users-round',
                    ],
                    [
                        'label' => 'Paket Member',
                        'route' => 'admin.membership-plans.index',
                        'patterns' => ['admin.membership-plans.*'],
                        'icon' => 'badge-dollar-sign',
                    ],
                    [
                        'label' => 'Kelas Coding',
                        'route' => 'admin.courses.index',
                        'patterns' => [
                            'admin.courses.*',
                            'admin.modules.*',
                            'admin.lessons.*',
                        ],
                        'icon' => 'graduation-cap',
                    ],
                    [
                        'label' => 'Diskusi Member',
                        'route' => 'admin.lesson-discussions.index',
                        'patterns' => ['admin.lesson-discussions.*'],
                        'icon' => 'message-circle-question',
                    ],
                ],
            ],
        ];
    @endphp

    <div
        x-data="{
            sidebarOpen: false,
            sidebarCollapsed: false
        }"
        x-init="
            sidebarCollapsed = localStorage.getItem('adminSidebarCollapsed') === 'true';

            $watch('sidebarCollapsed', value => {
                localStorage.setItem('adminSidebarCollapsed', value);
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
            class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col overflow-hidden border-r border-white/10 bg-gradient-to-b from-slate-950 via-blue-950 to-blue-900 text-white shadow-2xl shadow-blue-950/30 transition-all duration-300 lg:translate-x-0"
            :class="{
                'translate-x-0': sidebarOpen,
                '-translate-x-full': !sidebarOpen,
                'lg:w-24': sidebarCollapsed,
                'lg:w-72': !sidebarCollapsed
            }"
        >
            {{-- BACKGROUND DECORATION --}}
            <div class="pointer-events-none absolute inset-0 overflow-hidden">
                <div class="absolute -left-24 -top-24 h-72 w-72 rounded-full bg-blue-500/20 blur-3xl"></div>
                <div class="absolute -bottom-32 -right-24 h-80 w-80 rounded-full bg-cyan-400/10 blur-3xl"></div>

                <div
                    class="absolute inset-0 opacity-[0.035]"
                    style="
                        background-image:
                            linear-gradient(rgba(255,255,255,.8) 1px, transparent 1px),
                            linear-gradient(90deg, rgba(255,255,255,.8) 1px, transparent 1px);
                        background-size: 42px 42px;
                    "
                ></div>
            </div>

            {{-- BRAND --}}
            <div class="relative flex h-24 shrink-0 items-center border-b border-white/10 px-5">
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="flex min-w-0 flex-1 items-center gap-3"
                    :class="sidebarCollapsed ? 'lg:justify-center' : ''"
                >
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-white/15 bg-white/10 text-white shadow-xl backdrop-blur-xl">
                        <i data-lucide="code-2" class="h-6 w-6"></i>
                    </div>

                    <div
                        class="min-w-0"
                        :class="sidebarCollapsed ? 'lg:hidden' : ''"
                    >
                        <h1 class="truncate text-xl font-black tracking-tight text-white">
                            Hilmi<span class="text-cyan-300">Dev</span>
                        </h1>

                        <p class="mt-1 truncate text-[9px] font-bold uppercase tracking-[0.2em] text-blue-300">
                            Admin Control Center
                        </p>
                    </div>
                </a>

                <button
                    type="button"
                    @click="sidebarOpen = false"
                    class="ml-2 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-blue-100 transition hover:bg-white/10 hover:text-white lg:hidden"
                >
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            {{-- NAVIGATION --}}
            <div class="relative flex-1 overflow-y-auto px-3 py-5">
                <nav class="space-y-6">
                    @foreach ($menuGroups as $group)
                        <div>
                            <p
                                class="mb-2 px-3 text-[9px] font-black uppercase tracking-[0.2em] text-blue-300/60"
                                :class="sidebarCollapsed ? 'lg:hidden' : ''"
                            >
                                {{ $group['label'] }}
                            </p>

                            <div class="space-y-1.5">
                                @foreach ($group['items'] as $item)
                                    @php
                                        $isActive = request()->routeIs(...$item['patterns']);
                                    @endphp

                                    <a
                                        href="{{ route($item['route']) }}"
                                        @click="sidebarOpen = false"
                                        title="{{ $item['label'] }}"
                                        class="group relative flex min-h-12 items-center gap-3 overflow-hidden rounded-2xl px-3.5 py-3 text-sm transition duration-200"
                                        :class="sidebarCollapsed ? 'lg:justify-center lg:px-3' : ''"
                                        @class([
                                            'bg-white font-black text-blue-950 shadow-xl shadow-blue-950/20' => $isActive,
                                            'font-semibold text-blue-100/75 hover:bg-white/10 hover:text-white' => ! $isActive,
                                        ])
                                    >
                                        @if ($isActive)
                                            <span class="absolute inset-y-3 left-0 w-1 rounded-r-full bg-cyan-400"></span>
                                        @endif

                                        <span
                                            @class([
                                                'flex h-8 w-8 shrink-0 items-center justify-center rounded-xl transition',
                                                'bg-blue-50 text-blue-700' => $isActive,
                                                'bg-white/[0.06] text-blue-200 group-hover:bg-white/10 group-hover:text-white' => ! $isActive,
                                            ])
                                        >
                                            <i
                                                data-lucide="{{ $item['icon'] }}"
                                                class="h-4.5 w-4.5"
                                            ></i>
                                        </span>

                                        <span
                                            class="min-w-0 flex-1 truncate"
                                            :class="sidebarCollapsed ? 'lg:hidden' : ''"
                                        >
                                            {{ $item['label'] }}
                                        </span>

                                        @if ($isActive)
                                            <span
                                                class="h-2 w-2 shrink-0 rounded-full bg-cyan-400 shadow-lg shadow-cyan-400/50"
                                                :class="sidebarCollapsed ? 'lg:hidden' : ''"
                                            ></span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </nav>
            </div>

            {{-- USER AREA --}}
            <div class="relative shrink-0 border-t border-white/10 p-3">
                <div
                    class="rounded-2xl border border-white/10 bg-white/[0.06] p-3 backdrop-blur-xl"
                    :class="sidebarCollapsed ? 'lg:p-2' : ''"
                >
                    <div
                        class="flex items-center gap-3"
                        :class="sidebarCollapsed ? 'lg:justify-center' : ''"
                    >
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-sm font-black text-blue-900 shadow-lg">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>

                        <div
                            class="min-w-0 flex-1"
                            :class="sidebarCollapsed ? 'lg:hidden' : ''"
                        >
                            <p class="truncate text-xs font-black text-white">
                                {{ auth()->user()->name }}
                            </p>

                            <p class="mt-1 truncate text-[9px] font-medium text-blue-300">
                                {{ auth()->user()->email }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="mt-3 grid grid-cols-2 gap-2"
                        :class="sidebarCollapsed ? 'lg:mt-2 lg:grid-cols-1' : ''"
                    >
                        <a
                            href="{{ route('home') }}"
                            target="_blank"
                            title="Lihat website"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 px-3 py-2.5 text-[10px] font-bold text-blue-100 transition hover:bg-white/10 hover:text-white"
                        >
                            <i data-lucide="external-link" class="h-4 w-4"></i>

                            <span :class="sidebarCollapsed ? 'lg:hidden' : ''">
                                Website
                            </span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button
                                type="submit"
                                title="Keluar"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-red-500/15 px-3 py-2.5 text-[10px] font-bold text-red-200 transition hover:bg-red-500 hover:text-white"
                            >
                                <i data-lucide="log-out" class="h-4 w-4"></i>

                                <span :class="sidebarCollapsed ? 'lg:hidden' : ''">
                                    Keluar
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        {{-- CONTENT WRAPPER --}}
        <div
            class="min-h-screen transition-all duration-300"
            :class="sidebarCollapsed ? 'lg:pl-24' : 'lg:pl-72'"
        >
            {{-- TOPBAR --}}
            <header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/90 backdrop-blur-xl">
                <div class="flex h-20 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                    <div class="flex min-w-0 items-center gap-3">
                        {{-- MOBILE SIDEBAR --}}
                        <button
                            type="button"
                            @click="sidebarOpen = true"
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 lg:hidden"
                        >
                            <i data-lucide="menu" class="h-5 w-5"></i>
                        </button>

                        {{-- DESKTOP COLLAPSE --}}
                        <button
                            type="button"
                            @click="sidebarCollapsed = !sidebarCollapsed"
                            class="hidden h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 lg:flex"
                            title="Ciutkan sidebar"
                        >
                            <i data-lucide="panel-left" class="h-5 w-5"></i>
                        </button>

                        <div class="min-w-0">
                            <p class="text-[9px] font-black uppercase tracking-[0.18em] text-blue-600">
                                HilmiDev Administration
                            </p>

                            <h2 class="mt-1 truncate text-lg font-black text-slate-950 sm:text-xl">
                                {{ $pageTitle }}
                            </h2>
                        </div>
                    </div>

                    <div class="flex shrink-0 items-center gap-2 sm:gap-3">
                        <a
                            href="{{ route('home') }}"
                            target="_blank"
                            class="hidden h-11 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-xs font-black text-slate-600 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 sm:inline-flex"
                        >
                            <i data-lucide="globe-2" class="h-4 w-4"></i>
                            Lihat Website
                        </a>

                        <a
                            href="{{ route('profile.edit') }}"
                            class="flex h-11 items-center gap-3 rounded-xl border border-slate-200 bg-white p-1.5 pr-3 shadow-sm transition hover:border-blue-200 hover:bg-blue-50"
                        >
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-700 text-xs font-black text-white">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>

                            <div class="hidden max-w-36 text-left md:block">
                                <p class="truncate text-[11px] font-black text-slate-800">
                                    {{ auth()->user()->name }}
                                </p>

                                <p class="mt-0.5 text-[9px] font-bold uppercase tracking-wider text-blue-600">
                                    Administrator
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
            </header>

            {{-- PAGE CONTENT --}}
            <main class="min-w-0">
                {{ $slot }}
            </main>

            {{-- ADMIN FOOTER --}}
            <footer class="border-t border-slate-200 bg-white">
                <div class="flex flex-col gap-3 px-5 py-5 text-xs font-medium text-slate-400 sm:flex-row sm:items-center sm:justify-between lg:px-8">
                    <p>
                        © {{ date('Y') }} HilmiDev Administration.
                    </p>

                    <div class="flex items-center gap-2">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                        </span>

                        Sistem berjalan normal
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
