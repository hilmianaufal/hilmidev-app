<x-layouts.admin>
    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-blue-600 via-cyan-500 to-blue-400 p-8 text-white shadow-2xl shadow-blue-500/20 mb-8">
            <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/20 rounded-full blur-3xl"></div>

            <div class="relative flex items-center justify-between gap-4">
                <div>
                    <a href="{{ route('admin.products.index') }}"
                       class="inline-flex items-center gap-2 bg-white/15 border border-white/20 px-4 py-2 rounded-full text-sm font-bold mb-4">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Kembali
                    </a>

                    <h1 class="text-3xl md:text-5xl font-black">
                        Edit Produk
                    </h1>

                    <p class="text-blue-50 mt-3">
                        Perbarui data produk source code HilmiDev.
                    </p>
                </div>

                <div class="hidden md:flex w-20 h-20 rounded-[2rem] bg-white/20 items-center justify-center">
                    <i data-lucide="package-check" class="w-10 h-10"></i>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.products.update', $product) }}"
              method="POST"
              enctype="multipart/form-data"
              class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            @method('PUT')

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center">
                            <i data-lucide="file-text" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="font-black text-slate-900">Informasi Produk</h2>
                            <p class="text-sm text-slate-500">Data utama source code.</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
                            <select name="category_id"
                                    class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                                        {{ $category->icon }} {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Produk</label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $product->name) }}"
                                   class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Contoh: Source Code POS Laravel Premium">
                            @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Singkat</label>
                            <textarea name="short_description"
                                      rows="3"
                                      class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="Deskripsi singkat untuk card produk">{{ old('short_description', $product->short_description) }}</textarea>
                            @error('short_description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Lengkap</label>
                            <textarea name="description"
                                      rows="7"
                                      class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="Jelaskan fitur, keunggulan, teknologi, dan cara penggunaan">{{ old('description', $product->description) }}</textarea>
                            @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Fitur</label>
                            <textarea name="features"
                                      rows="6"
                                      class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="Tulis 1 fitur per baris">{{ old('features', $product->features ? implode("\n", $product->features) : '') }}</textarea>
                            <p class="text-xs text-slate-500 mt-2">
                                Contoh: Login Admin, Manajemen Produk, Laporan Penjualan
                            </p>
                            @error('features') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-cyan-500 text-white flex items-center justify-center">
                            <i data-lucide="link" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="font-black text-slate-900">Teknologi & Link</h2>
                            <p class="text-sm text-slate-500">Demo, video, dan stack teknologi.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Teknologi</label>
                            <input type="text"
                                   name="technology"
                                   value="{{ old('technology', $product->technology) }}"
                                   class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Laravel, MySQL, Tailwind">
                            @error('technology') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Live Demo URL</label>
                            <input type="url"
                                   name="demo_url"
                                   value="{{ old('demo_url', $product->demo_url) }}"
                                   class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="https://demo.hilmidev.com">
                            @error('demo_url') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Video URL</label>
                            <input type="url"
                                   name="video_url"
                                   value="{{ old('video_url', $product->video_url) }}"
                                   class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="https://youtube.com/...">
                            @error('video_url') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
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
                            <h2 class="font-black text-slate-900">Harga & File</h2>
                            <p class="text-sm text-slate-500">Update harga dan source code.</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Harga Normal</label>
                            <input type="number"
                                   name="price"
                                   value="{{ old('price', $product->price) }}"
                                   class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="250000">
                            @error('price') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Harga Diskon</label>
                            <input type="number"
                                   name="discount_price"
                                   value="{{ old('discount_price', $product->discount_price) }}"
                                   class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="199000">
                            @error('discount_price') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Thumbnail Saat Ini</label>

                            @if ($product->thumbnail)
                                <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                     class="w-full rounded-2xl border border-blue-100 mb-3 shadow-lg shadow-blue-500/10"
                                     alt="{{ $product->name }}">
                            @else
                                <div class="w-full aspect-video rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-400 mb-3">
                                    <i data-lucide="image" class="w-10 h-10"></i>
                                </div>
                            @endif

                            <input type="file"
                                   name="thumbnail"
                                   class="w-full rounded-2xl border border-blue-100 bg-blue-50/50 p-3 text-sm">
                            @error('thumbnail') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">File Source Code Baru</label>
                            <input type="file"
                                   name="file_path"
                                   class="w-full rounded-2xl border border-blue-100 bg-blue-50/50 p-3 text-sm">

                            @if ($product->file_path)
                                <p class="inline-flex items-center gap-1 text-xs text-green-700 bg-green-50 px-3 py-2 rounded-full mt-2 font-bold">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i>
                                    File source code sudah tersedia.
                                </p>
                            @else
                                <p class="text-xs text-slate-500 mt-2">Belum ada file source code.</p>
                            @endif

                            @error('file_path') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-3">
                            <label class="flex items-center justify-between gap-3 bg-blue-50 border border-blue-100 rounded-2xl p-4">
                                <span class="font-bold text-slate-700">Featured</span>
                                <input type="checkbox"
                                       name="is_featured"
                                       value="1"
                                       class="rounded text-blue-600"
                                       @checked(old('is_featured', $product->is_featured))>
                            </label>

                            <label class="flex items-center justify-between gap-3 bg-blue-50 border border-blue-100 rounded-2xl p-4">
                                <span class="font-bold text-slate-700">Aktif</span>
                                <input type="checkbox"
                                       name="is_active"
                                       value="1"
                                       class="rounded text-blue-600"
                                       @checked(old('is_active', $product->is_active))>
                            </label>
                        </div>

                        <button class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-black px-6 py-4 rounded-2xl shadow-lg shadow-blue-500/30">
                            <i data-lucide="save" class="w-5 h-5"></i>
                            Update Produk
                        </button>

                        <a href="{{ route('admin.products.index') }}"
                           class="w-full inline-flex items-center justify-center gap-2 bg-blue-50 text-blue-700 font-black px-6 py-4 rounded-2xl">
                            Batal
                        </a>
                    </div>
                </div>
            </aside>
        </form>
    </div>
</x-layouts.admin>