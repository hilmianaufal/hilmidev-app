@php
    $currentUser = auth()->user();

    $dashboardRoute = null;
    $dashboardLabel = null;
    $dashboardIcon = null;

    if ($currentUser) {
        if ($currentUser->isAdmin()) {
            $dashboardRoute = 'admin.dashboard';
            $dashboardLabel = 'Admin Panel';
            $dashboardIcon = 'shield-check';
        } elseif ($currentUser->hasActiveMembership()) {
            $dashboardRoute = 'member.dashboard';
            $dashboardLabel = 'Area Member';
            $dashboardIcon = 'graduation-cap';
        } else {
            $dashboardRoute = 'client.dashboard';
            $dashboardLabel = 'Dashboard Client';
            $dashboardIcon = 'layout-dashboard';
        }
    }

    $menus = [
        [
            'label' => 'Home',
            'route' => 'home',
            'patterns' => ['home'],
            'icon' => 'house',
        ],
        [
            'label' => 'Source Code',
            'route' => 'products.index',
            'patterns' => ['products.*'],
            'icon' => 'folder-code',
        ],
        [
            'label' => 'Jasa Website',
            'route' => 'services.index',
            'patterns' => ['services.*', 'project-requests.*'],
            'icon' => 'briefcase-business',
        ],
        [
            'label' => 'Kelas Coding',
            'route' => 'courses.index',
            'patterns' => ['courses.*'],
            'icon' => 'library-big',
        ],
        [
            'label' => 'Blog',
            'route' => 'blog.index',
            'patterns' => ['blog.*'],
            'icon' => 'newspaper',
        ],
    ];
@endphp

<nav
    x-data="{
        mobileOpen: false,
        accountOpen: false
    }"
    @keydown.escape.window="
        mobileOpen = false;
        accountOpen = false;
    "
    class="sticky top-0 z-50 border-b border-white/10 bg-gradient-to-r from-blue-950 via-blue-900 to-blue-700 text-white shadow-xl shadow-blue-950/20"
