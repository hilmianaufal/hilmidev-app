<x-layouts.admin>
    <div class="mx-auto max-w-7xl px-4 py-10">
        <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.16em] text-blue-700">
                    Bimbingan Coding
                </p>

                <h1 class="mt-2 text-3xl font-black text-slate-950">
                    Diskusi Member
                </h1>

                <p class="mt-2 text-sm font-medium text-slate-500">
                    Jawab pertanyaan member pada setiap materi pembelajaran.
                </p>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-bold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET"
              class="mb-6 grid gap-3 rounded-[1.5rem] border border-slate-200 bg-white p-4 shadow-sm sm:grid-cols-12">
            <input
                name="q"
                value="{{ $search }}"
                placeholder="Cari member, materi, kelas, atau pertanyaan..."
                class="h-13 rounded-xl border-slate-200 sm:col-span-8"
            >

            <select
                name="status"
                class="h-13 rounded-xl border-slate-200 sm:col-span-2"
            >
                <option value="">Semua status</option>
                <option value="unanswered" @selected($status === 'unanswered')>
                    Belum dijawab
                </option>
                <option value="answered" @selected($status === 'answered')>
                    Sudah dijawab
                </option>
            </select>

            <button
                class="rounded-xl bg-blue-700 px-5 py-3 text-xs font-black text-white sm:col-span-2"
            >
                Cari Diskusi
            </button>
        </form>

        <div class="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-sm">
                    <thead class="bg-slate-50 text-left text-[10px] font-black uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-4">Member</th>
                            <th class="px-5 py-4">Kelas dan Materi</th>
                            <th class="px-5 py-4">Pertanyaan</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($discussions as $discussion)
                            <tr class="transition hover:bg-blue-50/30">
                                <td class="px-5 py-5">
                                    <p class="font-black text-slate-900">
                                        {{ $discussion->user?->name ?? '-' }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $discussion->user?->email ?? '-' }}
                                    </p>

                                    <p class="mt-2 text-[10px] font-semibold text-slate-400">
                                        {{ $discussion->created_at->translatedFormat('d M Y H:i') }}
                                    </p>
                                </td>

                                <td class="px-5 py-5">
                                    <p class="font-black text-blue-700">
                                        {{ $discussion->lesson?->module?->course?->title ?? '-' }}
                                    </p>

                                    <p class="mt-1 max-w-xs text-xs font-semibold text-slate-500">
                                        {{ $discussion->lesson?->title ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-5 py-5">
                                    <p class="max-w-md line-clamp-3 text-sm font-medium leading-6 text-slate-600">
                                        {{ $discussion->message }}
                                    </p>

                                    <p class="mt-2 text-[10px] font-black text-blue-700">
                                        {{ $discussion->replies_count }} jawaban
                                    </p>
                                </td>

                                <td class="px-5 py-5">
                                    @if ($discussion->is_answered)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-2 text-[10px] font-black text-emerald-700">
                                            <i data-lucide="circle-check" class="h-3.5 w-3.5"></i>
                                            Terjawab
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-2 text-[10px] font-black text-amber-700">
                                            <i data-lucide="clock-3" class="h-3.5 w-3.5"></i>
                                            Menunggu
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-5 text-right">
                                    <a
                                        href="{{ route('admin.lesson-discussions.show', $discussion) }}"
                                        class="inline-flex items-center gap-2 rounded-xl bg-blue-700 px-4 py-3 text-xs font-black text-white"
                                    >
                                        <i data-lucide="message-square-reply" class="h-4 w-4"></i>
                                        Buka
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-blue-700">
                                        <i data-lucide="messages-square" class="h-8 w-8"></i>
                                    </div>

                                    <p class="mt-5 font-black text-slate-900">
                                        Belum ada diskusi member
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-7">
            {{ $discussions->links() }}
        </div>
    </div>
</x-layouts.admin>
