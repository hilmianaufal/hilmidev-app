<x-app-layout>
    <div class="min-h-screen bg-white">
        <section class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
                <nav class="mb-7 flex items-center gap-2 text-xs font-semibold text-slate-400">
                    <a href="{{ route('courses.index') }}" class="hover:text-blue-700">Kelas Coding</a>
                    <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>
                    <span class="truncate font-bold text-slate-700">{{ $course->title }}</span>
                </nav>

                <div class="grid gap-10 lg:grid-cols-12">
                    <div class="lg:col-span-7">
                        <div class="overflow-hidden rounded-[2rem] border border-slate-200 p-3 shadow-xl shadow-slate-900/[0.05]">
                            <div class="aspect-video overflow-hidden rounded-[1.5rem] bg-slate-50">
                                @if ($course->thumbnail)
                                    <img src="{{ asset('storage/' . $course->thumbnail) }}"
                                         alt="{{ $course->title }}"
                                         class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full items-center justify-center text-blue-700">
                                        <i data-lucide="graduation-cap" class="h-20 w-20"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-5">
                        <div class="flex flex-wrap gap-2">
                            <span class="rounded-full bg-blue-50 px-3 py-2 text-xs font-black text-blue-700">
                                {{ strtoupper($course->level) }}
                            </span>

                            @if ($course->technology)
                                <span class="rounded-full border border-slate-200 px-3 py-2 text-xs font-black text-slate-600">
                                    {{ $course->technology }}
                                </span>
                            @endif
                        </div>

                        <h1 class="mt-5 text-3xl font-black leading-tight text-slate-950 sm:text-4xl">
                            {{ $course->title }}
                        </h1>

                        <p class="mt-4 text-sm font-medium leading-7 text-slate-500">
                            {{ $course->subtitle ?: $course->description }}
                        </p>

                        <div class="mt-6 grid grid-cols-3 divide-x divide-slate-200 rounded-2xl border border-slate-200 py-4 text-center">
                            <div>
                                <p class="text-xl font-black">{{ $course->modules->count() }}</p>
                                <p class="text-[10px] font-bold text-slate-400">MODUL</p>
                            </div>
                            <div>
                                <p class="text-xl font-black">{{ $course->modules->flatMap->lessons->count() }}</p>
                                <p class="text-[10px] font-bold text-slate-400">VIDEO</p>
                            </div>
                            <div>
                                <p class="text-sm font-black">{{ $course->formatted_duration }}</p>
                                <p class="text-[10px] font-bold text-slate-400">DURASI</p>
                            </div>
                        </div>

                        <div class="mt-7">
                            @auth
                                @if ($hasActiveMembership)
                                    <a href="{{ route('member.courses.show', $course) }}"
                                       class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-700 px-6 py-4 text-sm font-black text-white shadow-lg shadow-blue-700/20 transition hover:bg-blue-800">
                                        <i data-lucide="play-circle" class="h-5 w-5"></i>
                                        Mulai Belajar
                                    </a>
                                @else
                                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
                                        <p class="font-black text-amber-900">Membership belum aktif</p>
                                        <p class="mt-2 text-sm font-medium text-amber-700">
                                            Hubungi admin untuk mengaktifkan paket bimbingan.
                                        </p>
                                    </div>
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                   class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-700 px-6 py-4 text-sm font-black text-white">
                                    Login untuk Belajar
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <main class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
            <p class="text-xs font-black uppercase tracking-[0.16em] text-blue-700">Kurikulum</p>
            <h2 class="mt-2 text-3xl font-black text-slate-950">Materi yang akan dipelajari</h2>

            <div class="mt-8 space-y-4">
                @foreach ($course->modules as $module)
                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                        <div class="border-b border-slate-100 px-6 py-5">
                            <p class="text-xs font-black text-blue-700">MODUL {{ $loop->iteration }}</p>
                            <h3 class="mt-1 font-black text-slate-950">{{ $module->title }}</h3>
                        </div>

                        @forelse ($module->lessons as $lesson)
                            <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-6 py-4 last:border-0">
                                <div class="flex min-w-0 items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-slate-200 text-slate-500">
                                        <i data-lucide="{{ $lesson->is_preview ? 'play' : 'lock' }}" class="h-4 w-4"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-bold text-slate-700">{{ $lesson->title }}</p>
                                        <p class="mt-1 text-xs text-slate-400">{{ $lesson->duration_minutes }} menit</p>
                                    </div>
                                </div>

                                @if ($lesson->is_preview)
                                    <a href="{{ route('courses.lessons.preview', $lesson) }}" class="rounded-full bg-emerald-50 px-3 py-1.5 text-[10px] font-black text-emerald-700 transition hover:bg-emerald-100">PREVIEW</a>
                                @endif
                            </div>
                        @empty
                            <p class="px-6 py-5 text-sm text-slate-500">Materi belum ditambahkan.</p>
                        @endforelse
                    </section>
                @endforeach
            </div>
        </main>
    </div>
</x-app-layout>
