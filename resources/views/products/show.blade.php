<x-app-layout>
    @php
        $discountPercent = $product->discount_price && $product->price > 0
            ? max(
                0,
                round(
                    (($product->price - $product->discount_price) / $product->price) * 100
                )
            )
            : null;

        $technologyItems = collect(
            preg_split('/[,|\/]+/', (string) $product->technology)
        )
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values();

        $productFeatures = is_array($product->features)
            ? $product->features
            : [];

        $productScreenshots = is_array($product->screenshots ?? null)
            ? $product->screenshots
            : [];
    @endphp

    <div class="min-h-screen bg-white">

        {{-- BREADCRUMB --}}
        <section class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
                <nav class="flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-400">
                    <a href="{{ route('home') }}"
                       class="transition hover:text-blue-700">
                        Beranda
                    </a>

                    <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>

                    <a href="{{ route('products.index') }}"
                       class="transition hover:text-blue-700">
                        Source Code
                    </a>

                    <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>

                    @if ($product->category)
                        <a href="{{ route('products.index', ['category' => $product->category->slug]) }}"
                           class="transition hover:text-blue-700">
                            {{ $product->category->name }}
                        </a>

                        <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>
                    @endif

                    <span class="max-w-xs truncate font-bold text-slate-700">
                        {{ $product->name }}
                    </span>
                </nav>
            </div>
        </section>

        {{-- INFORMASI UTAMA --}}
        <section class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
                <div class="grid items-start gap-10 lg:grid-cols-12">

                    {{-- PREVIEW PRODUK --}}
                    <div class="lg:col-span-7">
                        <div class="relative">
                            <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white p-3 shadow-xl shadow-slate-900/[0.05]">
                                <div class="relative aspect-[16/10] overflow-hidden rounded-[1.5rem] border border-slate-100 bg-slate-50">
                                    @if ($product->thumbnail)
                                        <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                             alt="{{ $product->name }}"
                                             class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full items-center justify-center bg-white">
                                            <div class="text-center">
                                                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[1.5rem] border border-slate-200 text-blue-700 shadow-sm">
                                                    <i data-lucide="code-2" class="h-10 w-10"></i>
                                                </div>

                                                <p class="mt-5 text-sm font-black text-slate-800">
                                                    Premium Source Code
                                                </p>

                                                <p class="mt-2 text-xs font-medium text-slate-400">
                                                    Preview belum tersedia
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="absolute left-5 top-5 flex flex-wrap gap-2">
                                        @if ($product->is_featured)
                                            <span class="inline-flex items-center gap-1.5 rounded-full border border-white/40 bg-white/95 px-3 py-2 text-[10px] font-black text-blue-800 shadow-lg backdrop-blur">
                                                <i data-lucide="star" class="h-3.5 w-3.5 fill-current"></i>
                                                Produk Pilihan
                                            </span>
                                        @endif

                                        @if ($discountPercent)
                                            <span class="rounded-full bg-blue-700 px-3 py-2 text-[10px] font-black text-white shadow-lg">
                                                Hemat {{ $discountPercent }}%
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- ACTION PREVIEW --}}
                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                @if ($product->demo_url)
                                    <a href="{{ $product->demo_url }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-4 text-sm font-black text-slate-700 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:border-blue-300 hover:text-blue-700 hover:shadow-md">
                                        <i data-lucide="monitor-play" class="h-5 w-5"></i>
                                        Buka Live Demo
                                    </a>
                                @endif

                                @if ($product->video_url)
                                    <a href="{{ $product->video_url }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-4 text-sm font-black text-slate-700 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:border-blue-300 hover:text-blue-700 hover:shadow-md">
                                        <i data-lucide="circle-play" class="h-5 w-5"></i>
                                        Lihat Video
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- INFORMASI PRODUK --}}
                    <div class="lg:col-span-5">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-black text-blue-700">
                                <span>{{ $product->category->icon ?? '📦' }}</span>
                                {{ $product->category->name ?? 'Source Code' }}
                            </span>

                            <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-black text-emerald-700">
                                <i data-lucide="circle-check" class="h-3.5 w-3.5"></i>
                                Tersedia
                            </span>
                        </div>

                        <h1 class="mt-6 text-3xl font-black leading-tight tracking-tight text-slate-950 sm:text-4xl lg:text-5xl">
                            {{ $product->name }}
                        </h1>

                        <p class="mt-5 text-sm font-medium leading-7 text-slate-500 sm:text-base">
                            {{ $product->short_description ?: $product->description }}
                        </p>

                        {{-- TECHNOLOGY --}}
                        @if ($technologyItems->count())
                            <div class="mt-6">
                                <p class="mb-3 text-[10px] font-black uppercase tracking-[0.16em] text-slate-400">
                                    Teknologi yang digunakan
                                </p>

                                <div class="flex flex-wrap gap-2">
                                    @foreach ($technologyItems as $technologyName)
                                        <span class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600">
                                            <i data-lucide="cpu" class="h-3.5 w-3.5 text-blue-700"></i>
                                            {{ $technologyName }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- KEUNGGULAN --}}
                        <div class="mt-7 grid grid-cols-3 divide-x divide-slate-200 rounded-2xl border border-slate-200 bg-white py-4 shadow-sm">
                            <div class="px-3 text-center">
                                <i data-lucide="code-2"
                                   class="mx-auto h-5 w-5 text-blue-700"></i>

                                <p class="mt-2 text-[10px] font-black uppercase tracking-wide text-slate-500">
                                    Source Lengkap
                                </p>
                            </div>

                            <div class="px-3 text-center">
                                <i data-lucide="smartphone"
                                   class="mx-auto h-5 w-5 text-blue-700"></i>

                                <p class="mt-2 text-[10px] font-black uppercase tracking-wide text-slate-500">
                                    Responsive
                                </p>
                            </div>

                            <div class="px-3 text-center">
                                <i data-lucide="headphones"
                                   class="mx-auto h-5 w-5 text-blue-700"></i>

                                <p class="mt-2 text-[10px] font-black uppercase tracking-wide text-slate-500">
                                    Support
                                </p>
                            </div>
                        </div>

                        {{-- HARGA --}}
                        <div class="mt-7 border-t border-slate-200 pt-6">
                            <p class="text-xs font-black uppercase tracking-[0.14em] text-slate-400">
                                Harga source code
                            </p>

                            <div class="mt-2 flex flex-wrap items-end gap-3">
                                <p class="text-3xl font-black tracking-tight text-blue-800 sm:text-4xl">
                                    Rp {{ number_format($product->final_price, 0, ',', '.') }}
                                </p>

                                @if ($product->discount_price)
                                    <p class="pb-1 text-sm font-bold text-slate-400 line-through">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- BUTTON --}}
                        <div class="mt-7 grid gap-3 sm:grid-cols-2">
                            <a href="#checkout"
                               class="inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-700 px-6 py-4 text-sm font-black text-white shadow-xl shadow-blue-700/20 transition duration-300 hover:-translate-y-0.5 hover:bg-blue-800">
                                <i data-lucide="shopping-cart" class="h-5 w-5"></i>
                                Beli Sekarang
                            </a>

                            @if ($product->demo_url)
                                <a href="{{ $product->demo_url }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-6 py-4 text-sm font-black text-slate-700 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:border-blue-300 hover:text-blue-700">
                                    <i data-lucide="external-link" class="h-5 w-5"></i>
                                    Live Demo
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- DETAIL PRODUK --}}
        <main class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
            <div class="grid items-start gap-10 lg:grid-cols-12">

                {{-- KONTEN KIRI --}}
                <div class="space-y-10 lg:col-span-8">

                    {{-- DESKRIPSI --}}
                    <section id="detail-produk">
                        <div class="mb-6 flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-700 text-white shadow-lg shadow-blue-700/20">
                                <i data-lucide="file-text" class="h-6 w-6"></i>
                            </div>

                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.16em] text-blue-700">
                                    Informasi Produk
                                </p>

                                <h2 class="mt-1 text-2xl font-black text-slate-950">
                                    Deskripsi Source Code
                                </h2>
                            </div>
                        </div>

                        <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                            <div class="text-sm font-medium leading-8 text-slate-600 sm:text-base">
                                {!! nl2br(e($product->description ?: $product->short_description)) !!}
                            </div>
                        </div>
                    </section>

                    {{-- FITUR --}}
                    @if (count($productFeatures))
                        <section>
                            <div class="mb-6 flex items-center gap-4">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-700 text-white shadow-lg shadow-blue-700/20">
                                    <i data-lucide="list-checks" class="h-6 w-6"></i>
                                </div>

                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.16em] text-blue-700">
                                        Fitur Aplikasi
                                    </p>

                                    <h2 class="mt-1 text-2xl font-black text-slate-950">
                                        Fitur yang tersedia
                                    </h2>
                                </div>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2">
                                @foreach ($productFeatures as $feature)
                                    <div class="group flex items-start gap-4 rounded-2xl border border-slate-200 bg-white p-5 transition duration-300 hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-lg hover:shadow-blue-700/[0.05]">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-700 transition group-hover:bg-blue-700 group-hover:text-white">
                                            <i data-lucide="check" class="h-4 w-4"></i>
                                        </div>

                                        <p class="pt-1 text-sm font-bold leading-6 text-slate-700">
                                            {{ $feature }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    {{-- SCREENSHOTS --}}
                    @if (count($productScreenshots))
                        <section>
                            <div class="mb-6 flex items-center gap-4">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-700 text-white shadow-lg shadow-blue-700/20">
                                    <i data-lucide="images" class="h-6 w-6"></i>
                                </div>

                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.16em] text-blue-700">
                                        Tampilan Aplikasi
                                    </p>

                                    <h2 class="mt-1 text-2xl font-black text-slate-950">
                                        Screenshot produk
                                    </h2>
                                </div>
                            </div>

                            <div class="grid gap-5 sm:grid-cols-2">
                                @foreach ($productScreenshots as $screenshot)
                                    <a href="{{ asset('storage/' . $screenshot) }}"
                                       target="_blank"
                                       class="group overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white p-2 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                                        <div class="aspect-video overflow-hidden rounded-[1.15rem]">
                                            <img src="{{ asset('storage/' . $screenshot) }}"
                                                 alt="Screenshot {{ $product->name }}"
                                                 loading="lazy"
                                                 class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.03]">
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    {{-- INFORMASI PEMBELIAN --}}
                    <section class="rounded-[1.75rem] border border-blue-200 bg-blue-50/50 p-6 sm:p-8">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-700 text-white">
                                <i data-lucide="info" class="h-6 w-6"></i>
                            </div>

                            <div>
                                <h2 class="text-xl font-black text-slate-950">
                                    Cara mendapatkan source code
                                </h2>

                                <p class="mt-2 text-sm font-medium leading-7 text-slate-600">
                                    Buat invoice melalui tombol checkout, lakukan pembayaran,
                                    kemudian unggah bukti transfer. File dapat diunduh melalui
                                    dashboard setelah pembayaran diverifikasi admin.
                                </p>
                            </div>
                        </div>

                        <div class="mt-7 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-2xl border border-blue-100 bg-white p-5">
                                <span class="text-2xl font-black text-blue-200">
                                    01
                                </span>

                                <h3 class="mt-4 text-sm font-black text-slate-950">
                                    Buat Invoice
                                </h3>

                                <p class="mt-2 text-xs font-medium leading-5 text-slate-500">
                                    Login dan lakukan checkout produk.
                                </p>
                            </div>

                            <div class="rounded-2xl border border-blue-100 bg-white p-5">
                                <span class="text-2xl font-black text-blue-200">
                                    02
                                </span>

                                <h3 class="mt-4 text-sm font-black text-slate-950">
                                    Bayar Pesanan
                                </h3>

                                <p class="mt-2 text-xs font-medium leading-5 text-slate-500">
                                    Transfer dan unggah bukti pembayaran.
                                </p>
                            </div>

                            <div class="rounded-2xl border border-blue-100 bg-white p-5">
                                <span class="text-2xl font-black text-blue-200">
                                    03
                                </span>

                                <h3 class="mt-4 text-sm font-black text-slate-950">
                                    Download File
                                </h3>

                                <p class="mt-2 text-xs font-medium leading-5 text-slate-500">
                                    Unduh setelah pembayaran disetujui.
                                </p>
                            </div>
                        </div>
                    </section>
                </div>

                {{-- CHECKOUT SIDEBAR --}}
                <aside id="checkout" class="scroll-mt-28 lg:col-span-4">
                    <div class="sticky top-28 overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-2xl shadow-slate-900/[0.08]">

                        <div class="border-b border-blue-800 bg-blue-700 p-6 text-white">
                            <div class="flex items-center gap-4">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/20 bg-white/10">
                                    <i data-lucide="shopping-cart" class="h-6 w-6"></i>
                                </div>

                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.16em] text-blue-200">
                                        Pembelian Produk
                                    </p>

                                    <h2 class="mt-1 text-xl font-black">
                                        Checkout Source Code
                                    </h2>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="flex items-center gap-4 border-b border-slate-100 pb-5">
                                <div class="h-16 w-20 shrink-0 overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                    @if ($product->thumbnail)
                                        <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                             alt="{{ $product->name }}"
                                             class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full items-center justify-center text-blue-700">
                                            <i data-lucide="code-2" class="h-6 w-6"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="min-w-0">
                                    <p class="line-clamp-2 text-sm font-black leading-5 text-slate-950">
                                        {{ $product->name }}
                                    </p>

                                    <p class="mt-1 text-xs font-medium text-slate-400">
                                        {{ $product->category->name ?? 'Source Code' }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-5 space-y-4">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-sm font-medium text-slate-500">
                                        Kategori
                                    </span>

                                    <span class="text-right text-sm font-black text-slate-800">
                                        {{ $product->category->name ?? '-' }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-sm font-medium text-slate-500">
                                        Teknologi
                                    </span>

                                    <span class="max-w-[55%] text-right text-sm font-black text-slate-800">
                                        {{ $product->technology ?: '-' }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-sm font-medium text-slate-500">
                                        Status
                                    </span>

                                    <span class="inline-flex items-center gap-1.5 text-xs font-black text-emerald-600">
                                        <i data-lucide="circle-check" class="h-4 w-4"></i>
                                        Tersedia
                                    </span>
                                </div>
                            </div>

                            <div class="my-6 border-t border-dashed border-slate-200"></div>

                            <div class="flex items-end justify-between gap-4">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                        Total pembayaran
                                    </p>

                                    @if ($product->discount_price)
                                        <p class="mt-2 text-xs font-bold text-slate-400 line-through">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </p>
                                    @endif
                                </div>

                                <p class="text-2xl font-black text-blue-800">
                                    Rp {{ number_format($product->final_price, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="mt-6">
                                @auth
                                    <form action="{{ route('checkout.store', $product) }}"
                                          method="POST"
                                          x-data="{ processing: false }"
                                          @submit="processing = true">
                                        @csrf

                                        <button type="submit"
                                                :disabled="processing"
                                                class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-700 px-6 py-4 text-sm font-black text-white shadow-xl shadow-blue-700/20 transition duration-300 hover:-translate-y-0.5 hover:bg-blue-800 disabled:cursor-not-allowed disabled:opacity-60">
                                            <i x-show="!processing"
                                               data-lucide="credit-card"
                                               class="h-5 w-5"></i>

                                            <svg x-show="processing"
                                                 x-cloak
                                                 class="h-5 w-5 animate-spin"
                                                 viewBox="0 0 24 24"
                                                 fill="none">
                                                <circle class="opacity-25"
                                                        cx="12"
                                                        cy="12"
                                                        r="10"
                                                        stroke="currentColor"
                                                        stroke-width="4">
                                                </circle>

                                                <path class="opacity-75"
                                                      fill="currentColor"
                                                      d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                                </path>
                                            </svg>

                                            <span x-text="processing ? 'Membuat Invoice...' : 'Checkout Sekarang'">
                                                Checkout Sekarang
                                            </span>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}"
                                       class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-700 px-6 py-4 text-sm font-black text-white shadow-xl shadow-blue-700/20 transition duration-300 hover:-translate-y-0.5 hover:bg-blue-800">
                                        <i data-lucide="log-in" class="h-5 w-5"></i>
                                        Login untuk Checkout
                                    </a>

                                    <p class="mt-3 text-center text-xs font-medium leading-5 text-slate-400">
                                        Kamu perlu masuk terlebih dahulu untuk membuat invoice.
                                    </p>
                                @endauth
                            </div>

                            <div class="mt-6 space-y-3 border-t border-slate-100 pt-5">
                                <div class="flex items-center gap-3 text-xs font-semibold text-slate-500">
                                    <i data-lucide="shield-check"
                                       class="h-4 w-4 text-blue-700"></i>
                                    Transaksi dicatat melalui invoice
                                </div>

                                <div class="flex items-center gap-3 text-xs font-semibold text-slate-500">
                                    <i data-lucide="download-cloud"
                                       class="h-4 w-4 text-blue-700"></i>
                                    Download setelah pembayaran disetujui
                                </div>

                                <div class="flex items-center gap-3 text-xs font-semibold text-slate-500">
                                    <i data-lucide="headphones"
                                       class="h-4 w-4 text-blue-700"></i>
                                    Tersedia bantuan teknis
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </main>

        {{-- PRODUK TERKAIT --}}
        @if ($relatedProducts->count())
            <section class="border-t border-slate-200 bg-white">
                <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
                    <div class="mb-9 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.16em] text-blue-700">
                                Produk Lainnya
                            </p>

                            <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950">
                                Source code terkait
                            </h2>

                            <p class="mt-3 text-sm font-medium text-slate-500">
                                Produk lain dari kategori yang sama.
                            </p>
                        </div>

                        <a href="{{ route('products.index', [
                            'category' => $product->category->slug ?? null
                        ]) }}"
                           class="inline-flex items-center gap-2 text-sm font-black text-blue-700 transition hover:text-blue-900">
                            Lihat Semua Produk
                            <i data-lucide="arrow-right" class="h-4 w-4"></i>
                        </a>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($relatedProducts as $related)
                            <article class="group flex h-full flex-col overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-700/[0.06]">
                                <a href="{{ route('products.show', $related) }}"
                                   class="block p-3 pb-0">
                                    <div class="aspect-[16/10] overflow-hidden rounded-[1.1rem] border border-slate-100 bg-slate-50">
                                        @if ($related->thumbnail)
                                            <img src="{{ asset('storage/' . $related->thumbnail) }}"
                                                 alt="{{ $related->name }}"
                                                 loading="lazy"
                                                 class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.03]">
                                        @else
                                            <div class="flex h-full items-center justify-center text-blue-700">
                                                <i data-lucide="code-2" class="h-9 w-9"></i>
                                            </div>
                                        @endif
                                    </div>
                                </a>

                                <div class="flex flex-1 flex-col p-5">
                                    <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">
                                        {{ $related->category->name ?? 'Source Code' }}
                                    </p>

                                    <a href="{{ route('products.show', $related) }}">
                                        <h3 class="mt-3 line-clamp-2 text-base font-black leading-6 text-slate-950 transition group-hover:text-blue-700">
                                            {{ $related->name }}
                                        </h3>
                                    </a>

                                    <p class="mt-3 line-clamp-2 text-xs font-medium leading-5 text-slate-500">
                                        {{ $related->short_description }}
                                    </p>

                                    <div class="mt-auto flex items-end justify-between border-t border-slate-100 pt-5">
                                        <div>
                                            @if ($related->discount_price)
                                                <p class="text-[9px] font-bold text-slate-400 line-through">
                                                    Rp {{ number_format($related->price, 0, ',', '.') }}
                                                </p>
                                            @endif

                                            <p class="mt-1 text-base font-black text-blue-800">
                                                Rp {{ number_format($related->final_price, 0, ',', '.') }}
                                            </p>
                                        </div>

                                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-700 text-white transition group-hover:bg-blue-800">
                                            <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</x-app-layout>
