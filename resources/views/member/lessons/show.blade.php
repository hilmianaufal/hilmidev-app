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


                    {{-- HILMIDEV NOTES DISCUSSIONS --}}
                    <section class="mt-6 grid items-start gap-6 lg:grid-cols-2">
                        {{-- CATATAN PRIBADI --}}
                        <article class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.16em] text-blue-700">
                                        Catatan Pribadi
                                    </p>

                                    <h2 class="mt-2 text-xl font-black text-slate-950">
                                        Tulis poin penting materi
                                    </h2>

                                    <p class="mt-2 text-xs font-medium leading-6 text-slate-500">
                                        Catatan ini bersifat pribadi dan hanya dapat dibaca melalui akun Anda.
                                    </p>
                                </div>

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-700">
                                    <i data-lucide="notebook-pen" class="h-5 w-5"></i>
                                </div>
                            </div>

                            <form method="POST"
                                  action="{{ route('member.lessons.notes.store', $lesson) }}"
                                  class="mt-6">
                                @csrf

                                <textarea
                                    name="content"
                                    rows="8"
                                    required
                                    maxlength="10000"
                                    placeholder="Contoh: Cara membuat migration, relasi model, fungsi controller..."
                                    class="w-full rounded-2xl border-slate-200 bg-slate-50 text-sm font-medium leading-7 focus:border-blue-500 focus:bg-white focus:ring-blue-100"
                                >{{ old('content', $note->content) }}</textarea>

                                @error('content')
                                    <p class="mt-2 text-xs font-semibold text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                                <div class="mt-4 flex flex-col gap-3 sm:flex-row">
                                    <button
                                        type="submit"
                                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-blue-700 px-5 py-3.5 text-xs font-black text-white transition hover:bg-blue-800"
                                    >
                                        <i data-lucide="save" class="h-4 w-4"></i>
                                        Simpan Catatan
                                    </button>
                                </div>
                            </form>

                            @if ($note->exists)
                                <form
                                    method="POST"
                                    action="{{ route('member.lessons.notes.destroy', $lesson) }}"
                                    class="mt-3"
                                    onsubmit="return confirm('Hapus catatan pribadi materi ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-red-100 bg-red-50 px-5 py-3 text-xs font-black text-red-600 transition hover:bg-red-100"
                                    >
                                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        Hapus Catatan
                                    </button>
                                </form>
                            @endif
                        </article>

                        {{-- FORUM DISKUSI --}}
                        <article class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.16em] text-blue-700">
                                        Forum Diskusi
                                    </p>

                                    <h2 class="mt-2 text-xl font-black text-slate-950">
                                        Tanya tentang materi
                                    </h2>

                                    <p class="mt-2 text-xs font-medium leading-6 text-slate-500">
                                        Tulis kendala atau pertanyaan agar dapat dijawab oleh admin.
                                    </p>
                                </div>

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-700">
                                    <i data-lucide="messages-square" class="h-5 w-5"></i>
                                </div>
                            </div>

                            <form
                                method="POST"
                                action="{{ route('member.lessons.discussions.store', $lesson) }}"
                                class="mt-6"
                            >
                                @csrf

                                <textarea
                                    name="message"
                                    rows="4"
                                    required
                                    maxlength="5000"
                                    placeholder="Tuliskan pertanyaan tentang materi ini..."
                                    class="w-full rounded-2xl border-slate-200 bg-slate-50 text-sm font-medium leading-7 focus:border-blue-500 focus:bg-white focus:ring-blue-100"
                                >{{ old('message') }}</textarea>

                                @error('message')
                                    <p class="mt-2 text-xs font-semibold text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                                <button
                                    type="submit"
                                    class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 py-3.5 text-xs font-black text-white transition hover:bg-blue-800"
                                >
                                    <i data-lucide="send" class="h-4 w-4"></i>
                                    Kirim Pertanyaan
                                </button>
                            </form>
                        </article>
                    </section>

                    {{-- DAFTAR DISKUSI --}}
                    <section class="mt-6 rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.16em] text-blue-700">
                                    Diskusi Materi
                                </p>

                                <h2 class="mt-2 text-2xl font-black text-slate-950">
                                    Pertanyaan dan jawaban
                                </h2>
                            </div>

                            <span class="inline-flex w-fit items-center gap-2 rounded-full bg-blue-50 px-4 py-2 text-xs font-black text-blue-700">
                                <i data-lucide="message-circle" class="h-4 w-4"></i>
                                {{ $discussions->count() }} diskusi
                            </span>
                        </div>

                        <div class="mt-7 space-y-5">
                            @forelse ($discussions as $discussion)
                                <article class="rounded-2xl border border-slate-200 bg-slate-50/60 p-5">
                                    <div class="flex items-start gap-4">
                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-700 text-sm font-black text-white">
                                            {{ strtoupper(substr($discussion->user?->name ?? 'M', 0, 1)) }}
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <p class="text-sm font-black text-slate-900">
                                                    {{ $discussion->user?->name ?? 'Member' }}
                                                </p>

                                                @if ($discussion->is_answered)
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-[9px] font-black text-emerald-700">
                                                        <i data-lucide="circle-check" class="h-3 w-3"></i>
                                                        Terjawab
                                                    </span>
                                                @else
                                                    <span class="rounded-full bg-amber-50 px-2.5 py-1 text-[9px] font-black text-amber-700">
                                                        Menunggu jawaban
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="mt-1 text-[10px] font-semibold text-slate-400">
                                                {{ $discussion->created_at->translatedFormat('d M Y H:i') }}
                                            </p>

                                            <p class="mt-4 whitespace-pre-line text-sm font-medium leading-7 text-slate-600">{{ $discussion->message }}</p>

                                            @if ($discussion->user_id === auth()->id())
                                                <form
                                                    method="POST"
                                                    action="{{ route('member.discussions.destroy', $discussion) }}"
                                                    class="mt-4"
                                                    onsubmit="return confirm('Hapus pertanyaan ini?')"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center gap-1.5 text-[10px] font-black text-red-600"
                                                    >
                                                        <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                                        Hapus pertanyaan
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($discussion->replies->count())
                                        <div class="ml-0 mt-5 space-y-3 border-l-2 border-blue-200 pl-4 sm:ml-14">
                                            @foreach ($discussion->replies as $reply)
                                                <div class="rounded-2xl border border-blue-100 bg-white p-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-cyan-100 text-xs font-black text-cyan-800">
                                                            {{ strtoupper(substr($reply->user?->name ?? 'A', 0, 1)) }}
                                                        </div>

                                                        <div>
                                                            <div class="flex flex-wrap items-center gap-2">
                                                                <p class="text-xs font-black text-slate-900">
                                                                    {{ $reply->user?->name ?? 'Admin' }}
                                                                </p>

                                                                @if ($reply->user?->isAdmin())
                                                                    <span class="rounded-full bg-blue-700 px-2 py-1 text-[8px] font-black uppercase text-white">
                                                                        Admin
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            <p class="mt-1 text-[9px] font-semibold text-slate-400">
                                                                {{ $reply->created_at->translatedFormat('d M Y H:i') }}
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <p class="mt-3 whitespace-pre-line text-sm font-medium leading-7 text-slate-600">{{ $reply->message }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </article>
                            @empty
                                <div class="rounded-2xl border border-dashed border-slate-300 px-6 py-12 text-center">
                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-700">
                                        <i data-lucide="message-circle-question" class="h-7 w-7"></i>
                                    </div>

                                    <p class="mt-5 font-black text-slate-900">
                                        Belum ada diskusi
                                    </p>

                                    <p class="mt-2 text-xs font-medium text-slate-500">
                                        Jadilah member pertama yang bertanya tentang materi ini.
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </section>

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
