<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>HilmiDev Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-50">

<div x-data="{ sidebarOpen: false }" class="min-h-screen">

    <!-- Mobile Overlay -->
    <div x-show="sidebarOpen"
         x-transition
         class="fixed inset-0 bg-black/50 z-40 lg:hidden"
         @click="sidebarOpen = false">
    </div>

    <!-- Sidebar -->
    <aside
        class="fixed top-0 left-0 z-50 h-full w-72 bg-white border-r border-blue-100 shadow-xl transform transition lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

        <div class="h-20 flex items-center px-6 border-b border-blue-100">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-400 flex items-center justify-center text-white shadow-lg">
                <i data-lucide="code-2" class="w-6 h-6"></i>
            </div>

            <div class="ml-3">
                <h2 class="font-black text-xl">
                    Hilmi<span class="text-blue-600">Dev</span>
                </h2>

                <p class="text-xs text-slate-400">
                    Admin Panel
                </p>
            </div>
        </div>

        <nav class="p-4 space-y-2">

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-blue-50 text-blue-700 font-bold">
                <i data-lucide="layout-dashboard"></i>
                Dashboard
            </a>

            <a href="{{ route('admin.products.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-blue-50 text-slate-700 font-semibold">
                <i data-lucide="package"></i>
                Produk
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-blue-50 text-slate-700 font-semibold">
                <i data-lucide="grid-3x3"></i>
                Kategori
            </a>

            <a href="{{ route('admin.orders.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('admin.orders.*') ? 'bg-blue-600 text-white' : 'hover:bg-blue-50 text-slate-700' }} font-bold">
                <i data-lucide="shopping-cart" class="w-5 h-5"></i> Order
            </a>

            <a href="{{ route('admin.clients.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('admin.clients.*') ? 'bg-blue-600 text-white' : 'hover:bg-blue-50 text-slate-700' }} font-bold">
                <i data-lucide="users" class="w-5 h-5"></i>
                Clients
            </a>
            <a href="{{ route('admin.payments.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('admin.payments.*') ? 'bg-blue-600 text-white' : 'hover:bg-blue-50 text-slate-700' }} font-bold">
                <i data-lucide="credit-card" class="w-5 h-5"></i>
                Pembayaran
            </a>

            <a href="{{ route('admin.services.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('admin.services.*') ? 'bg-blue-600 text-white' : 'hover:bg-blue-50 text-slate-700' }} font-bold">
                    <i data-lucide="briefcase" class="w-5 h-5"></i>
                    Jasa Website
                </a>

            <a href="{{ route('admin.project-requests.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('admin.project-requests.*') ? 'bg-blue-600 text-white' : 'hover:bg-blue-50 text-slate-700' }} font-bold">
                <i data-lucide="folder-kanban" class="w-5 h-5"></i>
                Project Request
            </a> 

            <a href="{{ route('admin.portfolios.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('admin.portfolios.*') ? 'bg-blue-600 text-white' : 'hover:bg-blue-50 text-slate-700' }} font-bold">
                    <i data-lucide="images" class="w-5 h-5"></i>
                    Portfolio
                </a>
            <a href="{{ route('admin.posts.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('admin.posts.*') ? 'bg-blue-600 text-white' : 'hover:bg-blue-50 text-slate-700' }} font-bold">
                <i data-lucide="newspaper" class="w-5 h-5"></i>
                Blog 
            </a>
            <a href="{{ route('admin.testimonials.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('admin.testimonials.*') ? 'bg-blue-600 text-white' : 'hover:bg-blue-50 text-slate-700' }} font-bold">
                    <i data-lucide="message-square-heart" class="w-5 h-5"></i>
                    Testimonial
                </a>
        </nav>

    </aside>

    <!-- Content -->
    <div class="lg:pl-72">

        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-blue-100">
            <div class="h-20 flex items-center justify-between px-6">

                <button
                    class="lg:hidden w-11 h-11 rounded-2xl bg-blue-600 text-white"
                    @click="sidebarOpen = true">
                    ☰
                </button>

                <div>
                    <h1 class="font-black text-xl">
                        HilmiDev Premium
                    </h1>
                </div>

                <div class="flex items-center gap-3">

                    <div class="px-4 py-2 rounded-2xl bg-blue-50">
                        {{ auth()->user()->name }}
                    </div>

                </div>

            </div>
        </header>

        <main>
            {{ $slot }}
        </main>

    </div>

</div>

<script>
    lucide.createIcons();
</script>

</body>
</html>