<div>
    <label class="mb-2 block text-sm font-black text-slate-700">Judul Kelas</label>
    <input name="title" value="{{ old('title', $course?->title) }}" class="w-full rounded-2xl border-slate-200" required>
</div>

<div>
    <label class="mb-2 block text-sm font-black text-slate-700">Subjudul</label>
    <input name="subtitle" value="{{ old('subtitle', $course?->subtitle) }}" class="w-full rounded-2xl border-slate-200">
</div>

<div>
    <label class="mb-2 block text-sm font-black text-slate-700">Deskripsi</label>
    <textarea name="description" rows="6" class="w-full rounded-2xl border-slate-200">{{ old('description', $course?->description) }}</textarea>
</div>

<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Level</label>
        <select name="level" class="w-full rounded-2xl border-slate-200">
            @foreach (['pemula', 'menengah', 'mahir'] as $level)
                <option value="{{ $level }}" @selected(old('level', $course?->level ?? 'pemula') === $level)>{{ ucfirst($level) }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Teknologi</label>
        <input name="technology" value="{{ old('technology', $course?->technology) }}" class="w-full rounded-2xl border-slate-200" placeholder="Laravel, Tailwind">
    </div>

    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Estimasi Menit</label>
        <input type="number" min="0" name="estimated_minutes" value="{{ old('estimated_minutes', $course?->estimated_minutes ?? 0) }}" class="w-full rounded-2xl border-slate-200">
    </div>

    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Urutan</label>
        <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $course?->sort_order ?? 0) }}" class="w-full rounded-2xl border-slate-200">
    </div>
</div>

<div>
    <label class="mb-2 block text-sm font-black text-slate-700">Thumbnail</label>
    <input type="file" name="thumbnail" accept="image/*" class="w-full rounded-2xl border border-slate-200 p-3">
</div>

<div class="flex flex-wrap gap-6">
    <label class="flex items-center gap-2 font-bold text-slate-700">
        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $course?->is_published ?? false))>
        Published
    </label>

    <label class="flex items-center gap-2 font-bold text-slate-700">
        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $course?->is_featured ?? false))>
        Kelas pilihan
    </label>
</div>

<button class="w-full rounded-2xl bg-blue-700 px-6 py-4 font-black text-white">Simpan Kelas</button>
