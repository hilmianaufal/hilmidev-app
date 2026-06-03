<x-app-layout>
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-cyan-500 to-blue-400"></div>
        <div class="absolute -top-24 -right-24 w-72 h-72 bg-white/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 -left-24 w-72 h-72 bg-cyan-200/40 rounded-full blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 py-16 md:py-24 text-white">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 bg-white/15 border border-white/20 backdrop-blur-xl px-4 py-2 rounded-full mb-6">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                    <span class="text-sm font-semibold">Premium Source Code Marketplace</span>
                </div>

                <h1 class="text-4xl md:text-6xl font-black leading-tight">
                    Source Code Premium Siap Pakai untuk Bisnis Digital
                </h1>

                <p class="text-blue-50 text-lg md:text-xl mt-6 leading-relaxed">
                    Temukan aplikasi web, dashboard admin, sistem bisnis, dan template modern dengan desain mobile-first.
                </p>

                <div class="flex flex-col sm:flex-row gap-3 mt-8">
                    <a href="#products"
                       class="inline-flex items-center justify-center gap-2 bg-white text-blue-700 px-6 py-4 rounded-2xl font-bold shadow-2xl shadow-blue-900/20">
                        <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                        Jelajahi Produk
                    </a>

                    <a href="#categories"
                       class="inline-flex items-center justify-center gap-2 bg-blue-900/20 border border-white/30 backdrop-blur-xl px-6 py-4 rounded-2xl font-bold">
                        <i data-lucide="grid-3x3" class="w-5 h-5"></i>
                        Lihat Kategori
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-12">
                <div class="bg-white/15 border border-white/20 backdrop-blur-xl rounded-3xl p-5">
                    <i data-lucide="shield-check" class="w-7 h-7 mb-3"></i>
                    <p class="font-black text-2xl">100%</p>
                    <p class="text-sm text-blue-50">Siap Deploy</p>
                </div>

                <div class="bg-white/15 border border-white/20 backdrop-blur-xl rounded-3xl p-5">
                    <i data-lucide="smartphone" class="w-7 h-7 mb-3"></i>
                    <p class="font-black text-2xl">Mobile</p>
                    <p class="text-sm text-blue-50">First Design</p>
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

    <section id="categories" class="max-w-7xl mx-auto px-4 -mt-8 relative z-10">
        <div class="bg-white/90 backdrop-blur-2xl border border-blue-100 rounded-[2rem] shadow-2xl shadow-blue-500/10 p-4">
            <div class="flex gap-3 overflow-x-auto pb-2">
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl text-sm font-bold whitespace-nowrap {{ request('category') ? 'bg-blue-50 text-blue-700' : 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' }}">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                    Semua
                </a>

                @foreach ($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                       class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl text-sm font-bold whitespace-nowrap {{ request('category') === $category->slug ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                        <span>{{ $category->icon ?? '📦' }}</span>
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section id="products" class="max-w-7xl mx-auto px-4 py-12">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
            <div>
                <div class="inline-flex items-center gap-2 text-blue-600 font-bold mb-2">
                    <i data-lucide="package-search" class="w-5 h-5"></i>
                    Marketplace
                </div>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900">
                    Produk Source Code
                </h2>
                <p class="text-slate-500 mt-2">
                    Pilih source code premium sesuai kebutuhan project kamu.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse ($products as $product)
                <a href="{{ route('products.show', $product) }}"
                   class="group bg-white border border-blue-100 rounded-[2rem] overflow-hidden shadow-xl shadow-blue-500/5 hover:shadow-2xl hover:shadow-blue-500/20 transition duration-300">

                    <div class="relative aspect-video bg-gradient-to-br from-blue-100 to-cyan-100 overflow-hidden">
                        @if ($product->thumbnail)
                            <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                                 alt="{{ $product->name }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-blue-400">
                                <i data-lucide="image" class="w-12 h-12"></i>
                            </div>
                        @endif

                        <div class="absolute top-4 left-4 flex gap-2">
                            <span class="inline-flex items-center gap-1 bg-white/90 backdrop-blur-xl text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                                <i data-lucide="tag" class="w-3 h-3"></i>
                                {{ $product->category->name }}
                            </span>
                        </div>

                        @if ($product->is_featured)
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
                            {{ $product->name }}
                        </h3>

                        <p class="text-sm text-slate-500 line-clamp-2 mt-3">
                            {{ $product->short_description }}
                        </p>

                        <div class="flex flex-wrap gap-2 mt-4">
                            <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                                <i data-lucide="code" class="w-3 h-3"></i>
                                {{ $product->technology ?? 'Web App' }}
                            </span>

                            <span class="inline-flex items-center gap-1 bg-cyan-50 text-cyan-700 px-3 py-1 rounded-full text-xs font-bold">
                                <i data-lucide="smartphone" class="w-3 h-3"></i>
                                Responsive
                            </span>
                        </div>

                        <div class="flex items-end justify-between mt-6 pt-5 border-t border-blue-50">
                            <div>
                                @if ($product->discount_price)
                                    <p class="text-sm text-slate-400 line-through">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </p>
                                @endif

                                <p class="text-2xl font-black text-blue-600">
                                    Rp {{ number_format($product->final_price, 0, ',', '.') }}
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
                        <i data-lucide="package-x" class="w-8 h-8"></i>
                    </div>
                    <p class="font-bold text-slate-800">Belum ada source code tersedia.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $products->links() }}
        </div>
    </section>
</x-app-layout>