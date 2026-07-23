<x-layouts.admin>
    <div class="mx-auto max-w-7xl px-4 py-10">
        <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.16em] text-blue-700">Learning Management</p>
                <h1 class="mt-2 text-3xl font-black text-slate-950">Kelas Coding</h1>
                <p class="mt-2 text-sm text-slate-500">Kelola kelas, modul, video, dan file materi.</p>
            </div>

            <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-blue-700 px-5 py-4 text-sm font-black text-white">
                <i data-lucide="plus" class="h-5 w-5"></i>
                Tambah Kelas
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 font-bold text-emerald-700">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-6 flex gap-3">
            <input name="q" value="{{ $search }}" placeholder="Cari kelas..." class="h-14 flex-1 rounded-2xl border-slate-200">
            <button class="rounded-2xl bg-slate-950 px-6 text-white">
                <i data-lucide="search" class="h-5 w-5"></i>
            </button>
        </form>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($courses as $course)
                <article class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
                    <div class="p-3 pb-0">
                        <div class="aspect-video overflow-hidden rounded-[1.3rem] bg-slate-50">
                            @if ($course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full items-center justify-center text-blue-700">
                                    <i data-lucide="graduation-cap" class="h-12 w-12"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <span class="rounded-full px-3 py-1.5 text-[10px] font-black uppercase {{ $course->is_published ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                {{ $course->is_published ? 'Published' : 'Draft' }}
                            </span>

                            <span class="text-xs font-bold text-slate-400">{{ strtoupper($course->level) }}</span>
                        </div>

                        <h2 class="mt-4 text-xl font-black text-slate-950">{{ $course->title }}</h2>
                        <p class="mt-2 line-clamp-2 text-sm text-slate-500">{{ $course->subtitle }}</p>

                        <div class="mt-5 flex gap-4 border-t border-slate-100 pt-5 text-xs font-bold text-slate-500">
                            <span>{{ $course->modules_count }} modul</span>
                            <span>{{ $course->lessons_count }} video</span>
                        </div>

                        <div class="mt-5 flex gap-2">
                            <a href="{{ route('admin.courses.edit', $course) }}" class="flex-1 rounded-xl bg-blue-700 px-4 py-3 text-center text-xs font-black text-white">
                                Kelola Materi
                            </a>

                            <form method="POST" action="{{ route('admin.courses.destroy', $course) }}">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-xl border border-red-100 p-3 text-red-600">
                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-8">{{ $courses->links() }}</div>
    </div>
</x-layouts.admin>
