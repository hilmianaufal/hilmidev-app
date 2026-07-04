<x-layouts.admin>
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-blue-600 via-cyan-500 to-blue-400 p-8 text-white shadow-2xl shadow-blue-500/20 mb-8">
            <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/20 rounded-full blur-3xl"></div>

            <div class="relative flex items-center justify-between gap-4">
                <div>
                    <a href="{{ route('admin.posts.index') }}"
                       class="inline-flex items-center gap-2 bg-white/15 border border-white/20 px-4 py-2 rounded-full text-sm font-bold mb-4">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Kembali
                    </a>

                    <h1 class="text-3xl md:text-5xl font-black">
                        Edit Artikel SEO
                    </h1>

                    <p class="text-blue-50 mt-3">
                        Perbarui artikel blog SEO HilmiDev.
                    </p>
                </div>

                <div class="hidden md:flex w-20 h-20 rounded-[2rem] bg-white/20 items-center justify-center">
                    <i data-lucide="newspaper" class="w-10 h-10"></i>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.posts.update', $post) }}"
              method="POST"
              enctype="multipart/form-data"
              class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            @method('PUT')

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center">
                            <i data-lucide="edit-3" class="w-6 h-6"></i>
                        </div>

                        <div>
                            <h2 class="font-black text-slate-900">Konten Artikel</h2>
                            <p class="text-sm text-slate-500">Judul, ringkasan, dan isi artikel.</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Judul Artikel
                            </label>

                            <input type="text"
                                   name="title"
                                   value="{{ old('title', $post->title) }}"
                                   class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500">

                            @error('title')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Excerpt / Ringkasan
                            </label>

                            <textarea name="excerpt"
                                      rows="3"
                                      class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500">{{ old('excerpt', $post->excerpt) }}</textarea>

                            @error('excerpt')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Isi Artikel
                            </label>

                            <textarea id="content"
                                      name="content"
                                      rows="16"
                                      class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500">{{ old('content', $post->content) }}</textarea>

                            @error('content')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-cyan-500 text-white flex items-center justify-center">
                            <i data-lucide="search" class="w-6 h-6"></i>
                        </div>

                        <div>
                            <h2 class="font-black text-slate-900">SEO Meta</h2>
                            <p class="text-sm text-slate-500">Optimasi title dan description untuk Google.</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Meta Title
                            </label>

                            <input type="text"
                                   name="meta_title"
                                   value="{{ old('meta_title', $post->meta_title) }}"
                                   class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500">

                            @error('meta_title')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Meta Description
                            </label>

                            <textarea name="meta_description"
                                      rows="4"
                                      class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500">{{ old('meta_description', $post->meta_description) }}</textarea>

                            @error('meta_description')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-2xl shadow-blue-500/10 lg:sticky lg:top-28">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center">
                            <i data-lucide="settings-2" class="w-6 h-6"></i>
                        </div>

                        <div>
                            <h2 class="font-black text-slate-900">Publish</h2>
                            <p class="text-sm text-slate-500">Thumbnail dan status artikel.</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Thumbnail Saat Ini
                            </label>

                            @if ($post->thumbnail)
                                <img src="{{ asset('storage/' . $post->thumbnail) }}"
                                     class="w-full aspect-video object-cover rounded-2xl border border-blue-100 mb-3 shadow-lg shadow-blue-500/10"
                                     alt="{{ $post->title }}">
                            @else
                                <div class="w-full aspect-video rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-500 mb-3">
                                    <i data-lucide="image" class="w-10 h-10"></i>
                                </div>
                            @endif

                            <input type="file"
                                   name="thumbnail"
                                   class="w-full rounded-2xl border border-blue-100 bg-blue-50/50 p-3 text-sm">

                            @error('thumbnail')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <label class="flex items-center justify-between gap-3 bg-blue-50 border border-blue-100 rounded-2xl p-4">
                            <span class="font-bold text-slate-700">Publish</span>
                            <input type="checkbox"
                                   name="is_published"
                                   value="1"
                                   class="rounded text-blue-600"
                                   @checked(old('is_published', $post->is_published))>
                        </label>

                        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
                            <p class="text-xs text-slate-500 font-bold mb-1">Slug</p>
                            <p class="text-sm font-black text-blue-600 break-all">
                                {{ $post->slug }}
                            </p>
                        </div>

                        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
                            <p class="text-xs text-slate-500 font-bold mb-1">Published At</p>
                            <p class="text-sm font-black text-slate-800">
                                {{ $post->published_at ? $post->published_at->format('d M Y H:i') : '-' }}
                            </p>
                        </div>

                        <button class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-black px-6 py-4 rounded-2xl shadow-lg shadow-blue-500/30">
                            <i data-lucide="save" class="w-5 h-5"></i>
                            Update Artikel
                        </button>

                        <a href="{{ route('admin.posts.index') }}"
                           class="w-full inline-flex items-center justify-center gap-2 bg-blue-50 text-blue-700 font-black px-6 py-4 rounded-2xl">
                            Batal
                        </a>
                    </div>
                </div>
            </aside>
        </form>
    </div>

    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            tinymce.init({
                selector: '#content',
                height: 560,
                menubar: false,
                branding: false,
                plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
                toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link image media table | code fullscreen preview',
                content_style: 'body { font-family: Plus Jakarta Sans, Arial, sans-serif; font-size: 16px; line-height: 1.8; }',
            });
        });
    </script>
</x-layouts.admin>