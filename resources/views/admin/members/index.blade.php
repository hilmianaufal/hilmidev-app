<x-layouts.admin>
    <div class="mx-auto max-w-7xl px-4 py-10">
        <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.16em] text-blue-700">Bimbingan Coding</p>
                <h1 class="mt-2 text-3xl font-black text-slate-950">Manajemen Member</h1>
                <p class="mt-2 text-sm text-slate-500">Buat akun dan atur masa aktif peserta.</p>
            </div>

            <a href="{{ route('admin.members.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-blue-700 px-5 py-4 text-sm font-black text-white">
                <i data-lucide="user-plus" class="h-5 w-5"></i>
                Tambah Member
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 font-bold text-emerald-700">{{ session('success') }}</div>
        @endif

        <form method="GET" class="mb-6 flex gap-3">
            <input name="q" value="{{ $search }}" placeholder="Cari nama atau email..." class="h-14 flex-1 rounded-2xl border-slate-200">
            <button class="rounded-2xl bg-slate-950 px-6 text-white">
                <i data-lucide="search" class="h-5 w-5"></i>
            </button>
        </form>

        <div class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
                        <tr>
                            <th class="px-5 py-4">Member</th>
                            <th class="px-5 py-4">Paket</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4">Masa Aktif</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($members as $user)
                            @php($membership = $user->memberships->sortByDesc('id')->first())

                            <tr>
                                <td class="px-5 py-5">
                                    <p class="font-black text-slate-900">{{ $user->name }}</p>
                                    <p class="mt-1 text-xs text-slate-400">{{ $user->email }}</p>
                                </td>

                                <td class="px-5 py-5 font-bold text-slate-700">{{ $membership?->plan?->name ?? '-' }}</td>

                                <td class="px-5 py-5">
                                    <span class="rounded-full px-3 py-1.5 text-[10px] font-black uppercase {{ $membership?->isActive() ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $membership?->status ?? 'belum member' }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 text-xs font-bold text-slate-500">
                                    {{ $membership?->expires_at?->translatedFormat('d M Y') ?? ($membership ? 'Selamanya' : '-') }}
                                </td>

                                <td class="px-5 py-5">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.members.edit', $user) }}" class="rounded-xl border border-slate-200 p-3 text-blue-700">
                                            <i data-lucide="settings-2" class="h-4 w-4"></i>
                                        </a>

                                        @if ($membership)
                                            <form method="POST" action="{{ route('admin.members.destroy', $user) }}">
                                                @csrf
                                                @method('DELETE')

                                                <button class="rounded-xl border border-red-100 p-3 text-red-600">
                                                    <i data-lucide="user-x" class="h-4 w-4"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-16 text-center text-slate-500">
                                    Belum ada client/member.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8">{{ $members->links() }}</div>
    </div>
</x-layouts.admin>
