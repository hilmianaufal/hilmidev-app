<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HilmiDev') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>
    <script src="https://unpkg.com/lucide@latest" defer></script>
</head>

<body class="font-[Plus_Jakarta_Sans] antialiased bg-white text-slate-900 overflow-x-hidden">
    <div class="min-h-screen bg-white">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white border-b border-blue-100">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main>
            {{ $slot }}
        </main>

        <footer class="relative overflow-hidden bg-slate-950 text-white">
            <div class="absolute -top-32 -right-32 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 -left-32 w-96 h-96 bg-cyan-400/10 rounded-full blur-3xl"></div>

            <div class="relative max-w-7xl mx-auto px-4 py-14">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                    <div class="md:col-span-2">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-400 flex items-center justify-center shadow-lg shadow-blue-500/30">
                                <i data-lucide="code-2" class="w-6 h-6 text-white"></i>
                            </div>

                            <div>
                                <h2 class="text-2xl font-black">
                                    Hilmi<span class="text-blue-400">Dev</span>
                                </h2>
                                <p class="text-xs text-slate-400">Premium Web Studio</p>
                            </div>
                        </div>

                        <p class="text-slate-400 max-w-xl leading-relaxed">
                            HilmiDev menyediakan source code premium, jasa pembuatan website,
                            aplikasi custom, dashboard admin, dan sistem digital modern yang mobile-first.
                        </p>

                        <div class="flex gap-3 mt-6">
                            <a href="https://wa.me/6281234567890"
                               target="_blank"
                               class="w-11 h-11 rounded-2xl bg-white/10 hover:bg-blue-600 flex items-center justify-center transition">
                                <i data-lucide="message-circle" class="w-5 h-5"></i>
                            </a>

                            <a href="#"
                               class="w-11 h-11 rounded-2xl bg-white/10 hover:bg-blue-600 flex items-center justify-center transition">
                                <i data-lucide="instagram" class="w-5 h-5"></i>
                            </a>

                            <a href="#"
                               class="w-11 h-11 rounded-2xl bg-white/10 hover:bg-blue-600 flex items-center justify-center transition">
                                <i data-lucide="github" class="w-5 h-5"></i>
                            </a>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-black mb-5">Navigasi</h3>

                        <div class="space-y-3 text-slate-400">
                            <a href="{{ route('home') }}" class="block hover:text-white">Home</a>
                            <a href="{{ route('products.index') }}" class="block hover:text-white">Source Code</a>
                            <a href="{{ route('services.index') }}" class="block hover:text-white">Jasa Website</a>

                            @auth
                                <a href="{{ route('orders.index') }}" class="block hover:text-white">Order Saya</a>
                                <a href="{{ route('project-requests.index') }}" class="block hover:text-white">Project Saya</a>
                            @endauth
                        </div>
                    </div>

                    <div>
                        <h3 class="font-black mb-5">Layanan</h3>

                        <div class="space-y-3 text-slate-400">
                            <p>Website Company Profile</p>
                            <p>Toko Online</p>
                            <p>Dashboard Admin</p>
                            <p>Custom Web App</p>
                            <p>Maintenance Website</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-white/10 mt-12 pt-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <p class="text-sm text-slate-500">
                        © {{ date('Y') }} HilmiDev. All rights reserved.
                    </p>

                    <div class="flex gap-4 text-sm text-slate-500">
                        <span>Mobile First</span>
                        <span>•</span>
                        <span>Premium UI</span>
                        <span>•</span>
                        <span>Laravel Powered</span>
                    </div>
                </div>
            </div>
        </footer>
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