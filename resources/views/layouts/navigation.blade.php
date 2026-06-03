<nav x-data="{ open: false }"
     class="sticky top-0 z-50 border-b border-blue-100/70 bg-white/80 backdrop-blur-2xl shadow-sm shadow-blue-500/5">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between h-20">
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-400 flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <i data-lucide="code-2" class="w-6 h-6 text-white"></i>
                    </div>

                    <div>
                        <h1 class="text-xl font-black tracking-tight">
                            Hilmi<span class="text-blue-600">Dev</span>
                        </h1>
                        <p class="text-xs text-slate-400 -mt-1">Premium Web Studio</p>
                    </div>
                </a>

                <div class="hidden lg:flex items-center gap-2">
                    <a href="{{ route('home') }}"
                       class="flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-bold {{ request()->routeIs('home') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                        <i data-lucide="home" class="w-4 h-4"></i>
                        Home
                    </a>

                    <a href="{{ route('products.index') }}"
                       class="flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-bold {{ request()->routeIs('products.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                        <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                        Source Code
                    </a>

                    <a href="{{ route('services.index') }}"
                       class="flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-bold {{ request()->routeIs('services.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                        <i data-lucide="briefcase" class="w-4 h-4"></i>
                        Jasa Website
                    </a>

                    @auth
                        <a href="{{ route('orders.index') }}"
                           class="flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-bold {{ request()->routeIs('orders.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                            <i data-lucide="receipt-text" class="w-4 h-4"></i>
                            Order Saya
                        </a>

                        <a href="{{ route('project-requests.index') }}"
                           class="flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-bold {{ request()->routeIs('project-requests.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                            <i data-lucide="folder-kanban" class="w-4 h-4"></i>
                            Project Saya
                        </a>

                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                               class="flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-bold {{ request()->routeIs('admin.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                Admin
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden lg:flex items-center gap-3">
                @auth
                    <div class="px-4 py-2 rounded-2xl bg-blue-50 text-blue-700 font-bold text-sm">
                        {{ Auth::user()->name }}
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-11 h-11 rounded-2xl bg-white border border-blue-100 text-slate-500 hover:text-red-600 hover:bg-red-50 flex items-center justify-center transition">
                            <i data-lucide="log-out" class="w-5 h-5"></i>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="px-5 py-3 rounded-2xl font-bold text-slate-600 hover:bg-blue-50 hover:text-blue-600 transition">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                       class="px-5 py-3 rounded-2xl bg-blue-600 text-white font-black shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition">
                        Register
                    </a>
                @endauth
            </div>

            <button type="button"
                    @click="open = ! open"
                    class="lg:hidden w-11 h-11 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/30">
                <i x-show="! open" data-lucide="menu" class="w-6 h-6"></i>
                <i x-show="open" data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
    </div>

    <div x-show="open"
         x-transition
         x-cloak
         class="lg:hidden px-4 pb-5">
        <div class="bg-white rounded-3xl border border-blue-100 shadow-2xl shadow-blue-500/10 p-3 space-y-2">
            <a href="{{ route('home') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold {{ request()->routeIs('home') ? 'bg-blue-600 text-white' : 'text-slate-600 bg-blue-50' }}">
                <i data-lucide="home" class="w-5 h-5"></i>
                Home
            </a>

            <a href="{{ route('products.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold {{ request()->routeIs('products.*') ? 'bg-blue-600 text-white' : 'text-slate-600 bg-blue-50' }}">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                Source Code
            </a>

            <a href="{{ route('services.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold {{ request()->routeIs('services.*') ? 'bg-blue-600 text-white' : 'text-slate-600 bg-blue-50' }}">
                <i data-lucide="briefcase" class="w-5 h-5"></i>
                Jasa Website
            </a>

            @auth
                <a href="{{ route('orders.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold {{ request()->routeIs('orders.*') ? 'bg-blue-600 text-white' : 'text-slate-600 bg-blue-50' }}">
                    <i data-lucide="receipt-text" class="w-5 h-5"></i>
                    Order Saya
                </a>

                <a href="{{ route('project-requests.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold {{ request()->routeIs('project-requests.*') ? 'bg-blue-600 text-white' : 'text-slate-600 bg-blue-50' }}">
                    <i data-lucide="folder-kanban" class="w-5 h-5"></i>
                    Project Saya
                </a>

                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold {{ request()->routeIs('admin.*') ? 'bg-blue-600 text-white' : 'text-slate-600 bg-blue-50' }}">
                        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                        Admin
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl font-bold text-red-600 bg-red-50">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold text-slate-600 bg-blue-50">
                    <i data-lucide="log-in" class="w-5 h-5"></i>
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold text-white bg-blue-600">
                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                    Register
                </a>
            @endauth
        </div>
    </div>
</nav>