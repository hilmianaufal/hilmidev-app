<x-layouts.admin>
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-blue-600 via-cyan-500 to-blue-400 p-8 text-white shadow-2xl shadow-blue-500/20 mb-8">
            <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/20 rounded-full blur-3xl"></div>

            <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
                <div>
                    <div class="inline-flex items-center gap-2 bg-white/15 border border-white/20 px-4 py-2 rounded-full mb-4">
                        <i data-lucide="package-search" class="w-4 h-4"></i>
                        <span class="text-sm font-bold">Product Management</span>
                    </div>

                    <h1 class="text-3xl md:text-5xl font-black">Produk Source Code</h1>
                    <p class="text-blue-50 mt-3">Kelola semua produk digital HilmiDev.</p>
                </div>

                <a href="{{ route('admin.products.create') }}"
                   class="inline-flex items-center justify-center gap-2 bg-white text-blue-700 px-6 py-4 rounded-2xl font-black shadow-xl shadow-blue-900/20">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    Tambah Produk
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
                            <th class="px-5 py-4 text-left">Produk</th>
                            <th class="px-5 py-4 text-left">Kategori</th>
                            <th class="px-5 py-4 text-left">Harga</th>
                            <th class="px-5 py-4 text-left">Status</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-blue-50">
                        @forelse ($products as $product)
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="px-5 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-12 bg-blue-50 rounded-2xl overflow-hidden border border-blue-100">
                                            @if ($product->thumbnail)
                                                <img src="{{ asset('storage/' . $product->thumbnail) }}"
                                                     class="w-full h-full object-cover"
                                                     alt="{{ $product->name }}">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-blue-400">
                                                    <i data-lucide="image" class="w-5 h-5"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <div>
                                            <p class="font-black text-slate-900">{{ $product->name }}</p>
                                            <p class="text-xs text-slate-500 mt-1">{{ $product->slug }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-5">
                                    <span class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 px-3 py-2 rounded-2xl font-bold text-xs">
                                        <i data-lucide="tag" class="w-3 h-3"></i>
                                        {{ $product->category->name ?? '-' }}
                                    </span>
                                </td>

                                <td class="px-5 py-5">
                                    @if ($product->discount_price)
                                        <p class="line-through text-slate-400 text-xs">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </p>
                                    @endif

                                    <p class="font-black text-blue-600">
                                        Rp {{ number_format($product->final_price, 0, ',', '.') }}
                                    </p>
                                </td>

                                <td class="px-5 py-5">
                                    <div class="flex flex-wrap gap-2">
                                        @if ($product->is_active)
                                            <span class="px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-bold">Aktif</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold">Nonaktif</span>
                                        @endif

                                        @if ($product->is_featured)
                                            <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-bold">Featured</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-5 py-5 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('products.show', $product) }}" target="_blank"
                                           class="w-10 h-10 rounded-2xl border border-blue-100 text-blue-600 hover:bg-blue-50 flex items-center justify-center">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>

                                        <a href="{{ route('admin.products.edit', $product) }}"
                                           class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-700 hover:bg-blue-100 flex items-center justify-center">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </a>

                                        <form action="{{ route('admin.products.destroy', $product) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin hapus produk ini?')">
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
                                    <p class="font-bold text-slate-800">Belum ada produk.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</x-layouts.admin>