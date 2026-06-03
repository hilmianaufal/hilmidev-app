<x-layouts.admin>
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-blue-600 via-cyan-500 to-blue-400 p-8 text-white shadow-2xl shadow-blue-500/20 mb-8">
            <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/20 rounded-full blur-3xl"></div>

            <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
                <div>
                    <div class="inline-flex items-center gap-2 bg-white/15 border border-white/20 px-4 py-2 rounded-full mb-4">
                        <i data-lucide="message-square-heart" class="w-4 h-4"></i>
                        <span class="text-sm font-bold">Testimonial Management</span>
                    </div>

                    <h1 class="text-3xl md:text-5xl font-black">
                        Testimonial
                    </h1>

                    <p class="text-blue-50 mt-3">
                        Kelola review dan kepercayaan client HilmiDev.
                    </p>
                </div>

                <a href="{{ route('admin.testimonials.create') }}"
                   class="inline-flex items-center justify-center gap-2 bg-white text-blue-700 px-6 py-4 rounded-2xl font-black shadow-xl shadow-blue-900/20">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    Tambah Testimonial
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
                            <th class="px-5 py-4 text-left">Client</th>
                            <th class="px-5 py-4 text-left">Review</th>
                            <th class="px-5 py-4 text-left">Rating</th>
                            <th class="px-5 py-4 text-left">Status</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-blue-50">
                        @forelse ($testimonials as $testimonial)
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="px-5 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-100 overflow-hidden flex items-center justify-center">
                                            @if ($testimonial->photo)
                                                <img src="{{ asset('storage/' . $testimonial->photo) }}"
                                                     class="w-full h-full object-cover"
                                                     alt="{{ $testimonial->name }}">
                                            @else
                                                <i data-lucide="user" class="w-6 h-6 text-blue-500"></i>
                                            @endif
                                        </div>

                                        <div>
                                            <p class="font-black text-slate-900">
                                                {{ $testimonial->name }}
                                            </p>

                                            <p class="text-xs text-slate-500 mt-1">
                                                {{ $testimonial->position ?? '-' }}
                                                @if ($testimonial->company)
                                                    • {{ $testimonial->company }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-5 text-slate-600 max-w-md">
                                    <p class="line-clamp-2">
                                        {{ $testimonial->review }}
                                    </p>
                                </td>

                                <td class="px-5 py-5">
                                    <div class="flex items-center gap-1 text-amber-400">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $testimonial->rating)
                                                <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                            @else
                                                <i data-lucide="star" class="w-4 h-4 text-slate-200"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </td>

                                <td class="px-5 py-5">
                                    <div class="flex flex-wrap gap-2">
                                        @if ($testimonial->is_active)
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

                                        @if ($testimonial->is_featured)
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-bold">
                                                <i data-lucide="star" class="w-3 h-3"></i>
                                                Featured
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-5 py-5 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.testimonials.edit', $testimonial) }}"
                                           class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-700 hover:bg-blue-100 flex items-center justify-center">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </a>

                                        <form action="{{ route('admin.testimonials.destroy', $testimonial) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin hapus testimonial ini?')">
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
                                        <i data-lucide="message-square-heart" class="w-8 h-8"></i>
                                    </div>

                                    <p class="font-black text-slate-900">Belum ada testimonial.</p>
                                    <p class="text-slate-500 text-sm mt-1">
                                        Tambahkan review client pertama.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $testimonials->links() }}
        </div>
    </div>
</x-layouts.admin>