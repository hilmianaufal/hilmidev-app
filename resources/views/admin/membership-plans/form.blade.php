<div>
    <label class="mb-2 block text-sm font-black text-slate-700">Nama Paket</label>
    <input name="name" value="{{ old('name', $plan?->name) }}" class="w-full rounded-2xl border-slate-200" required>
    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label class="mb-2 block text-sm font-black text-slate-700">Deskripsi</label>
    <textarea name="description" rows="4" class="w-full rounded-2xl border-slate-200">{{ old('description', $plan?->description) }}</textarea>
</div>

<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Harga</label>
        <input type="number" min="0" name="price" value="{{ old('price', $plan?->price ?? 0) }}" class="w-full rounded-2xl border-slate-200" required>
    </div>
    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Durasi Hari</label>
        <input type="number" min="1" name="duration_days" value="{{ old('duration_days', $plan?->duration_days) }}" class="w-full rounded-2xl border-slate-200" placeholder="Kosong = selamanya">
    </div>
</div>

<div>
    <label class="mb-2 block text-sm font-black text-slate-700">Fasilitas</label>
    <textarea name="features" rows="6" class="w-full rounded-2xl border-slate-200" placeholder="Satu fasilitas per baris">{{ old('features', $plan?->features ? implode("\n", $plan->features) : '') }}</textarea>
</div>

<div class="flex flex-wrap gap-6">
    <label class="flex items-center gap-2 font-bold text-slate-700">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $plan?->is_active ?? true))>
        Paket aktif
    </label>

    <label class="flex items-center gap-2 font-bold text-slate-700">
        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $plan?->is_featured ?? false))>
        Paket pilihan
    </label>
</div>

<button class="w-full rounded-2xl bg-blue-700 px-6 py-4 font-black text-white">Simpan Paket</button>
