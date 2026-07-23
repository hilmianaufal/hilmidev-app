<x-app-layout>
    @php
        $activeFilterCount = collect([
            $search !== '',
            $category !== '',
            $technology !== '',
            $sort !== 'latest',
        ])->filter()->count();

        $sortLabels = [
            'latest' => 'Produk terbaru',
            'featured' => 'Produk pilihan',
            'price_low' => 'Harga termurah',
            'price_high' => 'Harga termahal',
            'name_asc' => 'Nama A–Z',
            'name_desc' => 'Nama Z–A',
            'oldest' => 'Produk terlama',
        ];
    @endphp

    <div class="min-h-screen bg-white">

        {{-- PAGE HEADER --}}
        <section class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <nav class="mb-5 flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-400">
                            <a href="{{ route('home') }}"
                               class="transition hover:text-slate-950">
                                Beranda
                            </a>

                            <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>

                            <span class="text-slate-700">
                                Semua Produk
                            </span>
                        </nav>

                        <div class="mb-3 flex items-center gap-2 text-xs font-black uppercase tracking-[0.18em] text-slate-400">
                            <i data-lucide="code-2" class="h-4 w-4"></i>
                            HilmiDev Marketplace
                        </div>

                        <h1 class="text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">
                            Semua Source Code
                        </h1>

                        <p class="mt-3 max-w-2xl text-sm font-medium leading-7 text-slate-500 sm:text-base">
                            Temukan source code website dan aplikasi siap dikembangkan
                            berdasarkan kategori, teknologi, harga, dan kebutuhan project.
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="rounded-2xl border border-slate-200 bg-white px-5 py-3 shadow-sm">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                Produk ditemukan
                            </p>

                            <p class="mt-1 text-2xl font-black text-slate-950">
                                {{ number_format($products->total()) }}
                            </p>
                        </div>

                        <a href="{{ route('services.index') }}"
                           class="inline-flex h-[58px] items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 text-sm font-black text-white shadow-lg shadow-slate-950/10 transition duration-300 hover:-translate-y-0.5 hover:bg-slate-800">
                            <i data-lucide="briefcase-business" class="h-5 w-5"></i>

                            <span class="hidden sm:inline">
                                Project Custom
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- FILTER --}}
        <section class="sticky top-[76px] z-30 border-b border-slate-200 bg-white/95 backdrop-blur-xl">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <form action="{{ route('products.index') }}" method="GET">
                    <input type="hidden" name="category" value="{{ $category }}">

                    <div class="grid gap-3 lg:grid-cols-12">
                        <div class="relative lg:col-span-6">
                            <i data-lucide="search"
                               class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"></i>

                            <input type="search"
                                   name="q"
                                   value="{{ $search }}"
                                   placeholder="Cari aplikasi kasir, sekolah, koperasi..."
                                   class="h-14 w-full rounded-2xl border-slate-200 bg-white pl-12 pr-4 text-sm font-semibold text-slate-900 shadow-sm transition placeholder:font-medium placeholder:text-slate-400 focus:border-slate-400 focus:ring-4 focus:ring-slate-100">
                        </div>

                        <div class="lg:col-span-3">
                            <select name="technology"
                                    onchange="this.form.submit()"
                                    class="h-14 w-full rounded-2xl border-slate-200 bg-white px-4 text-sm font-bold text-slate-700 shadow-sm transition focus:border-slate-400 focus:ring-4 focus:ring-slate-100">
                                <option value="">
                                    Semua teknologi
                                </option>

                                @foreach ($technologies as $technologyItem)
                                    <option value="{{ $technologyItem }}"
                                            @selected($technology === $technologyItem)>
                                        {{ $technologyItem }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="lg:col-span-2">
                            <select name="sort"
                                    onchange="this.form.submit()"
                                    class="h-14 w-full rounded-2xl border-slate-200 bg-white px-4 text-sm font-bold text-slate-700 shadow-sm transition focus:border-slate-400 focus:ring-4 focus:ring-slate-100">
                                <option value="latest" @selected($sort === 'latest')>
                                    Terbaru
                                </option>

                                <option value="featured" @selected($sort === 'featured')>
                                    Produk pilihan
                                </option>

                                <option value="price_low" @selected($sort === 'price_low')>
                                    Termurah
                                </option>

                                <option value="price_high" @selected($sort === 'price_high')>
                                    Termahal
                                </option>

                                <option value="name_asc" @selected($sort === 'name_asc')>
                                    Nama A–Z
                                </option>

                                <option value="name_desc" @selected($sort === 'name_desc')>
                                    Nama Z–A
                                </option>

                                <option value="oldest" @selected($sort === 'oldest')>
                                    Terlama
                                </option>
                            </select>
                        </div>

                        <div class="flex gap-2 lg:col-span-1">
                            <button type="submit"
                                    title="Cari produk"
                                    class="flex h-14 flex-1 items-center justify-center rounded-2xl bg-slate-950 text-white shadow-lg shadow-slate-950/10 transition hover:bg-slate-800">
                                <i data-lucide="search" class="h-5 w-5"></i>
                            </button>

                            @if ($activeFilterCount > 0)
                                <a href="{{ route('products.index') }}"
                                   title="Reset filter"
                                   class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-slate-950 lg:hidden">
                                    <i data-lucide="rotate-ccw" class="h-5 w-5"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </section>

        {{-- CATEGORY FILTER --}}
        <section class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl overflow-x-auto px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex min-w-max gap-2">
                    <a href="{{ route('products.index', array_filter([
                            'q' => $search,
                            'technology' => $technology,
                            'sort' => $sort,
                        ])) }}"
                       class="inline-flex items-center gap-2 rounded-xl border px-4 py-3 text-sm font-bold transition
                       {{ $category === ''
                           ? 'border-slate-950 bg-slate-950 text-white'
                           : 'border-slate-200 bg-white text-slate-600 hover:border-slate-400 hover:text-slate-950' }}">
                        <i data-lucide="layout-grid" class="h-4 w-4"></i>
                        Semua
                    </a>

                    @foreach ($categories as $categoryItem)
                        <a href="{{ route('products.index', array_filter([
                                'q' => $search,
                                'category' => $categoryItem->slug,
                                'technology' => $technology,
                                'sort' => $sort,
                            ])) }}"
                           class="inline-flex items-center gap-2 rounded-xl border px-4 py-3 text-sm font-bold transition
                           {{ $category === $categoryItem->slug
                               ? 'border-slate-950 bg-slate-950 text-white'
                               : 'border-slate-200 bg-white text-slate-600 hover:border-slate-400 hover:text-slate-950' }}">
                            <span>{{ $categoryItem->icon ?? '📦' }}</span>

                            {{ $categoryItem->name }}

                            <span class="rounded-full px-2 py-0.5 text-xs
                                {{ $category === $categoryItem->slug
                                    ? 'bg-white/15 text-white'
                                    : 'bg-slate-100 text-slate-500' }}">
                                {{ $categoryItem->products_count }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- PRODUCT CONTENT --}}
        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">

            {{-- RESULT INFO --}}
            <div class="mb-6 flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-black text-slate-950">
                        {{ number_format($products->total()) }} produk ditemukan
                    </p>

                    <p class="mt-1 text-xs font-medium text-slate-400">
                        {{ $sortLabels[$sort] ?? 'Produk terbaru' }}
                    </p>
                </div>

                @if ($activeFilterCount > 0)
                    <div class="flex flex-wrap items-center gap-2">
                        @if ($search !== '')
                            <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600">
                                <i data-lucide="search" class="h-3.5 w-3.5"></i>
                                {{ $search }}
                            </span>
                        @endif

                        @if ($category !== '')
                            <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600">
                                <i data-lucide="tag" class="h-3.5 w-3.5"></i>

                                {{ $categories->firstWhere('slug', $category)?->name ?? $category }}
                            </span>
                        @endif

                        @if ($technology !== '')
                            <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600">
                                <i data-lucide="cpu" class="h-3.5 w-3.5"></i>
                                {{ $technology }}
                            </span>
                        @endif

                        <a href="{{ route('products.index') }}"
                           class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600 transition hover:bg-slate-50 hover:text-slate-950">
                            <i data-lucide="x" class="h-3.5 w-3.5"></i>
                            Reset
                        </a>
                    </div>
                @endif
            </div>

            @if ($products->count())
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($products as $product)
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
                                ->take(2);
                        @endphp

                        <article class="group flex h-full flex-col overflow-hidden rounded-[1.6rem] border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-slate-300 hover:shadow-xl hover:shadow-slate-900/[0.06]">

                            {{-- IMAGE --}}
                            <div class="relative p-3 pb-0">
                                <a href="{{ route('products.show', $product) }}"
                                   class="block aspect-[16/10] overflow-hidden rounded-[1.15rem] border border-slate-100 bg-white">
                                    @if ($product->thumbnail)
                                        <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                             alt="{{ $product->name }}"
                                             loading="lazy"
                                             class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.03]">
                                    @else
                                        <div class="flex h-full items-center justify-center">
                                            <div class="text-center">
                                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl border border-slate-200 text-slate-700 shadow-sm">
                                                    <i data-lucide="code-2" class="h-7 w-7"></i>
                                                </div>

                                                <p class="mt-3 text-xs font-black text-slate-700">
                                                    Premium Source Code
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </a>

                                <div class="absolute left-6 top-6 flex flex-wrap gap-2">
                                    @if ($product->is_featured)
                                        <span class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white px-2.5 py-1.5 text-[9px] font-black text-slate-800 shadow-sm">
                                            <i data-lucide="star" class="h-3 w-3"></i>
                                            Pilihan
                                        </span>
                                    @endif

                                    @if ($discountPercent)
                                        <span class="rounded-full bg-slate-950 px-2.5 py-1.5 text-[9px] font-black text-white shadow-sm">
                                            -{{ $discountPercent }}%
                                        </span>
                                    @endif
                                </div>

                                @if ($product->demo_url)
                                    <a href="{{ $product->demo_url }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       title="Buka live demo"
                                       class="absolute bottom-3 right-6 flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:border-slate-950 hover:bg-slate-950 hover:text-white">
                                        <i data-lucide="external-link" class="h-4 w-4"></i>
                                    </a>
                                @endif
                            </div>

                            {{-- CONTENT --}}
                            <div class="flex flex-1 flex-col p-5">
                                <div class="mb-3 flex items-center justify-between gap-2">
                                    <span class="inline-flex min-w-0 items-center gap-1.5 text-[9px] font-black uppercase tracking-wider text-slate-400">
                                        <span>{{ $product->category->icon ?? '📦' }}</span>

                                        <span class="truncate">
                                            {{ $product->category->name ?? 'Source Code' }}
                                        </span>
                                    </span>

                                    <span class="inline-flex items-center gap-1 text-[9px] font-bold text-slate-400">
                                        <i data-lucide="circle-check" class="h-3 w-3"></i>
                                        Ready
                                    </span>
                                </div>

                                <a href="{{ route('products.show', $product) }}">
                                    <h2 class="line-clamp-2 text-base font-black leading-6 text-slate-950 transition group-hover:text-slate-700">
                                        {{ $product->name }}
                                    </h2>
                                </a>

                                <p class="mt-3 line-clamp-3 text-xs font-medium leading-5 text-slate-500">
                                    {{ $product->short_description ?: $product->description }}
                                </p>

                                @if ($technologyItems->count())
                                    <div class="mt-4 flex flex-wrap gap-1.5">
                                        @foreach ($technologyItems as $technologyName)
                                            <span class="rounded-lg border border-slate-200 px-2 py-1 text-[9px] font-black uppercase tracking-wide text-slate-500">
                                                {{ $technologyName }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="mt-auto pt-5">
                                    <div class="mb-4 border-t border-slate-100 pt-4">
                                        @if ($product->discount_price)
                                            <p class="text-[10px] font-bold text-slate-400 line-through">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </p>
                                        @endif

                                        <p class="mt-1 text-lg font-black text-slate-950">
                                            Rp {{ number_format($product->final_price, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2">
                                        @if ($product->demo_url)
                                            <a href="{{ $product->demo_url }}"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 px-3 py-3 text-[10px] font-black text-slate-700 transition hover:bg-slate-50">
                                                <i data-lucide="monitor-play" class="h-3.5 w-3.5"></i>
                                                Demo
                                            </a>
                                        @else
                                            <a href="{{ route('products.show', $product) }}"
                                               class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 px-3 py-3 text-[10px] font-black text-slate-700 transition hover:bg-slate-50">
                                                <i data-lucide="eye" class="h-3.5 w-3.5"></i>
                                                Preview
                                            </a>
                                        @endif

                                        <a href="{{ route('products.show', $product) }}"
                                           class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-slate-950 px-3 py-3 text-[10px] font-black text-white transition hover:bg-slate-800">
                                            Detail
                                            <i data-lucide="arrow-up-right" class="h-3.5 w-3.5"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-10 border-t border-slate-200 pt-6">
                    {{ $products->onEachSide(1)->links() }}
                </div>
            @else
                <div class="rounded-[2rem] border border-dashed border-slate-300 px-6 py-20 text-center">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[1.75rem] border border-slate-200 text-slate-500 shadow-sm">
                        <i data-lucide="search-x" class="h-9 w-9"></i>
                    </div>

                    <h2 class="mt-6 text-2xl font-black text-slate-950">
                        Source code tidak ditemukan
                    </h2>

                    <p class="mx-auto mt-3 max-w-xl text-sm font-medium leading-7 text-slate-500">
                        Produk dengan pencarian atau filter tersebut belum tersedia.
                        Gunakan kata kunci lain atau hapus seluruh filter.
                    </p>

                    <a href="{{ route('products.index') }}"
                       class="mt-7 inline-flex items-center gap-2 rounded-2xl bg-slate-950 px-6 py-4 text-sm font-black text-white transition hover:bg-slate-800">
                        <i data-lucide="rotate-ccw" class="h-5 w-5"></i>
                        Hapus Semua Filter
                    </a>
                </div>
            @endif
        </main>

        {{-- SERVICE CTA --}}
        <section class="border-t border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div class="rounded-[2rem] border border-slate-200 px-6 py-10 shadow-sm sm:px-10 lg:flex lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.16em] text-slate-400">
                            Belum menemukan aplikasi yang sesuai?
                        </p>

                        <h2 class="mt-3 text-2xl font-black text-slate-950">
                            Buat aplikasi custom bersama HilmiDev
                        </h2>

                        <p class="mt-3 max-w-2xl text-sm font-medium leading-7 text-slate-500">
                            Fitur, desain, dan alur aplikasi dapat disesuaikan
                            berdasarkan kebutuhan bisnis maupun instansi.
                        </p>
                    </div>

                    <a href="{{ route('services.index') }}"
                       class="mt-7 inline-flex items-center justify-center gap-2 rounded-2xl bg-slate-950 px-6 py-4 text-sm font-black text-white shadow-lg shadow-slate-950/10 transition hover:-translate-y-0.5 hover:bg-slate-800 lg:mt-0">
                        Lihat Jasa Pembuatan
                        <i data-lucide="arrow-right" class="h-5 w-5"></i>
                    </a>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</x-app-layout>
