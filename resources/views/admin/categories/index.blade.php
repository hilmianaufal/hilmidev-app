<x-layouts.admin>
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-blue-600 via-cyan-500 to-blue-400 p-8 text-white shadow-2xl shadow-blue-500/20 mb-8">
            <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/20 rounded-full blur-3xl"></div>

            <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
                <div>
                    <div class="inline-flex items-center gap-2 bg-white/15 border border-white/20 px-4 py-2 rounded-full mb-4">
                        <i data-lucide="grid-3x3" class="w-4 h-4"></i>
                        <span class="text-sm font-bold">Category Management</span>
                    </div>

                    <h1 class="text-3xl md:text-5xl font-black">Kategori</h1>
                    <p class="text-blue-50 mt-3">Kelola kategori source code HilmiDev.</p>
                </div>

                <a href="{{ route('admin.categories.create') }}"
                   class="inline-flex items-center justify-center gap-2 bg-white text-blue-700 px-6 py-4 rounded-2xl font-black shadow-xl shadow-blue-900/20">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    Tambah Kategori
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 flex items-center gap-3 bg-green-50 text-green-700 px-5 py-4 rounded-2xl border border-green-100 font-semibold">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 flex items-center gap-3 bg-red-50 text-red-700 px-5 py-4 rounded-2xl border border-red-100 font-semibold">
                <i data-lucide="alert-circle" class="w-5 h-5"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white border border-blue-100 rounded-[2rem] overflow-hidden shadow-xl shadow-blue-500/5">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-blue-50 text-blue-700">
                        <tr>
                            <th class="px-5 py-4 text-left">Kategori</th>
                            <th class="px-5 py-4 text-left">Slug</th>
                            <th class="px-5 py-4 text-left">Deskripsi</th>
                            <th class="px-5 py-4 text-left">Status</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-blue-50">
                        @forelse ($categories as $category)
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="px-5 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-400 text-white flex items-center justify-center text-xl shadow-lg shadow-blue-500/20">
                                            {{ $category->icon ?? '📦' }}
                                        </div>

                                        <div>
                                            <p class="font-black text-slate-900">{{ $category->name }}</p>
                                            <p class="text-xs text-slate-500 mt-1">
                                                {{ $category->products_count ?? $category->products()->count() }} produk
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-5">
                                    <span class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 px-3 py-2 rounded-2xl font-bold text-xs">
                                        <i data-lucide="link" class="w-3 h-3"></i>
                                        {{ $category->slug }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 text-slate-500 max-w-xs">
                                    <p class="line-clamp-2">
                                        {{ $category->description ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-5 py-5">
                                    @if ($category->is_active)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-bold">
                                            <i data-lucide="check-circle" class="w-3 h-3"></i>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold">
                                            <i data-lucide="x-circle" class="w-3 h-3"></i>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-5 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                           class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-700 hover:bg-blue-100 flex items-center justify-center">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </a>

                                        <form action="{{ route('admin.categories.destroy', $category) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin hapus kategori ini?')">
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
                                <td colspan="5" class="px-5 py-14 text-center">
                                    <div class="w-16 h-16 mx-auto rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                                        <i data-lucide="folder-x" class="w-8 h-8"></i>
                                    </div>
                                    <p class="font-bold text-slate-800">Belum ada kategori.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    </div>
</x-layouts.admin>