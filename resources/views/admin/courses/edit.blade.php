<x-layouts.admin>
    <div class="mx-auto max-w-7xl px-4 py-10">
        <a href="{{ route('admin.courses.index') }}" class="text-sm font-black text-blue-700">← Kembali</a>

        @if (session('success'))
            <div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 font-bold text-emerald-700">{{ session('success') }}</div>
        @endif

        <div class="mt-6 grid items-start gap-8 xl:grid-cols-12">
            <section class="xl:col-span-5">
                <h1 class="text-3xl font-black text-slate-950">Edit Kelas</h1>

                <form method="POST" action="{{ route('admin.courses.update', $course) }}" enctype="multipart/form-data" class="mt-6 space-y-5 rounded-[2rem] border border-slate-200 bg-white p-7 shadow-sm">
                    @csrf
                    @method('PUT')
                    @include('admin.courses.form', ['course' => $course])
                </form>
            </section>

            <section class="xl:col-span-7">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.16em] text-blue-700">Kurikulum</p>
                    <h2 class="mt-2 text-3xl font-black text-slate-950">Modul dan Video</h2>
                </div>

                <form method="POST" action="{{ route('admin.courses.modules.store', $course) }}" class="mt-6 grid gap-3 rounded-2xl border border-blue-200 bg-blue-50 p-5 sm:grid-cols-12">
                    @csrf
                    <input name="title" placeholder="Judul modul baru" class="rounded-xl border-blue-200 sm:col-span-7" required>
                    <input type="number" name="sort_order" min="0" value="0" class="rounded-xl border-blue-200 sm:col-span-2">
                    <button class="rounded-xl bg-blue-700 px-4 py-3 text-xs font-black text-white sm:col-span-3">Tambah Modul</button>
                </form>

                <div class="mt-6 space-y-6">
                    @foreach ($course->modules as $module)
                        <article class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
                            <div class="border-b border-slate-100 p-5">
                                <form method="POST" action="{{ route('admin.modules.update', $module) }}" class="grid gap-3 sm:grid-cols-12">
                                    @csrf
                                    @method('PUT')

                                    <input name="title" value="{{ $module->title }}" class="rounded-xl border-slate-200 font-bold sm:col-span-7">
                                    <input type="number" name="sort_order" value="{{ $module->sort_order }}" min="0" class="rounded-xl border-slate-200 sm:col-span-2">
                                    <button class="rounded-xl border border-slate-200 px-3 text-xs font-black text-blue-700 sm:col-span-2">Simpan</button>
                                </form>

                                <form method="POST" action="{{ route('admin.modules.destroy', $module) }}" class="mt-3">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs font-black text-red-600">Hapus modul</button>
                                </form>
                            </div>

                            <div class="divide-y divide-slate-100">
                                @foreach ($module->lessons as $lesson)
                                    <details>
                                        <summary class="flex cursor-pointer items-center justify-between gap-4 px-5 py-4">
                                            <div>
                                                <p class="text-sm font-black text-slate-800">{{ $lesson->title }}</p>
                                                <p class="mt-1 text-xs text-slate-400">{{ strtoupper($lesson->video_type) }} · {{ $lesson->duration_minutes }} menit</p>
                                            </div>

                                            <span class="rounded-full {{ $lesson->is_published ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }} px-3 py-1.5 text-[10px] font-black">
                                                {{ $lesson->is_published ? 'Published' : 'Draft' }}
                                            </span>
                                        </summary>

                                        <form method="POST" action="{{ route('admin.lessons.update', $lesson) }}" enctype="multipart/form-data" class="grid gap-4 border-t border-slate-100 bg-slate-50 p-5">
                                            @csrf
                                            @method('PUT')

                                            <input name="title" value="{{ $lesson->title }}" class="rounded-xl border-slate-200" required>
                                            <textarea name="description" rows="3" class="rounded-xl border-slate-200">{{ $lesson->description }}</textarea>

                                            <div class="grid gap-3 sm:grid-cols-3">
                                                <select name="video_type" class="rounded-xl border-slate-200">
                                                    @foreach (['upload', 'youtube', 'vimeo', 'external'] as $type)
                                                        <option value="{{ $type }}" @selected($lesson->video_type === $type)>{{ strtoupper($type) }}</option>
                                                    @endforeach
                                                </select>

                                                <input name="video_url" value="{{ $lesson->video_url }}" placeholder="URL video" class="rounded-xl border-slate-200 sm:col-span-2">
                                            </div>

                                            <div class="grid gap-3 sm:grid-cols-2">
                                                <label class="rounded-xl border border-slate-200 bg-white p-3 text-xs font-bold">
                                                    Ganti video upload
                                                    <input type="file" name="video_file" accept="video/mp4,video/webm,video/quicktime" class="mt-2 block w-full">
                                                </label>

                                                <label class="rounded-xl border border-slate-200 bg-white p-3 text-xs font-bold">
                                                    Ganti file materi
                                                    <input type="file" name="attachment" class="mt-2 block w-full">
                                                </label>
                                            </div>

                                            <div class="grid gap-3 sm:grid-cols-2">
                                                <input type="number" min="0" name="duration_minutes" value="{{ $lesson->duration_minutes }}" placeholder="Durasi menit" class="rounded-xl border-slate-200">
                                                <input type="number" min="0" name="sort_order" value="{{ $lesson->sort_order }}" placeholder="Urutan" class="rounded-xl border-slate-200">
                                            </div>

                                            <div class="flex gap-5 text-sm font-bold">
                                                <label><input type="checkbox" name="is_preview" value="1" @checked($lesson->is_preview)> Preview</label>
                                                <label><input type="checkbox" name="is_published" value="1" @checked($lesson->is_published)> Published</label>
                                            </div>

                                            <button class="rounded-xl bg-blue-700 px-5 py-3 text-xs font-black text-white">Simpan Video</button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.lessons.destroy', $lesson) }}" class="bg-slate-50 px-5 pb-5">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-xs font-black text-red-600">Hapus video</button>
                                        </form>
                                    </details>
                                @endforeach
                            </div>

                            <details class="border-t border-slate-100">
                                <summary class="cursor-pointer px-5 py-4 text-sm font-black text-blue-700">+ Tambah Video Pembelajaran</summary>

                                <form method="POST" action="{{ route('admin.modules.lessons.store', $module) }}" enctype="multipart/form-data" class="grid gap-4 bg-blue-50 p-5">
                                    @csrf

                                    <input name="title" placeholder="Judul video" class="rounded-xl border-blue-200" required>
                                    <textarea name="description" rows="3" placeholder="Deskripsi materi" class="rounded-xl border-blue-200"></textarea>

                                    <div class="grid gap-3 sm:grid-cols-3">
                                        <select name="video_type" class="rounded-xl border-blue-200">
                                            <option value="upload">UPLOAD PRIVATE</option>
                                            <option value="youtube">YOUTUBE</option>
                                            <option value="vimeo">VIMEO</option>
                                            <option value="external">EXTERNAL</option>
                                        </select>

                                        <input name="video_url" placeholder="URL video bila tidak upload" class="rounded-xl border-blue-200 sm:col-span-2">
                                    </div>

                                    <div class="grid gap-3 sm:grid-cols-2">
                                        <label class="rounded-xl border border-blue-200 bg-white p-3 text-xs font-bold">
                                            File video
                                            <input type="file" name="video_file" accept="video/mp4,video/webm,video/quicktime" class="mt-2 block w-full">
                                        </label>

                                        <label class="rounded-xl border border-blue-200 bg-white p-3 text-xs font-bold">
                                            File source code/materi
                                            <input type="file" name="attachment" class="mt-2 block w-full">
                                        </label>
                                    </div>

                                    <div class="grid gap-3 sm:grid-cols-2">
                                        <input type="number" min="0" name="duration_minutes" value="0" placeholder="Durasi menit" class="rounded-xl border-blue-200">
                                        <input type="number" min="0" name="sort_order" value="0" placeholder="Urutan" class="rounded-xl border-blue-200">
                                    </div>

                                    <div class="flex gap-5 text-sm font-bold">
                                        <label><input type="checkbox" name="is_preview" value="1"> Preview publik</label>
                                        <label><input type="checkbox" name="is_published" value="1" checked> Published</label>
                                    </div>

                                    <button class="rounded-xl bg-blue-700 px-5 py-3 text-xs font-black text-white">Tambah Video</button>
                                </form>
                            </details>
                        </article>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
</x-layouts.admin>
