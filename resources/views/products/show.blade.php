<x-app-layout>
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-cyan-500 to-blue-400"></div>
        <div class="absolute -top-32 -right-20 w-80 h-80 bg-white/20 rounded-full blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 py-12 md:py-20 text-white">
            <a href="{{ route('products.index') }}"
               class="inline-flex items-center gap-2 bg-white/15 border border-white/20 backdrop-blur-xl px-4 py-2 rounded-full text-sm font-bold mb-8">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali
            </a>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
                <div class="bg-white/15 border border-white/20 backdrop-blur-xl rounded-[2rem] p-3 shadow-2xl shadow-blue-900/20">
                    <div class="aspect-video rounded-[1.5rem] bg-blue-100 overflow-hidden">
                        @if ($product->thumbnail)
                            <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                 class="w-full h-full object-cover"
                                 alt="{{ $product->name }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-blue-500">
                                <i data-lucide="image" class="w-14 h-14"></i>
                            </div>
                        @endif
                    </div>
                </div>

                <div>
                    <div class="inline-flex items-center gap-2 bg-white/15 border border-white/20 backdrop-blur-xl px-4 py-2 rounded-full mb-5">
                        <i data-lucide="tag" class="w-4 h-4"></i>
                        <span class="text-sm font-bold">{{ $product->category->name }}</span>
                    </div>

                    <h1 class="text-4xl md:text-6xl font-black leading-tight">
                        {{ $product->name }}
                    </h1>

                    <p class="text-blue-50 text-lg mt-5 leading-relaxed">
                        {{ $product->short_description }}
                    </p>

                    <div class="flex flex-wrap gap-3 mt-6">
                        <span class="inline-flex items-center gap-2 bg-white/15 border border-white/20 px-4 py-2 rounded-2xl font-bold">
                            <i data-lucide="code" class="w-4 h-4"></i>
                            {{ $product->technology ?? 'Web App' }}
                        </span>

                        <span class="inline-flex items-center gap-2 bg-white/15 border border-white/20 px-4 py-2 rounded-2xl font-bold">
                            <i data-lucide="smartphone" class="w-4 h-4"></i>
                            Mobile First
                        </span>

                        <span class="inline-flex items-center gap-2 bg-white/15 border border-white/20 px-4 py-2 rounded-2xl font-bold">
                            <i data-lucide="shield-check" class="w-4 h-4"></i>
                            Siap Pakai
                        </span>
                    </div>

                    <div class="mt-8">
                        @if ($product->discount_price)
                            <p class="text-blue-100 line-through text-lg">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                        @endif

                        <p class="text-4xl font-black">
                            Rp {{ number_format($product->final_price, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 mt-8">
                        <a href="#checkout"
                           class="inline-flex items-center justify-center gap-2 bg-white text-blue-700 px-6 py-4 rounded-2xl font-black shadow-2xl shadow-blue-900/20">
                            <i data-lucide="credit-card" class="w-5 h-5"></i>
                            Beli Sekarang
                        </a>

                        @if ($product->demo_url)
                            <a href="{{ $product->demo_url }}"
                               target="_blank"
                               class="inline-flex items-center justify-center gap-2 bg-blue-900/20 border border-white/30 backdrop-blur-xl px-6 py-4 rounded-2xl font-black">
                                <i data-lucide="external-link" class="w-5 h-5"></i>
                                Live Demo
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white border border-blue-100 rounded-[2rem] p-6 md:p-8 shadow-xl shadow-blue-500/5">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <i data-lucide="file-text" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-2xl font-black text-slate-900">
                        Deskripsi Source Code
                    </h2>
                </div>

                <div class="prose max-w-none text-slate-600 leading-relaxed">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>

            @if ($product->features)
                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 md:p-8 shadow-xl shadow-blue-500/5">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-12 h-12 rounded-2xl bg-cyan-500 text-white flex items-center justify-center shadow-lg shadow-cyan-500/30">
                            <i data-lucide="list-checks" class="w-6 h-6"></i>
                        </div>
                        <h2 class="text-2xl font-black text-slate-900">
                            Fitur Aplikasi
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ($product->features as $feature)
                            <div class="flex items-start gap-3 bg-blue-50 border border-blue-100 p-4 rounded-2xl">
                                <div class="w-7 h-7 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                </div>
                                <span class="text-slate-700 font-semibold">{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($product->screenshots)
                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 md:p-8 shadow-xl shadow-blue-500/5">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/30">
                            <i data-lucide="images" class="w-6 h-6"></i>
                        </div>
                        <h2 class="text-2xl font-black text-slate-900">
                            Screenshot
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($product->screenshots as $screenshot)
                            <img src="{{ asset('storage/' . $screenshot) }}"
                                 class="rounded-3xl border border-blue-100 shadow-lg shadow-blue-500/10"
                                 alt="{{ $product->name }}">
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <aside class="space-y-6">
            <div id="checkout" class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-2xl shadow-blue-500/10 lg:sticky lg:top-28">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-400 text-white flex items-center justify-center shadow-lg shadow-blue-500/30 mb-5">
                    <i data-lucide="shopping-cart" class="w-7 h-7"></i>
                </div>

                <h3 class="text-2xl font-black text-slate-900 mb-2">
                    Checkout Produk
                </h3>

                <p class="text-slate-500 text-sm mb-6">
                    File source code dapat diunduh setelah pembayaran berhasil.
                </p>

                <div class="space-y-3 text-sm mb-6">
                    <div class="flex justify-between bg-blue-50 rounded-2xl p-4">
                        <span class="text-slate-500">Kategori</span>
                        <span class="font-bold text-slate-800">{{ $product->category->name }}</span>
                    </div>

                    <div class="flex justify-between bg-blue-50 rounded-2xl p-4">
                        <span class="text-slate-500">Teknologi</span>
                        <span class="font-bold text-slate-800">{{ $product->technology ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between bg-blue-50 rounded-2xl p-4">
                        <span class="text-slate-500">Total</span>
                        <span class="font-black text-blue-600">
                            Rp {{ number_format($product->final_price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                @auth
                    <form action="{{ route('checkout.store', $product) }}" method="POST">
                        @csrf
                        <button class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-black px-6 py-4 rounded-2xl shadow-lg shadow-blue-500/30">
                            <i data-lucide="credit-card" class="w-5 h-5"></i>
                            Checkout Sekarang
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-black px-6 py-4 rounded-2xl shadow-lg shadow-blue-500/30">
                        <i data-lucide="log-in" class="w-5 h-5"></i>
                        Login untuk Checkout
                    </a>
                @endauth
            </div>
        </aside>
    </section>

    @if ($relatedProducts->count())
        <section class="max-w-7xl mx-auto px-4 pb-14">
            <h2 class="text-3xl font-black text-slate-900 mb-6">
                Source Code Terkait
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($relatedProducts as $related)
                    <a href="{{ route('products.show', $related) }}"
                       class="bg-white rounded-[2rem] overflow-hidden border border-blue-100 shadow-xl shadow-blue-500/5 hover:shadow-2xl hover:shadow-blue-500/20 transition">
                        <div class="aspect-video bg-blue-50">
                            @if ($related->thumbnail)
                                <img src="{{ asset('storage/' . $related->thumbnail) }}"
                                     class="w-full h-full object-cover"
                                     alt="{{ $related->name }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-blue-400">
                                    <i data-lucide="image" class="w-10 h-10"></i>
                                </div>
                            @endif
                        </div>

                        <div class="p-5">
                            <h3 class="font-black text-slate-900 mb-3">
                                {{ $related->name }}
                            </h3>

                            <p class="text-blue-600 font-black">
                                Rp {{ number_format($related->final_price, 0, ',', '.') }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</x-app-layout>