>
    {{-- BACKGROUND DECORATION --}}
    <div class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -left-24 -top-24 h-56 w-56 rounded-full bg-cyan-300/10 blur-3xl"></div>
        <div class="absolute -right-20 top-0 h-52 w-52 rounded-full bg-blue-300/10 blur-3xl"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between gap-5">

            {{-- BRAND --}}
            <a
                href="{{ route('home') }}"
                class="group flex shrink-0 items-center gap-3"
            >
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/15 bg-white/10 shadow-lg backdrop-blur-xl transition duration-300 group-hover:-translate-y-0.5 group-hover:bg-white/15">
                    <i
                        data-lucide="code-2"
                        class="h-6 w-6 text-white"
                    ></i>
                </div>

                <div class="leading-none">
                    <p class="text-xl font-black tracking-tight text-white">
                        Hilmi<span class="text-cyan-300">Dev</span>
                    </p>

                    <p class="mt-1.5 text-[9px] font-bold uppercase tracking-[0.18em] text-blue-200">
                        Digital Learning Platform
                    </p>
                </div>
            </a>

            {{-- MENU DESKTOP --}}
            <div class="hidden flex-1 items-center justify-center xl:flex">
                <div class="flex items-center gap-1 rounded-2xl border border-white/10 bg-white/[0.06] p-1.5 backdrop-blur-xl">
                    @foreach ($menus as $menu)
                        @php
                            $isActive = request()->routeIs(...$menu['patterns']);
                        @endphp

                        <a
                            href="{{ route($menu['route']) }}"
                            @class([
                                'inline-flex items-center gap-2 rounded-xl px-3.5 py-2.5 text-xs font-black transition duration-200',
                                'bg-white text-blue-950 shadow-lg' => $isActive,
                                'text-blue-100/80 hover:bg-white/10 hover:text-white' => ! $isActive,
                            ])
                        >
                            <i
                                data-lucide="{{ $menu['icon'] }}"
                                class="h-4 w-4"
                            ></i>

                            {{ $menu['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- AREA AKUN DESKTOP --}}
            <div class="hidden shrink-0 items-center gap-2 lg:flex">
                @guest
                    <a
                        href="{{ route('login') }}"
                        class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-white px-5 text-xs font-black text-blue-900 shadow-xl shadow-blue-950/20 transition duration-300 hover:-translate-y-0.5 hover:bg-cyan-50"
                    >
                        <i
                            data-lucide="log-in"
                            class="h-4 w-4"
                        ></i>

                        Login Member
                    </a>
                @else
                    {{-- TOMBOL AREA MEMBER / DASHBOARD --}}
                    <a
                        href="{{ route($dashboardRoute) }}"
                        @class([
                            'inline-flex h-12 items-center justify-center gap-2 rounded-2xl px-5 text-xs font-black shadow-xl transition duration-300 hover:-translate-y-0.5',
                            'bg-cyan-300 text-blue-950 shadow-cyan-950/10 hover:bg-cyan-200'
                                => $currentUser->hasActiveMembership() && ! $currentUser->isAdmin(),
                            'bg-white text-blue-900 shadow-blue-950/20 hover:bg-blue-50'
                                => ! ($currentUser->hasActiveMembership() && ! $currentUser->isAdmin()),
                        ])
                    >
                        <i
                            data-lucide="{{ $dashboardIcon }}"
                            class="h-4 w-4"
                        ></i>

                        {{ $dashboardLabel }}
                    </a>

                    {{-- DROPDOWN AKUN --}}
                    <div
                        class="relative"
                        @click.outside="accountOpen = false"
                    >
                        <button
                            type="button"
                            @click="accountOpen = !accountOpen"
                            class="flex h-12 items-center gap-3 rounded-2xl border border-white/15 bg-white/10 px-2.5 pr-3 backdrop-blur transition hover:bg-white/15"
                        >
                            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-white text-xs font-black text-blue-900">
                                {{ strtoupper(substr($currentUser->name, 0, 1)) }}
                            </div>

                            <div class="hidden max-w-28 text-left 2xl:block">
                                <p class="truncate text-[10px] font-black text-white">
                                    {{ $currentUser->name }}
                                </p>

                                <p class="mt-1 text-[8px] font-bold uppercase tracking-wider text-blue-200">
                                    @if ($currentUser->isAdmin())
                                        Administrator
                                    @elseif ($currentUser->hasActiveMembership())
                                        Member Aktif
                                    @else
                                        Client
                                    @endif
                                </p>
                            </div>

                            <i
                                data-lucide="chevron-down"
                                class="h-4 w-4 text-blue-200 transition"
                                :class="{ 'rotate-180': accountOpen }"
                            ></i>
                        </button>

                        <div
                            x-show="accountOpen"
                            x-cloak
                            x-transition
                            class="absolute right-0 mt-3 w-64 overflow-hidden rounded-2xl border border-slate-200 bg-white p-2 text-slate-900 shadow-2xl"
                        >
                            <div class="rounded-xl bg-blue-50 p-4">
                                <p class="truncate text-xs font-black text-slate-900">
                                    {{ $currentUser->name }}
                                </p>

                                <p class="mt-1 truncate text-[10px] font-medium text-slate-500">
                                    {{ $currentUser->email }}
                                </p>

                                @if (
                                    ! $currentUser->isAdmin()
                                    && $currentUser->hasActiveMembership()
                                )
                                    <span class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1.5 text-[9px] font-black text-emerald-700">
                                        <i
                                            data-lucide="badge-check"
                                            class="h-3.5 w-3.5"
                                        ></i>

                                        Membership Aktif
                                    </span>
                                @endif
                            </div>

                            <div class="mt-2 space-y-1">
                                <a
                                    href="{{ route($dashboardRoute) }}"
                                    class="flex items-center gap-3 rounded-xl px-3 py-3 text-xs font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700"
                                >
                                    <i
                                        data-lucide="{{ $dashboardIcon }}"
                                        class="h-4 w-4"
                                    ></i>

                                    {{ $dashboardLabel }}
                                </a>

                                @if (
                                    ! $currentUser->isAdmin()
                                    && $currentUser->hasActiveMembership()
                                )
                                    <a
                                        href="{{ route('member.dashboard') }}"
                                        class="flex items-center gap-3 rounded-xl bg-cyan-50 px-3 py-3 text-xs font-black text-cyan-800 transition hover:bg-cyan-100"
                                    >
                                        <i
                                            data-lucide="circle-play"
                                            class="h-4 w-4"
                                        ></i>

                                        Kelas Saya
                                    </a>
                                @endif

                                <a
                                    href="{{ route('profile.edit') }}"
                                    class="flex items-center gap-3 rounded-xl px-3 py-3 text-xs font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700"
                                >
                                    <i
                                        data-lucide="user-round-cog"
                                        class="h-4 w-4"
                                    ></i>

                                    Pengaturan Profil
                                </a>
                            </div>

                            <div class="mt-2 border-t border-slate-100 pt-2">
                                <form
                                    method="POST"
                                    action="{{ route('logout') }}"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-xs font-black text-red-600 transition hover:bg-red-50"
                                    >
                                        <i
                                            data-lucide="log-out"
                                            class="h-4 w-4"
                                        ></i>

                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>

            {{-- MOBILE BUTTON --}}
            <button
                type="button"
                @click="mobileOpen = !mobileOpen"
                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-white/15 bg-white/10 text-white transition hover:bg-white/15 lg:hidden"
                aria-label="Buka menu navigasi"
            >
                <i
                    x-show="!mobileOpen"
                    data-lucide="menu"
                    class="h-5 w-5"
                ></i>

                <i
                    x-show="mobileOpen"
                    x-cloak
                    data-lucide="x"
                    class="h-5 w-5"
                ></i>
            </button>
        </div>

        {{-- MOBILE MENU --}}
        <div
            x-show="mobileOpen"
            x-cloak
            x-transition
            class="border-t border-white/10 pb-5 pt-4 lg:hidden"
        >
            <div class="grid gap-2 sm:grid-cols-2">
                @foreach ($menus as $menu)
                    @php
                        $isActive = request()->routeIs(...$menu['patterns']);
                    @endphp

                    <a
                        href="{{ route($menu['route']) }}"
                        @click="mobileOpen = false"
                        @class([
                            'flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-black transition',
                            'bg-white text-blue-950 shadow-lg' => $isActive,
                            'bg-white/[0.06] text-blue-100 hover:bg-white/10 hover:text-white' => ! $isActive,
                        ])
                    >
                        <span
                            @class([
                                'flex h-9 w-9 items-center justify-center rounded-xl',
                                'bg-blue-50 text-blue-700' => $isActive,
                                'bg-white/10 text-blue-200' => ! $isActive,
                            ])
                        >
                            <i
                                data-lucide="{{ $menu['icon'] }}"
                                class="h-4 w-4"
                            ></i>
                        </span>

                        {{ $menu['label'] }}
                    </a>
                @endforeach
            </div>

            <div class="mt-4 border-t border-white/10 pt-4">
                @guest
                    <a
                        href="{{ route('login') }}"
                        class="flex w-full items-center justify-center gap-2 rounded-2xl bg-cyan-300 px-5 py-4 text-sm font-black text-blue-950"
                    >
                        <i
                            data-lucide="log-in"
                            class="h-5 w-5"
                        ></i>

                        Login ke Area Member
                    </a>

                    <p class="mt-3 text-center text-[10px] font-medium leading-5 text-blue-200">
                        Akun member dibuat langsung oleh admin HilmiDev.
                    </p>
                @else
                    {{-- TOMBOL AREA MEMBER DI MOBILE --}}
                    <a
                        href="{{ route($dashboardRoute) }}"
                        @click="mobileOpen = false"
                        class="flex w-full items-center justify-center gap-2 rounded-2xl bg-cyan-300 px-5 py-4 text-sm font-black text-blue-950 shadow-lg"
                    >
                        <i
                            data-lucide="{{ $dashboardIcon }}"
                            class="h-5 w-5"
                        ></i>

                        {{ $dashboardLabel }}
                    </a>

                    @if (
                        ! $currentUser->isAdmin()
                        && $currentUser->hasActiveMembership()
                    )
                        <a
                            href="{{ route('member.dashboard') }}"
                            @click="mobileOpen = false"
                            class="mt-2 flex w-full items-center justify-center gap-2 rounded-2xl border border-white/15 bg-white/10 px-5 py-4 text-sm font-black text-white"
                        >
                            <i
                                data-lucide="circle-play"
                                class="h-5 w-5"
                            ></i>

                            Buka Kelas Saya
                        </a>
                    @endif

                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <a
                            href="{{ route('profile.edit') }}"
                            class="flex items-center justify-center gap-2 rounded-2xl border border-white/10 bg-white/[0.06] px-4 py-3 text-xs font-black text-blue-100"
                        >
                            <i
                                data-lucide="user-round-cog"
                                class="h-4 w-4"
                            ></i>

                            Profil
                        </a>

                        <form
                            method="POST"
                            action="{{ route('logout') }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="flex w-full items-center justify-center gap-2 rounded-2xl bg-red-500/15 px-4 py-3 text-xs font-black text-red-100"
                            >
                                <i
                                    data-lucide="log-out"
                                    class="h-4 w-4"
                                ></i>

                                Keluar
                            </button>
                        </form>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
