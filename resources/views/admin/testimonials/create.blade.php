<x-layouts.admin>
    <div class="max-w-5xl mx-auto px-4 py-10">
        <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-blue-600 via-cyan-500 to-blue-400 p-8 text-white shadow-2xl shadow-blue-500/20 mb-8">
            <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/20 rounded-full blur-3xl"></div>

            <div class="relative flex items-center justify-between gap-4">
                <div>
                    <a href="{{ route('admin.testimonials.index') }}"
                       class="inline-flex items-center gap-2 bg-white/15 border border-white/20 px-4 py-2 rounded-full text-sm font-bold mb-4">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Kembali
                    </a>

                    <h1 class="text-3xl md:text-5xl font-black">
                        Tambah Testimonial
                    </h1>

                    <p class="text-blue-50 mt-3">
                        Tambahkan review client untuk meningkatkan trust HilmiDev.
                    </p>
                </div>

                <div class="hidden md:flex w-20 h-20 rounded-[2rem] bg-white/20 items-center justify-center">
                    <i data-lucide="message-square-heart" class="w-10 h-10"></i>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.testimonials.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center">
                            <i data-lucide="user-round" class="w-6 h-6"></i>
                        </div>

                        <div>
                            <h2 class="font-black text-slate-900">Data Client</h2>
                            <p class="text-sm text-slate-500">Identitas pemberi testimonial.</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Client</label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Contoh: Budi Santoso">
                            @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Jabatan</label>
                                <input type="text"
                                       name="position"
                                       value="{{ old('position') }}"
                                       class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="CEO / Owner / Manager">
                                @error('position') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Perusahaan</label>
                                <input type="text"
                                       name="company"
                                       value="{{ old('company') }}"
                                       class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="PT Maju Bersama">
                                @error('company') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Review</label>
                            <textarea name="review"
                                      rows="7"
                                      class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="Tulis review client...">{{ old('review') }}</textarea>
                            @error('review') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-2xl shadow-blue-500/10 lg:sticky lg:top-28">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center">
                            <i data-lucide="star" class="w-6 h-6"></i>
                        </div>

                        <div>
                            <h2 class="font-black text-slate-900">Rating & Foto</h2>
                            <p class="text-sm text-slate-500">Atur rating dan status tampil.</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Rating</label>
                            <select name="rating"
                                    class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500">
                                <option value="5" @selected(old('rating') == 5)>⭐⭐⭐⭐⭐ - 5</option>
                                <option value="4" @selected(old('rating') == 4)>⭐⭐⭐⭐ - 4</option>
                                <option value="3" @selected(old('rating') == 3)>⭐⭐⭐ - 3</option>
                                <option value="2" @selected(old('rating') == 2)>⭐⭐ - 2</option>
                                <option value="1" @selected(old('rating') == 1)>⭐ - 1</option>
                            </select>
                            @error('rating') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Foto Client</label>
                            <input type="file"
                                   name="photo"
                                   class="w-full rounded-2xl border border-blue-100 bg-blue-50/50 p-3 text-sm">
                            @error('photo') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
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
                            Simpan Testimonial
                        </button>

                        <a href="{{ route('admin.testimonials.index') }}"
                           class="w-full inline-flex items-center justify-center gap-2 bg-blue-50 text-blue-700 font-black px-6 py-4 rounded-2xl">
                            Batal
                        </a>
                    </div>
                </div>
            </aside>
        </form>
    </div>
</x-layouts.admin>