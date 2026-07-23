<x-app-layout>
    @php
        $activeFilterCount = collect([
            $search !== '',
            $sort !== 'latest',
        ])->filter()->count();

        $sortLabels = [
            'latest' => 'Layanan terbaru',
            'featured' => 'Layanan pilihan',
            'price_low' => 'Harga terendah',
            'price_high' => 'Harga tertinggi',
            'name_asc' => 'Nama A–Z',
            'name_desc' => 'Nama Z–A',
            'oldest' => 'Layanan terlama',
        ];
    @endphp

    <div id="layanan" class="min-h-screen bg-white">

        {{-- HEADER RINGKAS --}}
        <section class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-9 sm:px-6 lg:px-8 lg:py-12">
                <nav class="mb-6 flex items-center gap-2 text-xs font-semibold text-slate-400">
                    <a href="{{ route('home') }}"
                       class="transition hover:text-blue-700">
                        Beranda
                    </a>

                    <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>

                    <span class="font-bold text-slate-700">
                        Jasa Website
                    </span>
                </nav>

                <div class="flex flex-col gap-7 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <div class="mb-4 flex items-center gap-2 text-xs font-black uppercase tracking-[0.18em] text-blue-700">
                            <i data-lucide="briefcase-business" class="h-4 w-4"></i>
                            Layanan Profesional HilmiDev
                        </div>

                        <h1 class="text-3xl font-black tracking-tight text-slate-950 sm:text-4xl lg:text-5xl">
                            Jasa Pembuatan Website dan Aplikasi
                        </h1>

                        <p class="mt-4 max-w-2xl text-sm font-medium leading-7 text-slate-500 sm:text-base">
                            Pembuatan sistem custom untuk bisnis, sekolah, koperasi,
                            pesantren, UMKM, perusahaan, dan berbagai kebutuhan instansi.
                        </p>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div class="min-w-24 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                            <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">
                                Layanan
                            </p>

                            <p class="mt-1 text-xl font-black text-slate-950">
                                {{ number_format($serviceStatistics['total_services']) }}
                            </p>
                        </div>

                        <div class="min-w-24 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                            <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">
                                Pilihan
                            </p>

                            <p class="mt-1 text-xl font-black text-blue-700">
                                {{ number_format($serviceStatistics['featured_services']) }}
                            </p>
                        </div>

                        <div class="min-w-28 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                            <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">
                                Mulai
                            </p>

                            <p class="mt-1 text-sm font-black text-slate-950">
                                Rp {{ number_format($serviceStatistics['starting_price'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- SEARCH FILTER --}}
        <section class="sticky top-[78px] z-30 border-b border-slate-200 bg-white/95 backdrop-blur-xl">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <form action="{{ route('services.index') }}" method="GET">
                    <div class="grid gap-3 lg:grid-cols-12">
                        <div class="relative lg:col-span-8">
                            <i data-lucide="search"
                               class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"></i>

                            <input type="search"
                                   name="q"
                                   value="{{ $search }}"
                                   placeholder="Cari jasa website sekolah, koperasi, kasir, company profile..."
                                   class="h-14 w-full rounded-2xl border-slate-200 bg-white pl-12 pr-4 text-sm font-semibold text-slate-900 shadow-sm transition placeholder:font-medium placeholder:text-slate-400 focus:border-blue-400 focus:ring-4 focus:ring-blue-50">
                        </div>

                        <div class="lg:col-span-3">
                            <select name="sort"
                                    onchange="this.form.submit()"
                                    class="h-14 w-full rounded-2xl border-slate-200 bg-white px-4 text-sm font-bold text-slate-700 shadow-sm transition focus:border-blue-400 focus:ring-4 focus:ring-blue-50">
                                <option value="latest" @selected($sort === 'latest')>
                                    Terbaru
                                </option>

                                <option value="featured" @selected($sort === 'featured')>
                                    Layanan pilihan
                                </option>

                                <option value="price_low" @selected($sort === 'price_low')>
                                    Harga terendah
                                </option>

                                <option value="price_high" @selected($sort === 'price_high')>
                                    Harga tertinggi
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
                                    title="Cari layanan"
                                    class="flex h-14 flex-1 items-center justify-center rounded-2xl bg-blue-700 text-white shadow-lg shadow-blue-700/20 transition hover:bg-blue-800">
                                <i data-lucide="search" class="h-5 w-5"></i>
                            </button>

                            @if ($activeFilterCount > 0)
                                <a href="{{ route('services.index') }}"
                                   title="Reset pencarian"
                                   class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-slate-950 lg:hidden">
                                    <i data-lucide="rotate-ccw" class="h-5 w-5"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </section>

        {{-- DAFTAR LAYANAN --}}
        <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">

            <div class="mb-7 flex flex-col gap-4 border-b border-slate-200 pb-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-black text-slate-950">
                        {{ number_format($services->total()) }} layanan ditemukan
                    </p>

                    <p class="mt-1 text-xs font-medium text-slate-400">
                        {{ $sortLabels[$sort] ?? 'Layanan terbaru' }}
                    </p>
                </div>

                @if ($activeFilterCount > 0)
                    <div class="flex flex-wrap items-center gap-2">
                        @if ($search !== '')
                            <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600">
                                <i data-lucide="search" class="h-3.5 w-3.5"></i>
                                {{ $search }}
                            </span>
                        @endif

                        <a href="{{ route('services.index') }}"
                           class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 transition hover:border-blue-200 hover:text-blue-700">
                            <i data-lucide="x" class="h-3.5 w-3.5"></i>
                            Reset
                        </a>
                    </div>
                @endif
            </div>

            @if ($services->count())
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($services as $service)
                        @php
                            $serviceFeatures = is_array($service->features)
                                ? array_slice($service->features, 0, 4)
                                : [];
                        @endphp

                        <article class="group flex h-full flex-col overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-700/[0.07]">

                            {{-- THUMBNAIL --}}
                            <a href="{{ route('services.show', $service) }}"
                               class="block p-3 pb-0">
                                <div class="relative aspect-[16/9] overflow-hidden rounded-[1.3rem] border border-slate-100 bg-white">
                                    @if ($service->thumbnail)
                                        <img src="{{ asset('storage/' . $service->thumbnail) }}"
                                             alt="{{ $service->title }}"
                                             loading="lazy"
                                             class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.03]">
                                    @else
                                        <div class="flex h-full items-center justify-center bg-white">
                                            <div class="text-center">
                                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-blue-100 bg-blue-50 text-3xl shadow-sm">
                                                    {{ $service->icon ?? '💻' }}
                                                </div>

                                                <p class="mt-4 text-sm font-black text-slate-800">
                                                    Layanan HilmiDev
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($service->is_featured)
                                        <span class="absolute left-4 top-4 inline-flex items-center gap-1.5 rounded-full border border-white/40 bg-white/95 px-3 py-2 text-[10px] font-black text-blue-700 shadow-md backdrop-blur">
                                            <i data-lucide="star" class="h-3.5 w-3.5 fill-current"></i>
                                            Layanan Pilihan
                                        </span>
                                    @endif

                                    <div class="absolute bottom-4 right-4 flex h-11 w-11 items-center justify-center rounded-xl bg-blue-700 text-white shadow-lg shadow-blue-700/20 transition group-hover:bg-blue-800">
                                        <i data-lucide="arrow-up-right" class="h-5 w-5"></i>
                                    </div>
                                </div>
                            </a>

                            {{-- KONTEN --}}
                            <div class="flex flex-1 flex-col p-6">
                                <div class="mb-4 flex items-center justify-between gap-3">
                                    <span class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.14em] text-blue-700">
                                        <i data-lucide="badge-check" class="h-4 w-4"></i>
                                        Jasa Profesional
                                    </span>

                                    <span class="inline-flex items-center gap-1.5 text-[10px] font-bold text-emerald-600">
                                        <i data-lucide="circle-check" class="h-3.5 w-3.5"></i>
                                        Tersedia
                                    </span>
                                </div>

                                <a href="{{ route('services.show', $service) }}">
                                    <h2 class="text-xl font-black leading-7 text-slate-950 transition group-hover:text-blue-700">
                                        {{ $service->title }}
                                    </h2>
                                </a>

                                <p class="mt-3 line-clamp-3 text-sm font-medium leading-6 text-slate-500">
                                    {{ $service->short_description ?: $service->description }}
                                </p>

                                @if (count($serviceFeatures))
                                    <div class="mt-5 space-y-3 border-t border-slate-100 pt-5">
                                        @foreach ($serviceFeatures as $feature)
                                            <div class="flex items-start gap-3 text-xs font-semibold leading-5 text-slate-600">
                                                <div class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-700">
                                                    <i data-lucide="check" class="h-3 w-3"></i>
                                                </div>

                                                <span class="line-clamp-1">
                                                    {{ $feature }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="mt-auto pt-6">
                                    <div class="mb-5 flex items-end justify-between border-t border-slate-100 pt-5">
                                        <div>
                                            <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">
                                                Harga mulai
                                            </p>

                                            <p class="mt-2 text-2xl font-black text-blue-800">
                                                Rp {{ number_format($service->starting_price, 0, ',', '.') }}
                                            </p>
                                        </div>

                                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl border border-blue-100 bg-blue-50 text-blue-700">
                                            <i data-lucide="briefcase-business" class="h-5 w-5"></i>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <a href="{{ route('services.show', $service) }}"
                                           class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-xs font-black text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                            Detail Jasa
                                        </a>

                                        @auth
                                            <a href="{{ route('project-requests.create', $service) }}"
                                               class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-4 py-3.5 text-xs font-black text-white shadow-lg shadow-blue-700/15 transition hover:bg-blue-800">
                                                Request Project
                                                <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}"
                                               class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-4 py-3.5 text-xs font-black text-white shadow-lg shadow-blue-700/15 transition hover:bg-blue-800">
                                                Request Project
                                                <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-10 border-t border-slate-200 pt-7">
                    {{ $services->onEachSide(1)->fragment('layanan')->links() }}
                </div>
            @else
                <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white px-6 py-20 text-center">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[1.75rem] border border-blue-100 bg-blue-50 text-blue-700">
                        <i data-lucide="search-x" class="h-9 w-9"></i>
                    </div>

                    <h2 class="mt-6 text-2xl font-black text-slate-950">
                        Layanan tidak ditemukan
                    </h2>

                    <p class="mx-auto mt-3 max-w-xl text-sm font-medium leading-7 text-slate-500">
                        Belum ada layanan yang sesuai dengan pencarian tersebut.
                        Gunakan kata kunci lain atau hapus filter pencarian.
                    </p>

                    <a href="{{ route('services.index') }}"
                       class="mt-7 inline-flex items-center gap-2 rounded-2xl bg-blue-700 px-6 py-4 text-sm font-black text-white transition hover:bg-blue-800">
                        <i data-lucide="rotate-ccw" class="h-5 w-5"></i>
                        Hapus Pencarian
                    </a>
                </div>
            @endif
        </main>

        {{-- KEUNGGULAN --}}
        <section class="border-t border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
                <div class="mb-10 max-w-3xl">
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-blue-700">
                        Kenapa HilmiDev
                    </p>

                    <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950">
                        Project dikerjakan secara terarah
                    </h2>

                    <p class="mt-4 text-sm font-medium leading-7 text-slate-500">
                        Mulai dari analisis kebutuhan hingga implementasi,
                        setiap tahap dikerjakan secara jelas dan transparan.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-700 text-white">
                            <i data-lucide="palette" class="h-6 w-6"></i>
                        </div>

                        <h3 class="mt-5 text-sm font-black text-slate-950">
                            Desain Profesional
                        </h3>

                        <p class="mt-2 text-xs font-medium leading-6 text-slate-500">
                            Tampilan modern, bersih, responsif, dan nyaman digunakan.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-6 transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-700 text-white">
                            <i data-lucide="settings-2" class="h-6 w-6"></i>
                        </div>

                        <h3 class="mt-5 text-sm font-black text-slate-950">
                            Fitur Custom
                        </h3>

                        <p class="mt-2 text-xs font-medium leading-6 text-slate-500">
                            Alur dan fitur aplikasi dapat disesuaikan dengan kebutuhan.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-6 transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-700 text-white">
                            <i data-lucide="shield-check" class="h-6 w-6"></i>
                        </div>

                        <h3 class="mt-5 text-sm font-black text-slate-950">
                            Aman dan Stabil
                        </h3>

                        <p class="mt-2 text-xs font-medium leading-6 text-slate-500">
                            Sistem diuji sebelum digunakan dan diserahkan kepada client.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-6 transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-700 text-white">
                            <i data-lucide="headphones" class="h-6 w-6"></i>
                        </div>

                        <h3 class="mt-5 text-sm font-black text-slate-950">
                            Support Teknis
                        </h3>

                        <p class="mt-2 text-xs font-medium leading-6 text-slate-500">
                            Mendapatkan pendampingan penggunaan dan implementasi.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- PROSES --}}
        <section class="border-t border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
                <div class="mb-10 max-w-3xl">
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-blue-700">
                        Proses Pengerjaan
                    </p>

                    <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950">
                        Empat langkah menuju aplikasi siap pakai
                    </h2>
                </div>

                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    @php
                        $processes = [
                            [
                                'number' => '01',
                                'icon' => 'messages-square',
                                'title' => 'Konsultasi',
                                'description' => 'Diskusi kebutuhan, fitur, tujuan, dan target pengguna aplikasi.',
                            ],
                            [
                                'number' => '02',
                                'icon' => 'clipboard-list',
                                'title' => 'Perencanaan',
                                'description' => 'Penyusunan fitur, estimasi biaya, scope, dan waktu pengerjaan.',
                            ],
                            [
                                'number' => '03',
                                'icon' => 'code-2',
                                'title' => 'Development',
                                'description' => 'Proses desain, coding, pengujian, revisi, dan penyempurnaan.',
                            ],
                            [
                                'number' => '04',
                                'icon' => 'rocket',
                                'title' => 'Implementasi',
                                'description' => 'Deploy ke hosting atau server hingga sistem siap digunakan.',
                            ],
                        ];
                    @endphp

                    @foreach ($processes as $process)
                        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6">
                            <div class="flex items-center justify-between">
                                <span class="text-3xl font-black text-blue-100">
                                    {{ $process['number'] }}
                                </span>

                                <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 text-blue-700">
                                    <i data-lucide="{{ $process['icon'] }}" class="h-5 w-5"></i>
                                </div>
                            </div>

                            <h3 class="mt-6 text-base font-black text-slate-950">
                                {{ $process['title'] }}
                            </h3>

                            <p class="mt-3 text-xs font-medium leading-6 text-slate-500">
                                {{ $process['description'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="border-t border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
                <div class="relative overflow-hidden rounded-[2rem] bg-blue-800 px-6 py-10 text-white shadow-2xl shadow-blue-900/20 sm:px-10 lg:flex lg:items-center lg:justify-between">
                    <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>

                    <div class="relative max-w-3xl">
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-blue-200">
                            Siap Memulai Project?
                        </p>

                        <h2 class="mt-3 text-2xl font-black sm:text-3xl">
                            Wujudkan aplikasi sesuai kebutuhanmu
                        </h2>

                        <p class="mt-3 text-sm font-medium leading-7 text-blue-100/80">
                            Pilih salah satu layanan kemudian kirimkan detail kebutuhan
                            project melalui formulir request.
                        </p>
                    </div>

                    @auth
                        <a href="#layanan"
                           class="relative mt-7 inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-6 py-4 text-sm font-black text-blue-800 shadow-xl transition hover:-translate-y-0.5 lg:mt-0">
                            Pilih Layanan
                            <i data-lucide="arrow-up-right" class="h-5 w-5"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="relative mt-7 inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-6 py-4 text-sm font-black text-blue-800 shadow-xl transition hover:-translate-y-0.5 lg:mt-0">
                            Login Member
                            <i data-lucide="arrow-up-right" class="h-5 w-5"></i>
                        </a>
                    @endauth
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
