<x-app-layout>
    <div class="min-h-screen bg-white">
        <section class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                @if (session('error'))
                    <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm font-bold text-amber-800">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="flex flex-col gap-7 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-blue-700">
                            HilmiDev Learning
                        </p>

                        <h1 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl lg:text-5xl">
                            Belajar Membuat Website dan Aplikasi
                        </h1>

                        <p class="mt-4 text-sm font-medium leading-7 text-slate-500 sm:text-base">
                            Video pembelajaran terstruktur dari persiapan project,
                            database, CRUD, UI, sampai deploy aplikasi ke server.
                        </p>
                    </div>

                    @auth
                        @if ($hasActiveMembership)
                            <a href="{{ route('member.dashboard') }}"
                               class="inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-700 px-6 py-4 text-sm font-black text-white shadow-lg shadow-blue-700/20 transition hover:bg-blue-800">
                                <i data-lucide="graduation-cap" class="h-5 w-5"></i>
                                Dashboard Member
                            </a>
                        @else
                            <div class="rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4">
                                <p class="text-xs font-black uppercase tracking-wider text-blue-700">Status</p>
                                <p class="mt-1 text-sm font-bold text-slate-700">Membership belum aktif</p>
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-700 px-6 py-4 text-sm font-black text-white shadow-lg shadow-blue-700/20 transition hover:bg-blue-800">
                            <i data-lucide="user-plus" class="h-5 w-5"></i>
                            Login Member
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        <section class="sticky top-[78px] z-30 border-b border-slate-200 bg-white/95 backdrop-blur-xl">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <form method="GET" action="{{ route('courses.index') }}" class="grid gap-3 md:grid-cols-12">
                    <div class="relative md:col-span-8">
                        <i data-lucide="search" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"></i>
                        <input type="search"
                               name="q"
                               value="{{ $search }}"
                               placeholder="Cari Laravel, PHP, Tailwind, Flutter..."
                               class="h-14 w-full rounded-2xl border-slate-200 bg-white pl-12 pr-4 text-sm font-semibold focus:border-blue-400 focus:ring-blue-100">
                    </div>

                    <div class="md:col-span-3">
                        <select name="level"
                                onchange="this.form.submit()"
                                class="h-14 w-full rounded-2xl border-slate-200 bg-white px-4 text-sm font-bold focus:border-blue-400 focus:ring-blue-100">
                            <option value="">Semua level</option>
                            <option value="pemula" @selected($level === 'pemula')>Pemula</option>
                            <option value="menengah" @selected($level === 'menengah')>Menengah</option>
                            <option value="mahir" @selected($level === 'mahir')>Mahir</option>
                        </select>
                    </div>

                    <button class="flex h-14 items-center justify-center rounded-2xl bg-blue-700 text-white md:col-span-1">
                        <i data-lucide="search" class="h-5 w-5"></i>
                    </button>
                </form>
            </div>
        </section>

        <main class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            @if ($courses->count())
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($courses as $course)
                        <article class="group flex h-full flex-col overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl">
                            <a href="{{ route('courses.show', $course) }}" class="block p-3 pb-0">
                                <div class="aspect-video overflow-hidden rounded-[1.3rem] border border-slate-100 bg-slate-50">
                                    @if ($course->thumbnail)
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}"
                                             alt="{{ $course->title }}"
                                             class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.03]">
                                    @else
                                        <div class="flex h-full items-center justify-center text-blue-700">
                                            <i data-lucide="graduation-cap" class="h-14 w-14"></i>
                                        </div>
                                    @endif
                                </div>
                            </a>

                            <div class="flex flex-1 flex-col p-6">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="rounded-full border border-blue-200 bg-blue-50 px-3 py-1.5 text-[10px] font-black uppercase text-blue-700">
                                        {{ $course->level }}
                                    </span>

                                    @if ($course->is_featured)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-black text-amber-600">
                                            <i data-lucide="star" class="h-3.5 w-3.5 fill-current"></i>
                                            Pilihan
                                        </span>
                                    @endif
                                </div>

                                <a href="{{ route('courses.show', $course) }}">
                                    <h2 class="mt-4 text-xl font-black leading-7 text-slate-950 group-hover:text-blue-700">
                                        {{ $course->title }}
                                    </h2>
                                </a>

                                <p class="mt-3 line-clamp-3 text-sm font-medium leading-6 text-slate-500">
                                    {{ $course->subtitle ?: $course->description }}
                                </p>

                                <div class="mt-5 grid grid-cols-3 gap-2 border-t border-slate-100 pt-5 text-center">
                                    <div>
                                        <p class="text-base font-black text-slate-950">{{ $course->modules_count }}</p>
                                        <p class="text-[10px] font-bold text-slate-400">Modul</p>
                                    </div>
                                    <div>
                                        <p class="text-base font-black text-slate-950">{{ $course->lessons_count }}</p>
                                        <p class="text-[10px] font-bold text-slate-400">Video</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-950">{{ $course->formatted_duration }}</p>
                                        <p class="text-[10px] font-bold text-slate-400">Durasi</p>
                                    </div>
                                </div>

                                <a href="{{ route('courses.show', $course) }}"
                                   class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-3.5 text-xs font-black text-white transition hover:bg-blue-800">
                                    Lihat Materi
                                    <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-10 border-t border-slate-200 pt-6">
                    {{ $courses->links() }}
                </div>
            @else
                <div class="rounded-[2rem] border border-dashed border-slate-300 px-6 py-20 text-center">
                    <i data-lucide="search-x" class="mx-auto h-12 w-12 text-slate-400"></i>
                    <h2 class="mt-5 text-2xl font-black text-slate-950">Kelas tidak ditemukan</h2>
                </div>
            @endif
        </main>
    </div>
</x-app-layout>
