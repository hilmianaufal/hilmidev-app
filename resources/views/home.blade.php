<x-app-layout>
    <div class="overflow-hidden bg-white">

        {{-- HERO --}}
        <section class="relative overflow-hidden bg-gradient-to-br from-blue-950 via-blue-900 to-blue-700 text-white">
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -left-32 -top-32 h-[30rem] w-[30rem] rounded-full bg-cyan-400/15 blur-3xl"></div>
                <div class="absolute -bottom-44 right-0 h-[32rem] w-[32rem] rounded-full bg-blue-300/15 blur-3xl"></div>
                <div
                    class="absolute inset-0 opacity-[0.04]"
                    style="
                        background-image:
                            linear-gradient(rgba(255,255,255,.8) 1px, transparent 1px),
                            linear-gradient(90deg, rgba(255,255,255,.8) 1px, transparent 1px);
                        background-size: 54px 54px;
                    "
                ></div>
            </div>

            <div class="relative mx-auto grid max-w-7xl items-center gap-12 px-4 py-16 sm:px-6 lg:grid-cols-12 lg:px-8 lg:py-24">
                <div class="lg:col-span-7">
                    <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-black text-blue-100 backdrop-blur-xl">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-cyan-300 opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-cyan-300"></span>
                        </span>
                        Source Code, Jasa Website & Bimbingan Coding
                    </div>

                    <h1 class="mt-7 max-w-4xl text-4xl font-black leading-tight tracking-tight sm:text-5xl lg:text-6xl">
                        Bangun aplikasi lebih cepat bersama
                        <span class="text-cyan-300">HilmiDev</span>
                    </h1>

                    <p class="mt-6 max-w-2xl text-sm font-medium leading-8 text-blue-100/80 sm:text-base">
                        Dapatkan source code siap pakai, layanan pembuatan website custom,
                        serta akses video pembelajaran coding untuk membantu kamu membangun
                        aplikasi dari nol sampai siap digunakan.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a
                            href="{{ route('products.index') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-6 py-4 text-sm font-black text-blue-900 shadow-xl shadow-blue-950/20 transition duration-300 hover:-translate-y-0.5 hover:bg-blue-50"
                        >
                            <i data-lucide="shopping-bag" class="h-5 w-5"></i>
                            Lihat Source Code
                        </a>

                        <a
                            href="{{ route('courses.index') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/20 bg-white/10 px-6 py-4 text-sm font-black text-white backdrop-blur transition duration-300 hover:-translate-y-0.5 hover:bg-white/15"
                        >
                            <i data-lucide="graduation-cap" class="h-5 w-5"></i>
                            Mulai Belajar Coding
                        </a>
                    </div>

                    <div class="mt-9 flex flex-wrap gap-x-6 gap-y-3 text-xs font-bold text-blue-100/75">
                        <span class="inline-flex items-center gap-2">
                            <i data-lucide="badge-check" class="h-4 w-4 text-cyan-300"></i>
                            Source code lengkap
                        </span>

                        <span class="inline-flex items-center gap-2">
                            <i data-lucide="shield-check" class="h-4 w-4 text-cyan-300"></i>
                            Transaksi terdata
                        </span>

                        <span class="inline-flex items-center gap-2">
                            <i data-lucide="headphones" class="h-4 w-4 text-cyan-300"></i>
                            Support teknis
                        </span>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="rounded-[2rem] border border-white/15 bg-white/10 p-4 shadow-2xl shadow-blue-950/30 backdrop-blur-xl">
                        <div class="rounded-[1.6rem] border border-white/10 bg-blue-950/45 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.18em] text-blue-300">
                                        HilmiDev Platform
                                    </p>
                                    <h2 class="mt-2 text-2xl font-black text-white">
                                        Semua kebutuhan digital
                                    </h2>
                                </div>

                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-300 text-blue-950">
                                    <i data-lucide="code-2" class="h-6 w-6"></i>
                                </div>
                            </div>

                            <div class="mt-7 grid grid-cols-2 gap-3">
                                <a
                                    href="#source-code"
                                    class="group rounded-2xl border border-white/10 bg-white/[0.06] p-4 transition hover:bg-white/10"
                                >
                                    <i data-lucide="package-2" class="h-6 w-6 text-cyan-300"></i>
                                    <p class="mt-4 text-sm font-black text-white">Source Code</p>
                                    <p class="mt-1 text-[10px] font-medium text-blue-200">
                                        Siap dikembangkan
                                    </p>
                                </a>

                                <a
                                    href="#jasa-website"
                                    class="group rounded-2xl border border-white/10 bg-white/[0.06] p-4 transition hover:bg-white/10"
                                >
                                    <i data-lucide="briefcase-business" class="h-6 w-6 text-cyan-300"></i>
                                    <p class="mt-4 text-sm font-black text-white">Jasa Website</p>
                                    <p class="mt-1 text-[10px] font-medium text-blue-200">
                                        Sistem custom
                                    </p>
                                </a>

                                <a
                                    href="#kelas-coding"
                                    class="group rounded-2xl border border-white/10 bg-white/[0.06] p-4 transition hover:bg-white/10"
                                >
                                    <i data-lucide="circle-play" class="h-6 w-6 text-cyan-300"></i>
                                    <p class="mt-4 text-sm font-black text-white">Video Coding</p>
                                    <p class="mt-1 text-[10px] font-medium text-blue-200">
                                        Belajar bertahap
                                    </p>
                                </a>

                                <a
                                    href="#membership"
                                    class="group rounded-2xl border border-white/10 bg-white/[0.06] p-4 transition hover:bg-white/10"
                                >
                                    <i data-lucide="crown" class="h-6 w-6 text-cyan-300"></i>
                                    <p class="mt-4 text-sm font-black text-white">Membership</p>
                                    <p class="mt-1 text-[10px] font-medium text-blue-200">
                                        Akses kelas premium
                                    </p>
                                </a>
                            </div>

                            <div class="mt-4 rounded-2xl border border-cyan-300/20 bg-cyan-300/10 p-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-300 text-blue-950">
                                        <i data-lucide="sparkles" class="h-5 w-5"></i>
                                    </div>

                                    <div>
                                        <p class="text-xs font-black text-white">
                                            Belajar sambil membuat project
                                        </p>
                                        <p class="mt-1 text-[10px] font-medium text-blue-200">
                                            Materi video, source code, dan latihan tersedia untuk member.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- STATISTICS --}}
        <section class="relative z-10 -mt-1 border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 divide-x divide-y divide-slate-200 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl shadow-slate-900/[0.05] sm:grid-cols-4 sm:divide-y-0">
                    @php
                        $homeStats = [
                            ['label' => 'Source Code', 'value' => $statistics['products'], 'icon' => 'package-2'],
                            ['label' => 'Layanan', 'value' => $statistics['services'], 'icon' => 'briefcase-business'],
                            ['label' => 'Kelas Coding', 'value' => $statistics['courses'], 'icon' => 'graduation-cap'],
                            ['label' => 'Member Aktif', 'value' => $statistics['members'], 'icon' => 'users-round'],
                        ];
                    @endphp

                    @foreach ($homeStats as $stat)
                        <div class="flex items-center gap-3 p-5 sm:justify-center">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                                <i data-lucide="{{ $stat['icon'] }}" class="h-5 w-5"></i>
                            </div>

                            <div>
                                <p class="text-xl font-black text-slate-950">
                                    {{ number_format($stat['value']) }}+
                                </p>
                                <p class="mt-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                    {{ $stat['label'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- SOURCE CODE --}}
        <section id="source-code" class="scroll-mt-24 border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
                <div class="mb-10 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-[0.18em] text-blue-700">
                            <i data-lucide="package-2" class="h-4 w-4"></i>
                            Source Code Premium
                        </div>

                        <h2 class="mt-4 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">
                            Mulai project tanpa membuat semuanya dari nol
                        </h2>

                        <p class="mt-4 text-sm font-medium leading-7 text-slate-500">
                            Pilih source code siap pakai untuk aplikasi sekolah, koperasi,
                            kasir, keuangan, absensi, administrasi, dan kebutuhan lainnya.
                        </p>
                    </div>

                    <a
                        href="{{ route('products.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-black text-blue-700 transition hover:text-blue-900"
                    >
                        Lihat Semua Source Code
                        <i data-lucide="arrow-right" class="h-4 w-4"></i>
                    </a>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @forelse ($featuredProducts as $product)
                        @php
                            $discountPercent = $product->discount_price && $product->price > 0
                                ? max(0, round((($product->price - $product->discount_price) / $product->price) * 100))
                                : null;
                        @endphp

                        <article class="group flex h-full flex-col overflow-hidden rounded-[1.6rem] border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-700/[0.07]">
                            <a
                                href="{{ route('products.show', ['product' => $product->slug]) }}"
                                class="block p-3 pb-0"
                            >
                                <div class="relative aspect-[16/10] overflow-hidden rounded-[1.15rem] border border-slate-100 bg-slate-50">
                                    @if ($product->thumbnail)
                                        <img
                                            src="{{ asset('storage/' . $product->thumbnail) }}"
                                            alt="{{ $product->name }}"
                                            loading="lazy"
                                            class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.04]"
                                        >
                                    @else
                                        <div class="flex h-full items-center justify-center bg-blue-50 text-blue-700">
                                            <i data-lucide="code-2" class="h-10 w-10"></i>
                                        </div>
                                    @endif

                                    <div class="absolute left-3 top-3 flex flex-wrap gap-2">
                                        @if ($product->is_featured)
                                            <span class="rounded-full bg-white/95 px-3 py-1.5 text-[9px] font-black text-blue-700 shadow">
                                                PILIHAN
                                            </span>
                                        @endif

                                        @if ($discountPercent)
                                            <span class="rounded-full bg-blue-700 px-3 py-1.5 text-[9px] font-black text-white shadow">
                                                -{{ $discountPercent }}%
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </a>

                            <div class="flex flex-1 flex-col p-5">
                                <p class="text-[9px] font-black uppercase tracking-[0.14em] text-blue-700">
                                    {{ $product->category?->name ?? 'Source Code' }}
                                </p>

                                <a href="{{ route('products.show', ['product' => $product->slug]) }}">
                                    <h3 class="mt-3 line-clamp-2 text-base font-black leading-6 text-slate-950 transition group-hover:text-blue-700">
                                        {{ $product->name }}
                                    </h3>
                                </a>

                                <p class="mt-3 line-clamp-2 text-xs font-medium leading-5 text-slate-500">
                                    {{ $product->short_description ?: $product->description }}
                                </p>

                                <div class="mt-auto flex items-end justify-between border-t border-slate-100 pt-5">
                                    <div>
                                        @if ($product->discount_price)
                                            <p class="text-[9px] font-bold text-slate-400 line-through">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </p>
                                        @endif

                                        <p class="mt-1 text-lg font-black text-blue-800">
                                            Rp {{ number_format($product->final_price, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-700 text-white transition group-hover:bg-blue-800">
                                        <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full rounded-[2rem] border border-dashed border-slate-300 px-6 py-16 text-center">
                            <i data-lucide="package-x" class="mx-auto h-10 w-10 text-slate-300"></i>
                            <p class="mt-4 font-black text-slate-800">Source code belum tersedia.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- SERVICES --}}
        <section id="jasa-website" class="scroll-mt-24 border-b border-slate-200 bg-slate-50">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
                <div class="mx-auto mb-10 max-w-3xl text-center">
                    <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-[0.18em] text-blue-700">
                        <i data-lucide="briefcase-business" class="h-4 w-4"></i>
                        Jasa Website & Aplikasi
                    </div>

                    <h2 class="mt-4 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">
                        Belum menemukan sistem yang sesuai?
                    </h2>

                    <p class="mt-4 text-sm font-medium leading-7 text-slate-500">
                        HilmiDev melayani pembuatan website dan aplikasi custom
                        berdasarkan kebutuhan bisnis maupun instansi.
                    </p>
                </div>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @forelse ($featuredServices as $service)
                        @php
                            $serviceFeatures = collect($service->features ?? [])->take(4);
                        @endphp

                        <article class="group flex h-full flex-col rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-700/[0.07]">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-700 text-2xl text-white shadow-lg shadow-blue-700/20">
                                    {{ $service->icon ?? '💻' }}
                                </div>

                                @if ($service->is_featured)
                                    <span class="rounded-full bg-blue-50 px-3 py-1.5 text-[9px] font-black text-blue-700">
                                        POPULER
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('services.show', ['service' => $service->slug]) }}">
                                <h3 class="mt-6 text-xl font-black leading-7 text-slate-950 transition group-hover:text-blue-700">
                                    {{ $service->title }}
                                </h3>
                            </a>

                            <p class="mt-3 line-clamp-3 text-sm font-medium leading-6 text-slate-500">
                                {{ $service->short_description ?: $service->description }}
                            </p>

                            @if ($serviceFeatures->count())
                                <div class="mt-5 space-y-3 border-t border-slate-100 pt-5">
                                    @foreach ($serviceFeatures as $feature)
                                        <div class="flex items-start gap-3 text-xs font-semibold leading-5 text-slate-600">
                                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-700">
                                                <i data-lucide="check" class="h-3 w-3"></i>
                                            </span>
                                            {{ $feature }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-auto flex items-end justify-between border-t border-slate-100 pt-6">
                                <div>
                                    <p class="text-[9px] font-black uppercase tracking-wider text-slate-400">
                                        Harga mulai
                                    </p>

                                    <p class="mt-2 text-xl font-black text-blue-800">
                                        Rp {{ number_format($service->starting_price, 0, ',', '.') }}
                                    </p>
                                </div>

                                <a
                                    href="{{ route('services.show', ['service' => $service->slug]) }}"
                                    class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-blue-700 px-4 text-xs font-black text-white transition hover:bg-blue-800"
                                >
                                    Detail
                                    <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                                </a>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full rounded-[2rem] border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                            <p class="font-black text-slate-800">Layanan belum tersedia.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-9 text-center">
                    <a
                        href="{{ route('services.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-700 px-6 py-4 text-sm font-black text-white shadow-xl shadow-blue-700/20 transition hover:-translate-y-0.5 hover:bg-blue-800"
                    >
                        Lihat Semua Layanan
                        <i data-lucide="arrow-right" class="h-5 w-5"></i>
                    </a>
                </div>
            </div>
        </section>

        {{-- COURSES --}}
        <section id="kelas-coding" class="scroll-mt-24 border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
                <div class="mb-10 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-[0.18em] text-blue-700">
                            <i data-lucide="circle-play" class="h-4 w-4"></i>
                            Video Pembelajaran Coding
                        </div>

                        <h2 class="mt-4 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">
                            Belajar membangun aplikasi secara bertahap
                        </h2>

                        <p class="mt-4 text-sm font-medium leading-7 text-slate-500">
                            Ikuti kelas coding dari persiapan project, database, pembuatan fitur,
                            desain antarmuka, sampai aplikasi siap dijalankan.
                        </p>
                    </div>

                    <a
                        href="{{ route('courses.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-black text-blue-700 hover:text-blue-900"
                    >
                        Lihat Semua Kelas
                        <i data-lucide="arrow-right" class="h-4 w-4"></i>
                    </a>
                </div>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @forelse ($featuredCourses as $course)
                        <article class="group overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-700/[0.07]">
                            <a
                                href="{{ route('courses.show', ['course' => $course->slug]) }}"
                                class="block p-3 pb-0"
                            >
                                <div class="relative aspect-video overflow-hidden rounded-[1.3rem] bg-slate-50">
                                    @if ($course->thumbnail)
                                        <img
                                            src="{{ asset('storage/' . $course->thumbnail) }}"
                                            alt="{{ $course->title }}"
                                            loading="lazy"
                                            class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.04]"
                                        >
                                    @else
                                        <div class="flex h-full items-center justify-center bg-blue-50 text-blue-700">
                                            <i data-lucide="graduation-cap" class="h-12 w-12"></i>
                                        </div>
                                    @endif

                                    <span class="absolute left-4 top-4 rounded-full bg-white/95 px-3 py-2 text-[9px] font-black uppercase text-blue-700 shadow">
                                        {{ $course->level }}
                                    </span>
                                </div>
                            </a>

                            <div class="p-6">
                                <p class="text-[9px] font-black uppercase tracking-[0.14em] text-blue-700">
                                    {{ $course->technology ?: 'Kelas Coding' }}
                                </p>

                                <a href="{{ route('courses.show', ['course' => $course->slug]) }}">
                                    <h3 class="mt-3 text-xl font-black leading-7 text-slate-950 transition group-hover:text-blue-700">
                                        {{ $course->title }}
                                    </h3>
                                </a>

                                <p class="mt-3 line-clamp-2 text-sm font-medium leading-6 text-slate-500">
                                    {{ $course->subtitle ?: $course->description }}
                                </p>

                                <div class="mt-5 grid grid-cols-3 divide-x divide-slate-200 rounded-2xl border border-slate-200 py-3 text-center">
                                    <div class="px-2">
                                        <p class="text-sm font-black text-slate-900">{{ $course->modules_count }}</p>
                                        <p class="mt-1 text-[9px] font-bold text-slate-400">Modul</p>
                                    </div>

                                    <div class="px-2">
                                        <p class="text-sm font-black text-slate-900">{{ $course->lessons_count }}</p>
                                        <p class="mt-1 text-[9px] font-bold text-slate-400">Video</p>
                                    </div>

                                    <div class="px-2">
                                        <p class="text-sm font-black text-slate-900">{{ $course->formatted_duration }}</p>
                                        <p class="mt-1 text-[9px] font-bold text-slate-400">Durasi</p>
                                    </div>
                                </div>

                                <a
                                    href="{{ route('courses.show', ['course' => $course->slug]) }}"
                                    class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-3.5 text-xs font-black text-white transition hover:bg-blue-800"
                                >
                                    Lihat Materi Kelas
                                    <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                                </a>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full rounded-[2rem] border border-dashed border-slate-300 px-6 py-16 text-center">
                            <p class="font-black text-slate-800">Kelas coding belum tersedia.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- MEMBERSHIP --}}
        <section id="membership" class="scroll-mt-24 border-b border-slate-200 bg-slate-50">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
                <div class="mx-auto mb-11 max-w-3xl text-center">
                    <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-[0.18em] text-blue-700">
                        <i data-lucide="crown" class="h-4 w-4"></i>
                        Paket Membership
                    </div>

                    <h2 class="mt-4 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">
                        Pilih durasi bimbingan yang sesuai
                    </h2>

                    <p class="mt-4 text-sm font-medium leading-7 text-slate-500">
                        Member mendapatkan akses video pembelajaran, file materi,
                        source code latihan, progress belajar, dan update kelas.
                    </p>
                </div>

                <div class="mx-auto grid max-w-6xl gap-6 lg:grid-cols-3">
                    @forelse ($membershipPlans as $plan)
                        @php
                            $features = collect($plan->features ?? [])->take(6);
                            $isFeatured = (bool) $plan->is_featured;
                        @endphp

                        <article
                            @class([
                                'relative flex h-full flex-col overflow-hidden rounded-[2rem] border p-7 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl',
                                'border-blue-700 bg-blue-800 text-white shadow-blue-800/20' => $isFeatured,
                                'border-slate-200 bg-white text-slate-950 hover:border-blue-200' => ! $isFeatured,
                            ])
                        >
                            @if ($isFeatured)
                                <div class="absolute right-0 top-0 rounded-bl-2xl bg-cyan-300 px-4 py-2 text-[9px] font-black uppercase tracking-wider text-blue-950">
                                    Paling Populer
                                </div>
                            @endif

                            <div>
                                <div
                                    @class([
                                        'flex h-12 w-12 items-center justify-center rounded-2xl',
                                        'bg-white/10 text-cyan-300' => $isFeatured,
                                        'bg-blue-50 text-blue-700' => ! $isFeatured,
                                    ])
                                >
                                    <i data-lucide="badge-check" class="h-6 w-6"></i>
                                </div>

                                <h3 class="mt-6 text-2xl font-black">
                                    {{ $plan->name }}
                                </h3>

                                <p
                                    @class([
                                        'mt-3 min-h-12 text-sm font-medium leading-6',
                                        'text-blue-100/80' => $isFeatured,
                                        'text-slate-500' => ! $isFeatured,
                                    ])
                                >
                                    {{ $plan->description }}
                                </p>
                            </div>

                            <div class="mt-7">
                                <p
                                    @class([
                                        'text-[10px] font-black uppercase tracking-[0.15em]',
                                        'text-blue-200' => $isFeatured,
                                        'text-slate-400' => ! $isFeatured,
                                    ])
                                >
                                    Biaya membership
                                </p>

                                <p class="mt-2 text-3xl font-black">
                                    Rp {{ number_format($plan->price, 0, ',', '.') }}
                                </p>

                                <p
                                    @class([
                                        'mt-2 text-xs font-bold',
                                        'text-blue-200' => $isFeatured,
                                        'text-slate-400' => ! $isFeatured,
                                    ])
                                >
                                    {{ $plan->duration_days ? $plan->duration_days . ' hari akses' : 'Akses selamanya' }}
                                </p>
                            </div>

                            <div
                                @class([
                                    'my-7 border-t',
                                    'border-white/15' => $isFeatured,
                                    'border-slate-200' => ! $isFeatured,
                                ])
                            ></div>

                            <div class="flex-1 space-y-3">
                                @forelse ($features as $feature)
                                    <div
                                        @class([
                                            'flex items-start gap-3 text-xs font-semibold leading-5',
                                            'text-blue-100' => $isFeatured,
                                            'text-slate-600' => ! $isFeatured,
                                        ])
                                    >
                                        <span
                                            @class([
                                                'mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full',
                                                'bg-cyan-300 text-blue-950' => $isFeatured,
                                                'bg-blue-50 text-blue-700' => ! $isFeatured,
                                            ])
                                        >
                                            <i data-lucide="check" class="h-3 w-3"></i>
                                        </span>

                                        {{ $feature }}
                                    </div>
                                @empty
                                    <div class="flex items-start gap-3 text-xs font-semibold">
                                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-700">
                                            <i data-lucide="check" class="h-3 w-3"></i>
                                        </span>
                                        Akses video pembelajaran coding
                                    </div>

                                    <div class="flex items-start gap-3 text-xs font-semibold">
                                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-700">
                                            <i data-lucide="check" class="h-3 w-3"></i>
                                        </span>
                                        Download source code materi
                                    </div>
                                @endforelse
                            </div>

                            @auth
                                @if ($hasActiveMembership)
                                    <a
                                        href="{{ route('member.dashboard') }}"
                                        @class([
                                            'mt-8 inline-flex w-full items-center justify-center gap-2 rounded-2xl px-5 py-4 text-sm font-black transition',
                                            'bg-white text-blue-900 hover:bg-blue-50' => $isFeatured,
                                            'bg-blue-700 text-white hover:bg-blue-800' => ! $isFeatured,
                                        ])
                                    >
                                        Buka Dashboard Member
                                        <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                                    </a>
                                @else
                                    <a
                                        href="{{ route('courses.index') }}"
                                        @class([
                                            'mt-8 inline-flex w-full items-center justify-center gap-2 rounded-2xl px-5 py-4 text-sm font-black transition',
                                            'bg-white text-blue-900 hover:bg-blue-50' => $isFeatured,
                                            'bg-blue-700 text-white hover:bg-blue-800' => ! $isFeatured,
                                        ])
                                    >
                                        Lihat Program Bimbingan
                                        <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                                    </a>
                                @endif
                            @else
                                <a
                                    href="{{ route('login') }}"
                                    @class([
                                        'mt-8 inline-flex w-full items-center justify-center gap-2 rounded-2xl px-5 py-4 text-sm font-black transition',
                                        'bg-white text-blue-900 hover:bg-blue-50' => $isFeatured,
                                        'bg-blue-700 text-white hover:bg-blue-800' => ! $isFeatured,
                                    ])
                                >
                                    Login Member
                                    <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                                </a>
                            @endauth
                        </article>
                    @empty
                        <div class="col-span-full rounded-[2rem] border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                            <p class="font-black text-slate-800">Paket membership belum tersedia.</p>
                        </div>
                    @endforelse
                </div>

                <p class="mt-7 text-center text-xs font-medium text-slate-400">
                    Akun member dibuat dan diaktifkan langsung oleh admin HilmiDev.
                </p>
            </div>
        </section>

        {{-- PORTFOLIO --}}
        @if ($portfolios->count())
            <section class="border-b border-slate-200 bg-white">
                <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
                    <div class="mx-auto mb-10 max-w-3xl text-center">
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-blue-700">
                            Portfolio HilmiDev
                        </p>

                        <h2 class="mt-4 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">
                            Beberapa project yang telah dikerjakan
                        </h2>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($portfolios as $portfolio)
                            <article class="group overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl">
                                <div class="p-3 pb-0">
                                    <div class="aspect-video overflow-hidden rounded-[1.3rem] bg-slate-50">
                                        @if ($portfolio->thumbnail)
                                            <img
                                                src="{{ asset('storage/' . $portfolio->thumbnail) }}"
                                                alt="{{ $portfolio->title }}"
                                                loading="lazy"
                                                class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.04]"
                                            >
                                        @else
                                            <div class="flex h-full items-center justify-center bg-blue-50 text-blue-700">
                                                <i data-lucide="panels-top-left" class="h-11 w-11"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="p-6">
                                    <p class="text-[9px] font-black uppercase tracking-[0.14em] text-blue-700">
                                        {{ $portfolio->category ?: 'Project Digital' }}
                                    </p>

                                    <h3 class="mt-3 text-xl font-black text-slate-950">
                                        {{ $portfolio->title }}
                                    </h3>

                                    <p class="mt-3 line-clamp-2 text-sm font-medium leading-6 text-slate-500">
                                        {{ $portfolio->short_description ?: $portfolio->description }}
                                    </p>

                                    <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-5">
                                        <p class="text-xs font-bold text-slate-400">
                                            {{ $portfolio->client_name ?: 'HilmiDev Client' }}
                                        </p>

                                        @if ($portfolio->project_url)
                                            <a
                                                href="{{ $portfolio->project_url }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-700 text-white transition hover:bg-blue-800"
                                            >
                                                <i data-lucide="external-link" class="h-4 w-4"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- TESTIMONIALS --}}
        <section id="testimoni" class="scroll-mt-24 border-b border-slate-200 bg-slate-50">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
                <div class="mx-auto mb-10 max-w-3xl text-center">
                    <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-[0.18em] text-blue-700">
                        <i data-lucide="messages-square" class="h-4 w-4"></i>
                        Testimoni Client
                    </div>

                    <h2 class="mt-4 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">
                        Pengalaman mereka bersama HilmiDev
                    </h2>
                </div>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @forelse ($testimonials as $testimonial)
                        <article class="flex h-full flex-col rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl">
                            <div class="flex gap-1">
                                @for ($star = 1; $star <= 5; $star++)
                                    <i
                                        data-lucide="star"
                                        @class([
                                            'h-4 w-4',
                                            'fill-amber-400 text-amber-400' => $star <= $testimonial->rating,
                                            'text-slate-200' => $star > $testimonial->rating,
                                        ])
                                    ></i>
                                @endfor
                            </div>

                            <p class="mt-5 flex-1 text-sm font-medium leading-7 text-slate-600">
                                “{{ $testimonial->review }}”
                            </p>

                            <div class="mt-6 flex items-center gap-4 border-t border-slate-100 pt-5">
                                @if ($testimonial->photo)
                                    <img
                                        src="{{ asset('storage/' . $testimonial->photo) }}"
                                        alt="{{ $testimonial->name }}"
                                        class="h-12 w-12 rounded-2xl object-cover"
                                    >
                                @else
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-700 font-black text-white">
                                        {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                                    </div>
                                @endif

                                <div class="min-w-0">
                                    <p class="truncate text-sm font-black text-slate-950">
                                        {{ $testimonial->name }}
                                    </p>

                                    <p class="mt-1 truncate text-xs font-medium text-slate-400">
                                        {{ collect([$testimonial->position, $testimonial->company])->filter()->implode(' · ') ?: 'Client HilmiDev' }}
                                    </p>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full rounded-[2rem] border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                            <p class="font-black text-slate-800">Testimoni belum tersedia.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- BLOG --}}
        @if ($latestPosts->count())
            <section class="border-b border-slate-200 bg-white">
                <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
                    <div class="mb-10 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-blue-700">
                                Artikel Terbaru
                            </p>

                            <h2 class="mt-4 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">
                                Insight website dan coding
                            </h2>
                        </div>

                        <a
                            href="{{ route('blog.index') }}"
                            class="inline-flex items-center gap-2 text-sm font-black text-blue-700 hover:text-blue-900"
                        >
                            Lihat Semua Artikel
                            <i data-lucide="arrow-right" class="h-4 w-4"></i>
                        </a>
                    </div>

                    <div class="grid gap-6 md:grid-cols-3">
                        @foreach ($latestPosts as $post)
                            <article class="group overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl">
                                <a
                                    href="{{ route('blog.show', ['post' => $post->slug]) }}"
                                    class="block p-3 pb-0"
                                >
                                    <div class="aspect-video overflow-hidden rounded-[1.3rem] bg-slate-50">
                                        @if ($post->thumbnail)
                                            <img
                                                src="{{ asset('storage/' . $post->thumbnail) }}"
                                                alt="{{ $post->title }}"
                                                loading="lazy"
                                                class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.04]"
                                            >
                                        @else
                                            <div class="flex h-full items-center justify-center bg-blue-50 text-blue-700">
                                                <i data-lucide="newspaper" class="h-11 w-11"></i>
                                            </div>
                                        @endif
                                    </div>
                                </a>

                                <div class="p-6">
                                    <p class="text-[9px] font-black uppercase tracking-[0.14em] text-blue-700">
                                        {{ $post->published_at?->translatedFormat('d M Y') ?? $post->created_at->translatedFormat('d M Y') }}
                                    </p>

                                    <a href="{{ route('blog.show', ['post' => $post->slug]) }}">
                                        <h3 class="mt-3 line-clamp-2 text-xl font-black leading-7 text-slate-950 transition group-hover:text-blue-700">
                                            {{ $post->title }}
                                        </h3>
                                    </a>

                                    <p class="mt-3 line-clamp-3 text-sm font-medium leading-6 text-slate-500">
                                        {{ $post->excerpt }}
                                    </p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- WHY HILMIDEV --}}
        <section class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
                <div class="grid items-center gap-10 lg:grid-cols-12">
                    <div class="lg:col-span-5">
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-blue-700">
                            Kenapa HilmiDev
                        </p>

                        <h2 class="mt-4 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">
                            Satu tempat untuk membeli, membuat, dan belajar
                        </h2>

                        <p class="mt-5 text-sm font-medium leading-7 text-slate-500">
                            Tidak hanya menyediakan source code, HilmiDev juga membantu
                            pembuatan sistem custom serta menyediakan pembelajaran coding
                            agar kamu memahami cara aplikasi tersebut dibangun.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 lg:col-span-7">
                        @php
                            $advantages = [
                                ['icon' => 'palette', 'title' => 'Tampilan Profesional', 'description' => 'Desain modern, responsif, dan nyaman digunakan.'],
                                ['icon' => 'code-2', 'title' => 'Source Code Lengkap', 'description' => 'Struktur project siap dikembangkan sesuai kebutuhan.'],
                                ['icon' => 'graduation-cap', 'title' => 'Belajar Bertahap', 'description' => 'Materi video disusun dari dasar sampai implementasi.'],
                                ['icon' => 'headphones', 'title' => 'Support Teknis', 'description' => 'Mendapat bantuan penggunaan dan pengembangan sistem.'],
                            ];
                        @endphp

                        @foreach ($advantages as $advantage)
                            <div class="rounded-2xl border border-slate-200 bg-white p-6 transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-700 text-white">
                                    <i data-lucide="{{ $advantage['icon'] }}" class="h-6 w-6"></i>
                                </div>

                                <h3 class="mt-5 text-base font-black text-slate-950">
                                    {{ $advantage['title'] }}
                                </h3>

                                <p class="mt-2 text-xs font-medium leading-6 text-slate-500">
                                    {{ $advantage['description'] }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- FINAL CTA --}}
        <section class="bg-white">
            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
                <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-r from-blue-900 to-blue-700 px-6 py-11 text-white shadow-2xl shadow-blue-900/20 sm:px-10 lg:flex lg:items-center lg:justify-between">
                    <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-cyan-300/15 blur-3xl"></div>

                    <div class="relative max-w-3xl">
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-cyan-300">
                            Siap Memulai?
                        </p>

                        <h2 class="mt-3 text-2xl font-black sm:text-3xl">
                            Mulai project atau belajar coding hari ini
                        </h2>

                        <p class="mt-3 text-sm font-medium leading-7 text-blue-100/80">
                            Pilih source code, konsultasikan aplikasi custom,
                            atau masuk ke akun member bimbingan coding HilmiDev.
                        </p>
                    </div>

                    <div class="relative mt-7 flex flex-col gap-3 sm:flex-row lg:mt-0">
                        <a
                            href="{{ route('services.index') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/20 bg-white/10 px-6 py-4 text-sm font-black text-white transition hover:bg-white/15"
                        >
                            Konsultasi Project
                        </a>

                        @auth
                            <a
                                href="{{ $hasActiveMembership ? route('member.dashboard') : route('courses.index') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-6 py-4 text-sm font-black text-blue-900 transition hover:bg-blue-50"
                            >
                                {{ $hasActiveMembership ? 'Dashboard Member' : 'Lihat Kelas Coding' }}
                                <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-6 py-4 text-sm font-black text-blue-900 transition hover:bg-blue-50"
                            >
                                Login Member
                                <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                            </a>
                        @endauth
                    </div>
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
