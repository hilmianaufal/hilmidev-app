<x-app-layout>
    <div class="min-h-screen bg-white">
        <section class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
                <nav class="flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-400">
                    <a href="{{ route('courses.index') }}" class="hover:text-blue-700">
                        Kelas Coding
                    </a>

                    <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>

                    <a href="{{ route('courses.show', $course) }}" class="hover:text-blue-700">
                        {{ $course->title }}
                    </a>

                    <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>

                    <span class="text-slate-700">
                        Preview
                    </span>
                </nav>

                <div class="mt-6">
                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-2 text-xs font-black text-emerald-700">
                        <i data-lucide="play-circle" class="h-4 w-4"></i>
                        Preview Gratis
                    </span>

                    <h1 class="mt-4 text-3xl font-black text-slate-950">
                        {{ $lesson->title }}
                    </h1>

                    <p class="mt-3 text-sm font-medium leading-7 text-slate-500">
                        {{ $lesson->description ?: $course->subtitle }}
                    </p>
                </div>
            </div>
        </section>

        <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-slate-950 shadow-2xl">
                @if ($lesson->video_type === 'upload' && $lesson->video_path)
                    <video controls controlsList="nodownload" class="aspect-video w-full bg-black">
                        <source src="{{ route('courses.lessons.preview-video', $lesson) }}">
                        Browser tidak mendukung pemutar video.
                    </video>
                @elseif ($lesson->embedUrl())
                    <iframe
                        src="{{ $lesson->embedUrl() }}"
                        class="aspect-video w-full"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                @else
                    <div class="flex aspect-video items-center justify-center text-center text-white">
                        <div>
                            <i data-lucide="video-off" class="mx-auto h-12 w-12 text-slate-400"></i>
                            <p class="mt-4 font-bold">Video preview belum tersedia.</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-8 rounded-[1.75rem] border border-blue-200 bg-blue-50 p-6 sm:flex sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-black text-slate-950">
                        Akses seluruh video pembelajaran
                    </h2>

                    <p class="mt-2 text-sm font-medium leading-7 text-slate-600">
                        Login menggunakan akun member aktif untuk membuka seluruh modul,
                        video, progress belajar, dan file materi.
                    </p>
                </div>

                @auth
                    <a href="{{ auth()->user()->hasActiveMembership() ? route('member.dashboard') : route('courses.index') }}"
                       class="mt-5 inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-700 px-6 py-4 text-sm font-black text-white sm:mt-0">
                        Buka Member Area
                        <i data-lucide="arrow-right" class="h-5 w-5"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="mt-5 inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-700 px-6 py-4 text-sm font-black text-white sm:mt-0">
                        Login Member
                        <i data-lucide="log-in" class="h-5 w-5"></i>
                    </a>
                @endauth
            </div>
        </main>
    </div>
</x-app-layout>
