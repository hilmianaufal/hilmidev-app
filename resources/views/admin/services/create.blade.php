<x-layouts.admin>
    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-blue-600 via-cyan-500 to-blue-400 p-8 text-white shadow-2xl shadow-blue-500/20 mb-8">
            <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/20 rounded-full blur-3xl"></div>

            <div class="relative flex items-center justify-between gap-4">
                <div>
                    <a href="{{ route('admin.services.index') }}"
                       class="inline-flex items-center gap-2 bg-white/15 border border-white/20 px-4 py-2 rounded-full text-sm font-bold mb-4">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Kembali
                    </a>

                    <h1 class="text-3xl md:text-5xl font-black">
                        Tambah Jasa
                    </h1>

                    <p class="text-blue-50 mt-3">
                        Tambahkan layanan premium HilmiDev untuk client.
                    </p>
                </div>

                <div class="hidden md:flex w-20 h-20 rounded-[2rem] bg-white/20 items-center justify-center">
                    <i data-lucide="briefcase-business" class="w-10 h-10"></i>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.services.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center">
                            <i data-lucide="file-text" class="w-6 h-6"></i>
                        </div>

                        <div>
                            <h2 class="font-black text-slate-900">Informasi Jasa</h2>
                            <p class="text-sm text-slate-500">Data utama layanan HilmiDev.</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Jasa</label>
                            <input type="text"
                                   name="title"
                                   value="{{ old('title') }}"
                                   class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Contoh: Pembuatan Website Company Profile">
                            @error('title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Icon</label>
                            <input type="text"
                                   name="icon"
                                   value="{{ old('icon') }}"
                                   class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="💻">
                            @error('icon') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Singkat</label>
                            <textarea name="short_description"
                                      rows="3"
                                      class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="Deskripsi singkat untuk card jasa">{{ old('short_description') }}</textarea>
                            @error('short_description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Lengkap</label>
                            <textarea name="description"
                                      rows="7"
                                      class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="Jelaskan layanan, proses pengerjaan, benefit, dan estimasi">{{ old('description') }}</textarea>
                            @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Fitur / Benefit</label>
                            <textarea name="features"
                                      rows="6"
                                      class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="Tulis 1 fitur per baris">{{ old('features') }}</textarea>
                            <p class="text-xs text-slate-500 mt-2">
                                Contoh: Desain Premium, Mobile First, SEO Friendly, Admin Panel
                            </p>
                            @error('features') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-2xl shadow-blue-500/10 lg:sticky lg:top-28">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center">
                            <i data-lucide="wallet" class="w-6 h-6"></i>
                        </div>

                        <div>
                            <h2 class="font-black text-slate-900">Harga & Media</h2>
                            <p class="text-sm text-slate-500">Atur harga mulai dan thumbnail.</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Harga Mulai</label>
                            <input type="number"
                                   name="starting_price"
                                   value="{{ old('starting_price') }}"
                                   class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="1500000">
                            @error('starting_price') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Thumbnail</label>
                            <input type="file"
                                   name="thumbnail"
                                   class="w-full rounded-2xl border border-blue-100 bg-blue-50/50 p-3 text-sm">
                            @error('thumbnail') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-3">
                            <label class="flex items-center justify-between gap-3 bg-blue-50 border border-blue-100 rounded-2xl p-4">
                                <span class="font-bold text-slate-700">Featured</span>
                                <input type="checkbox"
                                       name="is_featured"
                                       value="1"
                                       class="rounded text-blue-600">
                            </label>

                            <label class="flex items-center justify-between gap-3 bg-blue-50 border border-blue-100 rounded-2xl p-4">
                                <span class="font-bold text-slate-700">Aktif</span>
                                <input type="checkbox"
                                       name="is_active"
                                       value="1"
                                       class="rounded text-blue-600"
                                       checked>
                            </label>
                        </div>

                        <button class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-black px-6 py-4 rounded-2xl shadow-lg shadow-blue-500/30">
                            <i data-lucide="save" class="w-5 h-5"></i>
                            Simpan Jasa
                        </button>

                        <a href="{{ route('admin.services.index') }}"
                           class="w-full inline-flex items-center justify-center gap-2 bg-blue-50 text-blue-700 font-black px-6 py-4 rounded-2xl">
                            Batal
                        </a>
                    </div>
                </div>
            </aside>
        </form>
    </div>
</x-layouts.admin>