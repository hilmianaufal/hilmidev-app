<x-layouts.admin>
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-blue-600 via-cyan-500 to-blue-400 p-8 text-white shadow-2xl shadow-blue-500/20 mb-8">
            <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/20 rounded-full blur-3xl"></div>

            <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
                <div>
                    <div class="inline-flex items-center gap-2 bg-white/15 border border-white/20 px-4 py-2 rounded-full mb-4">
                        <i data-lucide="newspaper" class="w-4 h-4"></i>
                        <span class="text-sm font-bold">Blog SEO Management</span>
                    </div>

                    <h1 class="text-3xl md:text-5xl font-black">
                        Blog SEO
                    </h1>

                    <p class="text-blue-50 mt-3">
                        Kelola artikel untuk mendatangkan traffic Google ke HilmiDev.
                    </p>
                </div>

                <a href="{{ route('admin.posts.create') }}"
                   class="inline-flex items-center justify-center gap-2 bg-white text-blue-700 px-6 py-4 rounded-2xl font-black shadow-xl shadow-blue-900/20">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    Tambah Artikel
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 flex items-center gap-3 bg-green-50 text-green-700 px-5 py-4 rounded-2xl border border-green-100 font-semibold">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-blue-100 rounded-[2rem] overflow-hidden shadow-xl shadow-blue-500/5">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-blue-50 text-blue-700">
                        <tr>
                            <th class="px-5 py-4 text-left">Artikel</th>
                            <th class="px-5 py-4 text-left">Author</th>
                            <th class="px-5 py-4 text-left">Status</th>
                            <th class="px-5 py-4 text-left">Published</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-blue-50">
                        @forelse ($posts as $post)
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="px-5 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-20 h-14 rounded-2xl overflow-hidden bg-blue-50 border border-blue-100 flex items-center justify-center">
                                            @if ($post->thumbnail)
                                                <img src="{{ asset('storage/' . $post->thumbnail) }}"
                                                     class="w-full h-full object-cover"
                                                     alt="{{ $post->title }}">
                                            @else
                                                <i data-lucide="image" class="w-6 h-6 text-blue-500"></i>
                                            @endif
                                        </div>

                                        <div>
                                            <p class="font-black text-slate-900">
                                                {{ $post->title }}
                                            </p>

                                            <p class="text-xs text-slate-500 mt-1 line-clamp-1 max-w-md">
                                                {{ $post->excerpt ?? $post->slug }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-5">
                                    <p class="font-bold text-slate-700">
                                        {{ $post->author->name ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-5 py-5">
                                    @if ($post->is_published)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-bold">
                                            <i data-lucide="check-circle" class="w-3 h-3"></i>
                                            Published
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-bold">
                                            <i data-lucide="clock" class="w-3 h-3"></i>
                                            Draft
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-5 text-slate-500">
                                    {{ $post->published_at ? $post->published_at->format('d M Y H:i') : '-' }}
                                </td>

                                <td class="px-5 py-5 text-right">
                                    <div class="flex justify-end gap-2">
                                        @if ($post->is_published)
                                            <a href="{{ route('blog.show', $post) }}"
                                               target="_blank"
                                               class="w-10 h-10 rounded-2xl border border-blue-100 text-blue-600 hover:bg-blue-50 flex items-center justify-center">
                                                <i data-lucide="external-link" class="w-4 h-4"></i>
                                            </a>
                                        @endif

                                        <a href="{{ route('admin.posts.edit', $post) }}"
                                           class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-700 hover:bg-blue-100 flex items-center justify-center">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </a>

                                        <form action="{{ route('admin.posts.destroy', $post) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin hapus artikel ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="w-10 h-10 rounded-2xl bg-red-50 text-red-700 hover:bg-red-100 flex items-center justify-center">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-16 text-center">
                                    <div class="w-16 h-16 mx-auto rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                                        <i data-lucide="newspaper" class="w-8 h-8"></i>
                                    </div>

                                    <p class="font-black text-slate-900">Belum ada artikel.</p>
                                    <p class="text-slate-500 text-sm mt-1">
                                        Tambahkan artikel SEO pertama HilmiDev.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    </div>
</x-layouts.admin>