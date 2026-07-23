#!/usr/bin/env bash

set -euo pipefail

if [ ! -f artisan ]; then
    echo "ERROR: Jalankan dari folder utama project Laravel."
    exit 1
fi

STAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP_DIR="storage/app/member-learning-backup-$STAMP"

mkdir -p "$BACKUP_DIR/resources/views/member/courses"
mkdir -p "$BACKUP_DIR/resources/views/member/lessons"

for file in \
    resources/views/member/courses/show.blade.php \
    resources/views/member/lessons/show.blade.php
do
    if [ -f "$file" ]; then
        cp "$file" "$BACKUP_DIR/$file"
    fi
done

echo "Backup dibuat di: $BACKUP_DIR"

# ============================================================
# DETAIL KELAS MEMBER
# ============================================================

cat > resources/views/member/courses/show.blade.php <<'BLADE'
<x-layouts.member>
    @php
        $allLessons = $course->modules
            ->flatMap(fn ($module) => $module->lessons)
            ->values();

        $nextLessonToLearn = $allLessons->first(
            fn ($item) => ! $completedLessonIds->contains($item->id)
        ) ?? $allLessons->first();

        $totalModules = $course->modules->count();

        $totalMinutes = $allLessons->sum('duration_minutes');

        $formattedDuration = $totalMinutes >= 60
            ? floor($totalMinutes / 60) . ' jam ' . ($totalMinutes % 60) . ' menit'
            : $totalMinutes . ' menit';
    @endphp

    <div class="min-h-screen bg-slate-50">

        {{-- HERO KELAS --}}
        <section class="px-4 pt-6 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-blue-950 via-blue-800 to-blue-600 text-white shadow-2xl shadow-blue-900/20">
                <div class="pointer-events-none absolute inset-0">
                    <div class="absolute -right-24 -top-24 h-80 w-80 rounded-full bg-cyan-300/15 blur-3xl"></div>
                    <div class="absolute -bottom-32 left-1/3 h-80 w-80 rounded-full bg-white/10 blur-3xl"></div>
                </div>

                <div class="relative grid items-center gap-8 px-6 py-8 sm:px-9 lg:grid-cols-12 lg:px-10 lg:py-10">
                    <div class="lg:col-span-7">
                        <a href="{{ route('member.dashboard') }}"
                           class="inline-flex items-center gap-2 text-xs font-black text-blue-200 transition hover:text-white">
                            <i data-lucide="arrow-left" class="h-4 w-4"></i>
                            Dashboard Member
                        </a>

                        <div class="mt-6 flex flex-wrap gap-2">
                            <span class="rounded-full border border-white/15 bg-white/10 px-3 py-2 text-[10px] font-black uppercase tracking-wider">
                                {{ $course->level }}
                            </span>

                            @if ($course->technology)
                                <span class="rounded-full border border-white/15 bg-white/10 px-3 py-2 text-[10px] font-black uppercase tracking-wider text-cyan-200">
                                    {{ $course->technology }}
                                </span>
                            @endif

                            @if ($course->is_featured)
                                <span class="inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-white/10 px-3 py-2 text-[10px] font-black text-amber-200">
                                    <i data-lucide="star" class="h-3.5 w-3.5 fill-current"></i>
                                    Kelas Pilihan
                                </span>
                            @endif
                        </div>

                        <h1 class="mt-6 text-3xl font-black leading-tight tracking-tight sm:text-4xl lg:text-5xl">
                            {{ $course->title }}
                        </h1>

                        <p class="mt-4 max-w-3xl text-sm font-medium leading-7 text-blue-100/80 sm:text-base">
                            {{ $course->subtitle ?: $course->description }}
                        </p>

                        <div class="mt-7 flex flex-wrap items-center gap-5 text-xs font-bold text-blue-100/75">
                            <span class="inline-flex items-center gap-2">
                                <i data-lucide="layers-3" class="h-4 w-4 text-cyan-300"></i>
                                {{ $totalModules }} modul
                            </span>

                            <span class="inline-flex items-center gap-2">
                                <i data-lucide="circle-play" class="h-4 w-4 text-cyan-300"></i>
                                {{ $totalLessons }} video
                            </span>

                            <span class="inline-flex items-center gap-2">
                                <i data-lucide="clock-3" class="h-4 w-4 text-cyan-300"></i>
                                {{ $formattedDuration }}
                            </span>
                        </div>

                        @if ($nextLessonToLearn)
                            <a href="{{ route('member.lessons.show', $nextLessonToLearn) }}"
                               class="mt-8 inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-6 py-4 text-sm font-black text-blue-900 shadow-xl transition duration-300 hover:-translate-y-0.5 hover:bg-blue-50">
                                <i data-lucide="{{ $percentage > 0 ? 'circle-play' : 'rocket' }}"
                                   class="h-5 w-5"></i>

                                {{ $percentage > 0 ? 'Lanjutkan Belajar' : 'Mulai Kelas' }}
                            </a>
                        @endif
                    </div>

                    <div class="lg:col-span-5">
                        <div class="overflow-hidden rounded-[1.75rem] border border-white/15 bg-white/10 p-3 backdrop-blur-xl">
                            <div class="relative aspect-video overflow-hidden rounded-[1.3rem] bg-blue-950">
                                @if ($course->thumbnail)
                                    <img src="{{ asset('storage/' . $course->thumbnail) }}"
                                         alt="{{ $course->title }}"
                                         class="h-full w-full object-cover opacity-80">
                                @else
                                    <div class="flex h-full items-center justify-center">
                                        <i data-lucide="graduation-cap" class="h-16 w-16 text-blue-200"></i>
                                    </div>
                                @endif

                                <div class="absolute inset-0 bg-gradient-to-t from-blue-950/80 via-transparent to-transparent"></div>

                                @if ($nextLessonToLearn)
                                    <a href="{{ route('member.lessons.show', $nextLessonToLearn) }}"
                                       class="absolute inset-0 flex items-center justify-center">
                                        <span class="flex h-20 w-20 items-center justify-center rounded-full border border-white/25 bg-white/20 text-white shadow-2xl backdrop-blur transition hover:scale-105 hover:bg-white/30">
                                            <i data-lucide="play" class="ml-1 h-9 w-9"></i>
                                        </span>
                                    </a>
                                @endif
                            </div>

                            <div class="p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-xs font-bold text-blue-100">
                                        Progress kelas
                                    </span>

                                    <span class="text-sm font-black text-cyan-300">
                                        {{ $percentage }}%
                                    </span>
                                </div>

                                <div class="mt-3 h-2.5 overflow-hidden rounded-full bg-white/10">
                                    <div class="h-full rounded-full bg-cyan-300 transition-all duration-700"
                                         style="width: {{ $percentage }}%">
                                    </div>
                                </div>

                                <p class="mt-3 text-xs font-medium text-blue-100/70">
                                    {{ $completedLessons }} dari {{ $totalLessons }} video telah diselesaikan
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <main class="px-4 py-8 sm:px-6 lg:px-8">
            <div class="grid items-start gap-8 xl:grid-cols-12">

                {{-- KURIKULUM --}}
                <section class="xl:col-span-8">
                    <div class="mb-6">
                        <p class="text-xs font-black uppercase tracking-[0.16em] text-blue-700">
                            Kurikulum Pembelajaran
                        </p>

                        <h2 class="mt-2 text-2xl font-black text-slate-950 sm:text-3xl">
                            Modul dan materi kelas
                        </h2>

                        <p class="mt-2 text-sm font-medium leading-7 text-slate-500">
                            Pelajari materi secara berurutan agar proses pembuatan aplikasi
                            lebih mudah dipahami.
                        </p>
                    </div>

                    <div class="space-y-5">
                        @forelse ($course->modules as $module)
                            @php
                                $moduleLessonIds = $module->lessons->pluck('id');

                                $moduleCompleted = $moduleLessonIds
                                    ->intersect($completedLessonIds)
                                    ->count();

                                $moduleTotal = $module->lessons->count();

                                $modulePercentage = $moduleTotal > 0
                                    ? (int) round(($moduleCompleted / $moduleTotal) * 100)
                                    : 0;
                            @endphp

                            <article x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }"
                                     class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">

                                <button type="button"
                                        @click="open = !open"
                                        class="flex w-full items-center justify-between gap-5 p-5 text-left sm:p-6">
                                    <div class="flex min-w-0 items-center gap-4">
                                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-700 text-sm font-black text-white shadow-lg shadow-blue-700/20">
                                            {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="text-[10px] font-black uppercase tracking-[0.15em] text-blue-700">
                                                Modul {{ $loop->iteration }}
                                            </p>

                                            <h3 class="mt-1 truncate text-base font-black text-slate-950 sm:text-lg">
                                                {{ $module->title }}
                                            </h3>

                                            <p class="mt-1 text-xs font-medium text-slate-400">
                                                {{ $moduleCompleted }} dari {{ $moduleTotal }} materi selesai
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex shrink-0 items-center gap-3">
                                        <span class="hidden rounded-full bg-blue-50 px-3 py-2 text-[10px] font-black text-blue-700 sm:inline-flex">
                                            {{ $modulePercentage }}%
                                        </span>

                                        <i data-lucide="chevron-down"
                                           class="h-5 w-5 text-slate-400 transition"
                                           :class="{ 'rotate-180': open }"></i>
                                    </div>
                                </button>

                                <div x-show="open"
                                     x-collapse
                                     class="border-t border-slate-100">

                                    @if ($module->description)
                                        <div class="border-b border-slate-100 bg-slate-50/70 px-5 py-4 text-xs font-medium leading-6 text-slate-500 sm:px-6">
                                            {{ $module->description }}
                                        </div>
                                    @endif

                                    <div class="divide-y divide-slate-100">
                                        @forelse ($module->lessons as $lesson)
                                            @php
                                                $completed = $completedLessonIds->contains($lesson->id);
                                                $isNextLesson = $nextLessonToLearn?->id === $lesson->id;
                                            @endphp

                                            <a href="{{ route('member.lessons.show', $lesson) }}"
                                               class="group flex items-center justify-between gap-4 px-5 py-4 transition hover:bg-blue-50/60 sm:px-6">

                                                <div class="flex min-w-0 items-center gap-4">
                                                    <div @class([
                                                        'flex h-11 w-11 shrink-0 items-center justify-center rounded-xl transition',
                                                        'bg-emerald-50 text-emerald-600' => $completed,
                                                        'bg-blue-700 text-white shadow-lg shadow-blue-700/15' => $isNextLesson && ! $completed,
                                                        'bg-slate-100 text-slate-500 group-hover:bg-blue-100 group-hover:text-blue-700' => ! $completed && ! $isNextLesson,
                                                    ])>
                                                        <i data-lucide="{{ $completed ? 'circle-check' : 'play' }}"
                                                           class="h-5 w-5"></i>
                                                    </div>

                                                    <div class="min-w-0">
                                                        <div class="flex flex-wrap items-center gap-2">
                                                            <p class="truncate text-sm font-black text-slate-800 group-hover:text-blue-700">
                                                                {{ $lesson->title }}
                                                            </p>

                                                            @if ($isNextLesson && ! $completed)
                                                                <span class="rounded-full bg-blue-50 px-2 py-1 text-[8px] font-black uppercase tracking-wide text-blue-700">
                                                                    Lanjutkan
                                                                </span>
                                                            @endif
                                                        </div>

                                                        <div class="mt-1.5 flex flex-wrap gap-3 text-[10px] font-bold text-slate-400">
                                                            <span class="inline-flex items-center gap-1">
                                                                <i data-lucide="clock-3" class="h-3.5 w-3.5"></i>
                                                                {{ $lesson->duration_minutes }} menit
                                                            </span>

                                                            @if ($lesson->attachment_path)
                                                                <span class="inline-flex items-center gap-1">
                                                                    <i data-lucide="paperclip" class="h-3.5 w-3.5"></i>
                                                                    File materi
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <i data-lucide="chevron-right"
                                                   class="h-5 w-5 shrink-0 text-slate-300 transition group-hover:translate-x-1 group-hover:text-blue-700"></i>
                                            </a>
                                        @empty
                                            <div class="px-6 py-8 text-center text-sm font-medium text-slate-400">
                                                Belum ada video pada modul ini.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[1.75rem] bg-blue-50 text-blue-700">
                                    <i data-lucide="book-open-text" class="h-9 w-9"></i>
                                </div>

                                <h3 class="mt-6 text-xl font-black text-slate-950">
                                    Materi belum tersedia
                                </h3>

                                <p class="mt-2 text-sm font-medium text-slate-500">
                                    Admin belum menambahkan modul pembelajaran.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </section>

                {{-- SIDEBAR INFORMASI --}}
                <aside class="space-y-5 xl:col-span-4">
                    <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm xl:sticky xl:top-28">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-700 text-white">
                                <i data-lucide="chart-no-axes-column-increasing" class="h-6 w-6"></i>
                            </div>

                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.14em] text-slate-400">
                                    Progress Belajar
                                </p>

                                <p class="mt-1 text-2xl font-black text-slate-950">
                                    {{ $percentage }}%
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 h-3 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-blue-700"
                                 style="width: {{ $percentage }}%">
                            </div>
                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-3">
                            <div class="rounded-2xl bg-blue-50 p-4 text-center">
                                <p class="text-2xl font-black text-blue-700">
                                    {{ $completedLessons }}
                                </p>

                                <p class="mt-1 text-[9px] font-black uppercase tracking-wider text-slate-500">
                                    Selesai
                                </p>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-4 text-center">
                                <p class="text-2xl font-black text-slate-950">
                                    {{ max(0, $totalLessons - $completedLessons) }}
                                </p>

                                <p class="mt-1 text-[9px] font-black uppercase tracking-wider text-slate-500">
                                    Tersisa
                                </p>
                            </div>
                        </div>

                        @if ($nextLessonToLearn)
                            <a href="{{ route('member.lessons.show', $nextLessonToLearn) }}"
                               class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-700 px-5 py-4 text-sm font-black text-white shadow-lg shadow-blue-700/20 transition hover:bg-blue-800">
                                <i data-lucide="circle-play" class="h-5 w-5"></i>

                                {{ $percentage > 0 ? 'Lanjutkan Materi' : 'Mulai Kelas' }}
                            </a>
                        @endif

                        <div class="mt-6 space-y-4 border-t border-slate-100 pt-6">
                            <div class="flex items-center justify-between gap-3 text-xs">
                                <span class="font-semibold text-slate-500">
                                    Level
                                </span>

                                <span class="font-black capitalize text-slate-900">
                                    {{ $course->level }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between gap-3 text-xs">
                                <span class="font-semibold text-slate-500">
                                    Total modul
                                </span>

                                <span class="font-black text-slate-900">
                                    {{ $totalModules }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between gap-3 text-xs">
                                <span class="font-semibold text-slate-500">
                                    Total video
                                </span>

                                <span class="font-black text-slate-900">
                                    {{ $totalLessons }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between gap-3 text-xs">
                                <span class="font-semibold text-slate-500">
                                    Durasi
                                </span>

                                <span class="font-black text-slate-900">
                                    {{ $formattedDuration }}
                                </span>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </main>
    </div>
</x-layouts.member>
BLADE

# ============================================================
# PEMUTAR VIDEO MEMBER
# ============================================================

cat > resources/views/member/lessons/show.blade.php <<'BLADE'
<x-layouts.member>
    @php
        $allLessons = $course->modules
            ->flatMap(fn ($module) => $module->lessons)
            ->values();

        $totalLessons = $allLessons->count();

        $completedLessons = $allLessons
            ->whereIn('id', $completedLessonIds)
            ->count();

        $coursePercentage = $totalLessons > 0
            ? (int) round(($completedLessons / $totalLessons) * 100)
            : 0;

        $currentIndex = $allLessons->search(
            fn ($item) => $item->id === $lesson->id
        );

        $currentNumber = $currentIndex !== false
            ? $currentIndex + 1
            : 1;
    @endphp

    <div class="min-h-screen bg-slate-50">
        <div class="px-4 py-6 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-5 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-bold text-emerald-700">
                    <i data-lucide="circle-check" class="mt-0.5 h-5 w-5 shrink-0"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <nav class="flex min-w-0 items-center gap-2 text-xs font-semibold text-slate-400">
                    <a href="{{ route('member.dashboard') }}"
                       class="transition hover:text-blue-700">
                        Dashboard
                    </a>

                    <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>

                    <a href="{{ route('member.courses.show', $course) }}"
                       class="max-w-48 truncate transition hover:text-blue-700 sm:max-w-xs">
                        {{ $course->title }}
                    </a>

                    <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>

                    <span class="max-w-44 truncate font-bold text-slate-700">
                        {{ $lesson->title }}
                    </span>
                </nav>

                <a href="{{ route('member.courses.show', $course) }}"
                   class="inline-flex items-center gap-2 text-xs font-black text-blue-700">
                    <i data-lucide="list-video" class="h-4 w-4"></i>
                    Kembali ke kurikulum
                </a>
            </div>

            <div class="grid items-start gap-6 xl:grid-cols-12">

                {{-- VIDEO DAN MATERI --}}
                <main class="min-w-0 xl:col-span-8">

                    {{-- VIDEO PLAYER --}}
                    <div class="overflow-hidden rounded-[1.75rem] bg-slate-950 shadow-2xl shadow-slate-950/20">
                        <div class="relative aspect-video">
                            @if ($lesson->video_type === 'upload' && $lesson->video_path)
                                <video id="lesson-video"
                                       class="h-full w-full bg-black"
                                       controls
                                       controlsList="nodownload"
                                       preload="metadata">
                                    <source src="{{ route('member.lessons.video', $lesson) }}">
                                </video>
                            @elseif ($lesson->embedUrl())
                                <iframe src="{{ $lesson->embedUrl() }}"
                                        class="h-full w-full"
                                        title="{{ $lesson->title }}"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                </iframe>
                            @else
                                <div class="flex h-full items-center justify-center px-6 text-center text-white">
                                    <div>
                                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[1.75rem] bg-white/10">
                                            <i data-lucide="video-off" class="h-9 w-9 text-slate-300"></i>
                                        </div>

                                        <p class="mt-5 text-lg font-black">
                                            Video belum tersedia
                                        </p>

                                        <p class="mt-2 text-sm font-medium text-slate-400">
                                            Admin belum menambahkan video pada materi ini.
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <div class="pointer-events-none absolute left-4 top-4">
                                <span class="inline-flex items-center gap-2 rounded-full bg-black/60 px-3 py-2 text-[10px] font-black text-white backdrop-blur">
                                    <i data-lucide="play-circle" class="h-3.5 w-3.5"></i>
                                    Materi {{ $currentNumber }} dari {{ $totalLessons }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- DETAIL MATERI --}}
                    <article class="mt-6 rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <div class="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap gap-2">
                                    <span class="rounded-full bg-blue-50 px-3 py-2 text-[10px] font-black uppercase tracking-wider text-blue-700">
                                        {{ $lesson->module->title }}
                                    </span>

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-2 text-[10px] font-black text-slate-500">
                                        <i data-lucide="clock-3" class="h-3.5 w-3.5"></i>
                                        {{ $lesson->duration_minutes }} menit
                                    </span>

                                    @if ($progress->is_completed)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-2 text-[10px] font-black text-emerald-700">
                                            <i data-lucide="circle-check" class="h-3.5 w-3.5"></i>
                                            Selesai
                                        </span>
                                    @endif
                                </div>

                                <h1 class="mt-5 text-2xl font-black leading-tight text-slate-950 sm:text-3xl">
                                    {{ $lesson->title }}
                                </h1>

                                @if ($lesson->description)
                                    <p class="mt-4 text-sm font-medium leading-7 text-slate-500">
                                        {{ $lesson->description }}
                                    </p>
                                @endif
                            </div>

                            @if ($lesson->attachment_path)
                                <a href="{{ route('member.lessons.attachment', $lesson) }}"
                                   class="inline-flex shrink-0 items-center justify-center gap-2 rounded-2xl border border-blue-200 bg-blue-50 px-5 py-3.5 text-xs font-black text-blue-700 transition hover:bg-blue-100">
                                    <i data-lucide="download" class="h-4 w-4"></i>
                                    Download Materi
                                </a>
                            @endif
                        </div>

                        {{-- COMPLETE FORM --}}
                        <div class="mt-7 border-t border-slate-100 pt-6">
                            @if (! $progress->is_completed)
                                <form id="complete-form"
                                      method="POST"
                                      action="{{ route('member.lessons.progress', $lesson) }}">
                                    @csrf

                                    <input id="watched-seconds"
                                           type="hidden"
                                           name="watched_seconds"
                                           value="{{ $progress->watched_seconds ?? 0 }}">

                                    <input type="hidden"
                                           name="is_completed"
                                           value="1">

                                    <button type="submit"
                                            class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-700 px-6 py-4 text-sm font-black text-white shadow-lg shadow-blue-700/20 transition hover:bg-blue-800 sm:w-auto">
                                        <i data-lucide="circle-check-big" class="h-5 w-5"></i>
                                        Tandai Materi Selesai
                                    </button>
                                </form>
                            @else
                                <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-bold text-emerald-700">
                                    <i data-lucide="badge-check" class="h-5 w-5"></i>
                                    Materi ini sudah diselesaikan.
                                </div>
                            @endif
                        </div>
                    </article>

                    {{-- PREVIOUS NEXT --}}
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        @if ($previousLesson)
                            <a href="{{ route('member.lessons.show', $previousLesson) }}"
                               class="group rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-lg">
                                <div class="flex items-center gap-2 text-[10px] font-black uppercase tracking-wider text-slate-400">
                                    <i data-lucide="arrow-left" class="h-4 w-4"></i>
                                    Materi sebelumnya
                                </div>

                                <p class="mt-3 line-clamp-2 text-sm font-black leading-6 text-slate-800 group-hover:text-blue-700">
                                    {{ $previousLesson->title }}
                                </p>
                            </a>
                        @else
                            <div class="hidden sm:block"></div>
                        @endif

                        @if ($nextLesson)
                            <a href="{{ route('member.lessons.show', $nextLesson) }}"
                               class="group rounded-[1.5rem] border border-blue-200 bg-blue-50 p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                                <div class="flex items-center justify-end gap-2 text-[10px] font-black uppercase tracking-wider text-blue-700">
                                    Materi berikutnya
                                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                                </div>

                                <p class="mt-3 line-clamp-2 text-right text-sm font-black leading-6 text-slate-800 group-hover:text-blue-700">
                                    {{ $nextLesson->title }}
                                </p>
                            </a>
                        @else
                            <a href="{{ route('member.courses.show', $course) }}"
                               class="rounded-[1.5rem] border border-emerald-200 bg-emerald-50 p-5 text-right shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                                <div class="flex items-center justify-end gap-2 text-[10px] font-black uppercase tracking-wider text-emerald-700">
                                    Kelas selesai
                                    <i data-lucide="trophy" class="h-4 w-4"></i>
                                </div>

                                <p class="mt-3 text-sm font-black text-slate-800">
                                    Kembali ke halaman kelas
                                </p>
                            </a>
                        @endif
                    </div>
                </main>

                {{-- SIDEBAR KURIKULUM --}}
                <aside class="min-w-0 xl:col-span-4">
                    <div class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm xl:sticky xl:top-28">
                        <div class="border-b border-slate-100 p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="text-[10px] font-black uppercase tracking-[0.14em] text-blue-700">
                                        Kurikulum Kelas
                                    </p>

                                    <h2 class="mt-2 line-clamp-2 text-lg font-black leading-6 text-slate-950">
                                        {{ $course->title }}
                                    </h2>
                                </div>

                                <span class="shrink-0 rounded-full bg-blue-50 px-3 py-2 text-xs font-black text-blue-700">
                                    {{ $coursePercentage }}%
                                </span>
                            </div>

                            <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-blue-700"
                                     style="width: {{ $coursePercentage }}%">
                                </div>
                            </div>

                            <p class="mt-3 text-xs font-medium text-slate-400">
                                {{ $completedLessons }} dari {{ $totalLessons }} materi selesai
                            </p>
                        </div>

                        <div class="max-h-[65vh] overflow-y-auto">
                            @foreach ($course->modules as $module)
                                <section x-data="{ open: {{ $module->id === $lesson->module->id ? 'true' : 'false' }} }"
                                         class="border-b border-slate-100 last:border-0">

                                    <button type="button"
                                            @click="open = !open"
                                            class="flex w-full items-center justify-between gap-3 bg-slate-50/70 px-5 py-4 text-left">
                                        <div class="min-w-0">
                                            <p class="text-[9px] font-black uppercase tracking-wider text-blue-700">
                                                Modul {{ $loop->iteration }}
                                            </p>

                                            <p class="mt-1 truncate text-xs font-black text-slate-800">
                                                {{ $module->title }}
                                            </p>
                                        </div>

                                        <i data-lucide="chevron-down"
                                           class="h-4 w-4 shrink-0 text-slate-400 transition"
                                           :class="{ 'rotate-180': open }"></i>
                                    </button>

                                    <div x-show="open" x-collapse>
                                        @foreach ($module->lessons as $lessonItem)
                                            @php
                                                $isCurrent = $lessonItem->id === $lesson->id;
                                                $isCompleted = $completedLessonIds->contains($lessonItem->id);
                                            @endphp

                                            <a href="{{ route('member.lessons.show', $lessonItem) }}"
                                               @class([
                                                   'flex items-center gap-3 border-t border-slate-100 px-5 py-4 transition',
                                                   'bg-blue-700 text-white' => $isCurrent,
                                                   'hover:bg-blue-50' => ! $isCurrent,
                                               ])>
                                                <span @class([
                                                    'flex h-9 w-9 shrink-0 items-center justify-center rounded-xl',
                                                    'bg-white/15 text-white' => $isCurrent,
                                                    'bg-emerald-50 text-emerald-600' => $isCompleted && ! $isCurrent,
                                                    'bg-slate-100 text-slate-500' => ! $isCompleted && ! $isCurrent,
                                                ])>
                                                    <i data-lucide="{{ $isCompleted ? 'circle-check' : 'play' }}"
                                                       class="h-4 w-4"></i>
                                                </span>

                                                <div class="min-w-0 flex-1">
                                                    <p @class([
                                                        'line-clamp-2 text-xs font-black leading-5',
                                                        'text-white' => $isCurrent,
                                                        'text-slate-700' => ! $isCurrent,
                                                    ])>
                                                        {{ $lessonItem->title }}
                                                    </p>

                                                    <p @class([
                                                        'mt-1 text-[9px] font-semibold',
                                                        'text-blue-100' => $isCurrent,
                                                        'text-slate-400' => ! $isCurrent,
                                                    ])>
                                                        {{ $lessonItem->duration_minutes }} menit
                                                    </p>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </section>
                            @endforeach
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const video = document.getElementById('lesson-video');
            const watchedInput = document.getElementById('watched-seconds');

            if (!video || !watchedInput) {
                return;
            }

            const progressUrl = @json(
                route('member.lessons.progress', $lesson)
            );

            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute('content');

            let lastSavedSecond = Number(watchedInput.value || 0);
            let saving = false;

            function saveProgress(completed = false, useBeacon = false) {
                const watchedSeconds = Math.floor(video.currentTime || 0);

                watchedInput.value = watchedSeconds;

                const formData = new FormData();
                formData.append('_token', csrfToken);
                formData.append('watched_seconds', watchedSeconds);

                if (completed) {
                    formData.append('is_completed', '1');
                }

                if (useBeacon && navigator.sendBeacon) {
                    navigator.sendBeacon(progressUrl, formData);
                    return;
                }

                if (saving) {
                    return;
                }

                saving = true;

                fetch(progressUrl, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .catch(() => {})
                    .finally(() => {
                        saving = false;
                        lastSavedSecond = watchedSeconds;
                    });
            }

            video.addEventListener('timeupdate', function () {
                const currentSecond = Math.floor(video.currentTime || 0);

                watchedInput.value = currentSecond;

                if (currentSecond - lastSavedSecond >= 30) {
                    saveProgress(false);
                }
            });

            video.addEventListener('pause', function () {
                if (!video.ended) {
                    saveProgress(false);
                }
            });

            video.addEventListener('ended', function () {
                saveProgress(true);

                setTimeout(function () {
                    window.location.reload();
                }, 700);
            });

            window.addEventListener('beforeunload', function () {
                saveProgress(false, true);
            });
        });
    </script>
</x-layouts.member>
BLADE

# ============================================================
# VALIDASI
# ============================================================

php artisan optimize:clear
php artisan view:cache
php artisan view:clear

echo ""
echo "============================================================"
echo "HALAMAN BELAJAR MEMBER BERHASIL DIPERBARUI"
echo "============================================================"
echo "Detail kelas : /member/kelas/{course}"
echo "Video materi : /member/materi/{lesson}"
echo "Backup       : $BACKUP_DIR"
echo "============================================================"
