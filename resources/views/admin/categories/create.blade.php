<x-layouts.admin>
    <div class="max-w-4xl mx-auto px-4 py-10">
        <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-blue-600 via-cyan-500 to-blue-400 p-8 text-white shadow-2xl shadow-blue-500/20 mb-8">
            <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/20 rounded-full blur-3xl"></div>

            <div class="relative">
                <a href="{{ route('admin.categories.index') }}"
                   class="inline-flex items-center gap-2 bg-white/15 border border-white/20 px-4 py-2 rounded-full text-sm font-bold mb-4">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Kembali
                </a>

                <h1 class="text-3xl md:text-5xl font-black">Tambah Kategori</h1>
                <p class="text-blue-50 mt-3">Buat kategori baru untuk produk source code.</p>
            </div>
        </div>

        <form action="{{ route('admin.categories.store') }}"
              method="POST"
              class="bg-white border border-blue-100 rounded-[2rem] p-6 md:p-8 shadow-xl shadow-blue-500/5 space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Kategori</label>
                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                       placeholder="Laravel">
                @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Icon</label>
                <input type="text"
                       name="icon"
                       value="{{ old('icon') }}"
                       class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                       placeholder="🚀">
                @error('icon') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi</label>
                <textarea name="description"
                          rows="5"
                          class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                          placeholder="Deskripsi singkat kategori">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <label class="flex items-center justify-between gap-3 bg-blue-50 border border-blue-100 rounded-2xl p-4">
                <span class="font-bold text-slate-700">Status Aktif</span>
                <input type="checkbox" name="is_active" value="1" class="rounded text-blue-600" checked>
            </label>

            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                <a href="{{ route('admin.categories.index') }}"
                   class="inline-flex items-center justify-center gap-2 bg-blue-50 text-blue-700 font-black px-6 py-4 rounded-2xl">
                    Batal
                </a>

                <button class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-black px-6 py-4 rounded-2xl shadow-lg shadow-blue-500/30">
                    <i data-lucide="save" class="w-5 h-5"></i>
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>