<footer class="relative overflow-hidden bg-gradient-to-br from-blue-950 via-blue-900 to-blue-700 text-white">

    {{-- DEKORASI --}}
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-blue-400/10 blur-3xl"></div>
        <div class="absolute -bottom-40 right-0 h-[28rem] w-[28rem] rounded-full bg-cyan-300/10 blur-3xl"></div>

        <div
            class="absolute inset-0 opacity-[0.035]"
            style="background-image:
                linear-gradient(rgba(255,255,255,.8) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.8) 1px, transparent 1px);
                background-size: 52px 52px;">
        </div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- FOOTER TOP --}}
        <div class="grid gap-12 py-16 md:grid-cols-2 lg:grid-cols-12 lg:py-20">

            {{-- BRAND --}}
            <div class="lg:col-span-5">
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-4">

                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-white/15 bg-white/10 text-white shadow-xl backdrop-blur">
                        <i data-lucide="code-2" class="h-7 w-7"></i>
                    </div>

                    <div>
                        <h2 class="text-2xl font-black tracking-tight text-white">
                            HilmiDev
                        </h2>

                        <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.2em] text-blue-200">
                            Digital Marketplace
                        </p>
                    </div>
                </a>

                <p class="mt-7 max-w-md text-sm font-medium leading-7 text-blue-100/75">
                    Marketplace source code premium dan layanan pembuatan
                    website serta aplikasi custom untuk bisnis, sekolah,
                    koperasi, pesantren, UMKM, dan instansi.
                </p>

                <div class="mt-7 flex flex-wrap gap-3">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/[0.06] px-4 py-2.5 text-xs font-bold text-blue-100">
                        <i data-lucide="shield-check" class="h-4 w-4"></i>
                        Source Code Premium
                    </span>

                    <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/[0.06] px-4 py-2.5 text-xs font-bold text-blue-100">
                        <i data-lucide="headphones" class="h-4 w-4"></i>
                        Support Teknis
                    </span>
                </div>
            </div>

            {{-- PRODUK --}}
            <div class="lg:col-span-2">
                <h3 class="text-sm font-black uppercase tracking-[0.12em] text-white">
                    Produk
                </h3>

                <div class="mt-6 space-y-4">
                    <a href="{{ route('home') }}"
                       class="group flex items-center gap-2 text-sm font-medium text-blue-100/70 transition hover:text-white">
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-300 transition group-hover:w-3"></span>
                        Source Code
                    </a>

                    <a href="{{ route('products.index') }}"
                       class="group flex items-center gap-2 text-sm font-medium text-blue-100/70 transition hover:text-white">
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-300 transition group-hover:w-3"></span>
                        Semua Produk
                    </a>

                    <a href="{{ route('courses.index') }}"
                       class="group flex items-center gap-2 text-sm font-medium text-blue-100/70 transition hover:text-white">
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-300 transition group-hover:w-3"></span>
                        Kelas Coding
                    </a>
                    <a href="{{ route('services.index') }}"
                       class="group flex items-center gap-2 text-sm font-medium text-blue-100/70 transition hover:text-white">
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-300 transition group-hover:w-3"></span>
                        Jasa Website
                    </a>
                </div>
            </div>

            {{-- AKUN --}}
            <div class="lg:col-span-2">
                <h3 class="text-sm font-black uppercase tracking-[0.12em] text-white">
                    Akun
                </h3>

                <div class="mt-6 space-y-4">
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                               class="group flex items-center gap-2 text-sm font-medium text-blue-100/70 transition hover:text-white">
                                <span class="h-1.5 w-1.5 rounded-full bg-blue-300 transition group-hover:w-3"></span>
                                Dashboard Admin
                            </a>
                        @else
                            <a href="{{ route('client.dashboard') }}"
                               class="group flex items-center gap-2 text-sm font-medium text-blue-100/70 transition hover:text-white">
                                <span class="h-1.5 w-1.5 rounded-full bg-blue-300 transition group-hover:w-3"></span>
                                Dashboard Client
                            </a>

                            <a href="{{ route('orders.index') }}"
                               class="group flex items-center gap-2 text-sm font-medium text-blue-100/70 transition hover:text-white">
                                <span class="h-1.5 w-1.5 rounded-full bg-blue-300 transition group-hover:w-3"></span>
                                Pesanan Saya
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="group flex items-center gap-2 text-sm font-medium text-blue-100/70 transition hover:text-white">
                            <span class="h-1.5 w-1.5 rounded-full bg-blue-300 transition group-hover:w-3"></span>
                            Masuk
                        </a>

                        <a href="{{ route('courses.index') }}"
                           class="group flex items-center gap-2 text-sm font-medium text-blue-100/70 transition hover:text-white">
                            <span class="h-1.5 w-1.5 rounded-full bg-blue-300 transition group-hover:w-3"></span>
                            Info Member
                        </a>
                    @endauth
                </div>
            </div>

            {{-- INFORMASI --}}
            <div class="lg:col-span-3">
                <h3 class="text-sm font-black uppercase tracking-[0.12em] text-white">
                    Mulai Project
                </h3>

                <p class="mt-6 text-sm font-medium leading-7 text-blue-100/70">
                    Konsultasikan website atau aplikasi yang dibutuhkan bersama tim HilmiDev.
                </p>

                <a href="{{ route('services.index') }}"
                   class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl bg-white px-5 py-3.5 text-xs font-black text-blue-900 shadow-xl shadow-blue-950/20 transition duration-300 hover:-translate-y-0.5 hover:bg-blue-50">
                    Konsultasi Project
                    <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                </a>
            </div>
        </div>

        {{-- FOOTER BOTTOM --}}
        <div class="flex flex-col gap-4 border-t border-white/10 py-7 sm:flex-row sm:items-center sm:justify-between">

            <p class="text-xs font-medium text-blue-100/60">
                © {{ date('Y') }} HilmiDev. Seluruh hak cipta dilindungi.
            </p>

            <div class="flex flex-wrap items-center gap-5">
                <span class="inline-flex items-center gap-2 text-xs font-medium text-blue-100/60">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-400"></span>
                    </span>

                    Sistem berjalan normal
                </span>

                <span class="inline-flex items-center gap-2 text-xs font-medium text-blue-100/60">
                    <i data-lucide="heart" class="h-3.5 w-3.5"></i>
                    Dibuat dengan Laravel
                </span>
            </div>
        </div>
    </div>
</footer>
