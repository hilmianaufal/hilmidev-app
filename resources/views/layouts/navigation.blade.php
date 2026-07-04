<nav x-data="{ open: false, userDropdown: false, adminDropdown: false }"
     class="sticky top-0 z-50 border-b border-blue-100/70 bg-white/80 backdrop-blur-2xl shadow-sm shadow-blue-500/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20 gap-6">
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
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

            <div class="hidden lg:flex items-center justify-center gap-2 flex-1">
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
                    <a href="{{ route('client.dashboard') }}"
                       class="flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-bold {{ request()->routeIs('client.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                        <i data-lucide="gauge" class="w-4 h-4"></i>
                        Client
                    </a>
                @endauth
            </div>

            <div class="hidden lg:flex items-center gap-3 shrink-0">
                @auth
                    @if (auth()->user()->isAdmin())
                        <div class="relative" @click.outside="adminDropdown = false">
                            <button type="button"
                                    @click="adminDropdown = ! adminDropdown"
                                    class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-slate-950 text-white font-black text-sm shadow-xl shadow-blue-500/20">
                                <i data-lucide="shield-check" class="w-4 h-4"></i>
                                Admin
                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            </button>

                            <div x-show="adminDropdown"
                                 x-transition
                                 x-cloak
                                 class="absolute right-0 mt-3 w-72 rounded-3xl bg-white border border-blue-100 shadow-2xl shadow-blue-500/20 p-3 z-50">
                                <a href="{{ route('admin.dashboard') }}"
                                   class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                                    Dashboard Admin
                                </a>

                                <a href="{{ route('admin.products.index') }}"
                                   class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold {{ request()->routeIs('admin.products.*') ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                                    <i data-lucide="package" class="w-5 h-5"></i>
                                    Produk
                                </a>

                                <a href="{{ route('admin.services.index') }}"
                                   class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold {{ request()->routeIs('admin.services.*') ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                                    <i data-lucide="briefcase-business" class="w-5 h-5"></i>
                                    Jasa Website
                                </a>

                                <a href="{{ route('admin.orders.index') }}"
                                   class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold {{ request()->routeIs('admin.orders.*') ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                                    Order
                                </a>

                                <a href="{{ route('admin.project-requests.index') }}"
                                   class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold {{ request()->routeIs('admin.project-requests.*') ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                                    <i data-lucide="folder-kanban" class="w-5 h-5"></i>
                                    Project Request
                                </a>

                                <a href="{{ route('admin.portfolios.index') }}"
                                   class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold {{ request()->routeIs('admin.portfolios.*') ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                                    <i data-lucide="images" class="w-5 h-5"></i>
                                    Portfolio
                                </a>

                                <a href="{{ route('admin.testimonials.index') }}"
                                   class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold {{ request()->routeIs('admin.testimonials.*') ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                                    <i data-lucide="message-square-heart" class="w-5 h-5"></i>
                                    Testimonial
                                </a>

                                <a href="{{ route('blog.index') }}"
                                    class="flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-bold {{ request()->routeIs('blog.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                                        <i data-lucide="newspaper" class="w-4 h-4"></i>
                                        Blog
                                    </a>
                            </div>
                        </div>
                    @endif

                    <div class="relative" @click.outside="userDropdown = false">
                        <button type="button"
                                @click="userDropdown = ! userDropdown"
                                class="inline-flex items-center gap-3 pl-2 pr-4 py-2 rounded-2xl bg-blue-50 text-blue-700 font-black text-sm border border-blue-100">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-400 text-white flex items-center justify-center">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </button>

                        <div x-show="userDropdown"
                             x-transition
                             x-cloak
                             class="absolute right-0 mt-3 w-64 rounded-3xl bg-white border border-blue-100 shadow-2xl shadow-blue-500/20 p-3 z-50">
                            <a href="{{ route('orders.index') }}"
                               class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold {{ request()->routeIs('orders.*') ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                                <i data-lucide="receipt-text" class="w-5 h-5"></i>
                                Order Saya
                            </a>

                            <a href="{{ route('project-requests.index') }}"
                               class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold {{ request()->routeIs('project-requests.*') ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                                <i data-lucide="folder-kanban" class="w-5 h-5"></i>
                                Project Saya
                            </a>

                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold text-slate-600 hover:bg-blue-50 hover:text-blue-600">
                                <i data-lucide="user" class="w-5 h-5"></i>
                                Profile
                            </a>

                            <div class="my-2 border-t border-blue-50"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl font-bold text-red-600 hover:bg-red-50">
                                    <i data-lucide="log-out" class="w-5 h-5"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
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
                <a href="{{ route('client.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold {{ request()->routeIs('client.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-600 bg-blue-50' }}">
                    <i data-lucide="gauge" class="w-5 h-5"></i>
                    Client Dashboard
                </a>

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

                <a href="{{ route('blog.index') }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-2xl text-sm font-bold {{ request()->routeIs('blog.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}">
                        <i data-lucide="newspaper" class="w-4 h-4"></i>
                        Blog
                    </a>

                @if (auth()->user()->isAdmin())
                    <div class="pt-3 mt-3 border-t border-blue-100">
                        <p class="px-4 pb-2 text-xs font-black text-blue-600 uppercase tracking-widest">
                            Admin Panel
                        </p>

                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-600 bg-blue-50' }}">
                            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                            Dashboard Admin
                        </a>

                        <a href="{{ route('admin.products.index') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold text-slate-600 bg-blue-50 mt-2">
                            <i data-lucide="package" class="w-5 h-5"></i>
                            Produk
                        </a>

                        <a href="{{ route('admin.services.index') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold text-slate-600 bg-blue-50 mt-2">
                            <i data-lucide="briefcase-business" class="w-5 h-5"></i>
                            Jasa Website
                        </a>
                    </div>
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