<x-layouts.admin>
    <div class="mx-auto max-w-7xl px-4 py-10">
        <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.16em] text-blue-700">Membership</p>
                <h1 class="mt-2 text-3xl font-black text-slate-950">Paket Membership</h1>
                <p class="mt-2 text-sm text-slate-500">Atur harga, durasi, dan fasilitas bimbingan.</p>
            </div>
            <a href="{{ route('admin.membership-plans.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-700 px-5 py-4 text-sm font-black text-white">
                <i data-lucide="plus" class="h-5 w-5"></i>
                Tambah Paket
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 font-bold text-emerald-700">{{ session('success') }}</div>
        @endif

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($plans as $plan)
                <article class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <span class="rounded-full {{ $plan->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }} px-3 py-1.5 text-[10px] font-black uppercase">
                                {{ $plan->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                            <h2 class="mt-4 text-xl font-black text-slate-950">{{ $plan->name }}</h2>
                        </div>

                        @if ($plan->is_featured)
                            <i data-lucide="star" class="h-5 w-5 fill-amber-400 text-amber-400"></i>
                        @endif
                    </div>

                    <p class="mt-4 text-2xl font-black text-blue-700">Rp {{ number_format($plan->price, 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs font-bold text-slate-400">
                        {{ $plan->duration_days ? $plan->duration_days . ' hari' : 'Akses selamanya' }}
                    </p>

                    <p class="mt-4 line-clamp-3 text-sm leading-6 text-slate-500">{{ $plan->description }}</p>

                    <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-5">
                        <span class="text-xs font-bold text-slate-500">{{ $plan->memberships_count }} membership</span>

                        <div class="flex gap-2">
                            <a href="{{ route('admin.membership-plans.edit', $plan) }}" class="rounded-xl border border-slate-200 p-3 text-blue-700">
                                <i data-lucide="pencil" class="h-4 w-4"></i>
                            </a>

                            <form method="POST" action="{{ route('admin.membership-plans.destroy', $plan) }}">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-xl border border-red-100 p-3 text-red-600">
                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-8">{{ $plans->links() }}</div>
    </div>
</x-layouts.admin>
