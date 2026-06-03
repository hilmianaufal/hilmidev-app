<x-app-layout>
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-cyan-500 to-blue-400"></div>
        <div class="absolute -top-24 -right-24 w-72 h-72 bg-white/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 -left-24 w-72 h-72 bg-cyan-200/40 rounded-full blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 py-16 md:py-24 text-white">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 bg-white/15 border border-white/20 backdrop-blur-xl px-4 py-2 rounded-full mb-6">
                    <i data-lucide="briefcase" class="w-4 h-4"></i>
                    <span class="text-sm font-semibold">HilmiDev Premium Services</span>
                </div>

                <h1 class="text-4xl md:text-6xl font-black leading-tight">
                    Jasa Pembuatan Website & Aplikasi Premium
                </h1>

                <p class="text-blue-50 text-lg md:text-xl mt-6 leading-relaxed">
                    Bangun website bisnis, toko online, dashboard admin, aplikasi custom, dan sistem digital profesional.
                </p>

                <div class="flex flex-col sm:flex-row gap-3 mt-8">
                    <a href="#services"
                       class="inline-flex items-center justify-center gap-2 bg-white text-blue-700 px-6 py-4 rounded-2xl font-bold shadow-2xl shadow-blue-900/20">
                        <i data-lucide="sparkles" class="w-5 h-5"></i>
                        Lihat Layanan
                    </a>

                    <a href="https://wa.me/6281234567890"
                       target="_blank"
                       class="inline-flex items-center justify-center gap-2 bg-blue-900/20 border border-white/30 backdrop-blur-xl px-6 py-4 rounded-2xl font-bold">
                        <i data-lucide="message-circle" class="w-5 h-5"></i>
                        Konsultasi Gratis
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-12">
                <div class="bg-white/15 border border-white/20 backdrop-blur-xl rounded-3xl p-5">
                    <i data-lucide="smartphone" class="w-7 h-7 mb-3"></i>
                    <p class="font-black text-2xl">Mobile</p>
                    <p class="text-sm text-blue-50">First Design</p>
                </div>

                <div class="bg-white/15 border border-white/20 backdrop-blur-xl rounded-3xl p-5">
                    <i data-lucide="shield-check" class="w-7 h-7 mb-3"></i>
                    <p class="font-black text-2xl">Secure</p>
                    <p class="text-sm text-blue-50">Clean Code</p>
                </div>

                <div class="bg-white/15 border border-white/20 backdrop-blur-xl rounded-3xl p-5">
                    <i data-lucide="zap" class="w-7 h-7 mb-3"></i>
                    <p class="font-black text-2xl">Fast</p>
                    <p class="text-sm text-blue-50">Performance</p>
                </div>

                <div class="bg-white/15 border border-white/20 backdrop-blur-xl rounded-3xl p-5">
                    <i data-lucide="headphones" class="w-7 h-7 mb-3"></i>
                    <p class="font-black text-2xl">Support</p>
                    <p class="text-sm text-blue-50">After Sales</p>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="max-w-7xl mx-auto px-4 py-12">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
            <div>
                <div class="inline-flex items-center gap-2 text-blue-600 font-bold mb-2">
                    <i data-lucide="briefcase-business" class="w-5 h-5"></i>
                    Layanan HilmiDev
                </div>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900">
                    Pilih Jasa Sesuai Kebutuhan
                </h2>
                <p class="text-slate-500 mt-2">
                    Dari company profile sampai sistem custom enterprise.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse ($services as $service)
                <a href="{{ route('services.show', $service) }}"
                   class="group bg-white border border-blue-100 rounded-[2rem] overflow-hidden shadow-xl shadow-blue-500/5 hover:shadow-2xl hover:shadow-blue-500/20 transition duration-300">
                    <div class="relative aspect-video bg-gradient-to-br from-blue-100 to-cyan-100 overflow-hidden">
                        @if ($service->thumbnail)
                            <img src="{{ asset('storage/' . $service->thumbnail) }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                                 alt="{{ $service->title }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-6xl">
                                {{ $service->icon ?? '💼' }}
                            </div>
                        @endif

                        @if ($service->is_featured)
                            <div class="absolute top-4 right-4">
                                <span class="inline-flex items-center gap-1 bg-amber-400 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                    <i data-lucide="star" class="w-3 h-3"></i>
                                    Featured
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="p-6">
                        <h3 class="text-xl font-black text-slate-900 group-hover:text-blue-600 transition">
                            {{ $service->title }}
                        </h3>

                        <p class="text-sm text-slate-500 line-clamp-2 mt-3">
                            {{ $service->short_description }}
                        </p>

                        @if ($service->features)
                            <div class="flex flex-wrap gap-2 mt-4">
                                @foreach (array_slice($service->features, 0, 2) as $feature)
                                    <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                        {{ $feature }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex items-end justify-between mt-6 pt-5 border-t border-blue-50">
                            <div>
                                <p class="text-sm text-slate-400">Mulai dari</p>
                                <p class="text-2xl font-black text-blue-600">
                                    Rp {{ number_format($service->starting_price, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center group-hover:scale-110 transition shadow-lg shadow-blue-500/30">
                                <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full bg-white rounded-[2rem] border border-blue-100 p-12 text-center shadow-xl shadow-blue-500/5">
                    <div class="w-16 h-16 mx-auto rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                        <i data-lucide="briefcase" class="w-8 h-8"></i>
                    </div>
                    <p class="font-bold text-slate-800">Belum ada jasa tersedia.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $services->links() }}
        </div>
    </section>
</x-app-layout>