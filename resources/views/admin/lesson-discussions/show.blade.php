<x-layouts.admin>
    <div class="mx-auto max-w-5xl px-4 py-10">
        <a
            href="{{ route('admin.lesson-discussions.index') }}"
            class="inline-flex items-center gap-2 text-sm font-black text-blue-700"
        >
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Kembali ke Diskusi
        </a>

        @if (session('success'))
            <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-bold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-7 grid items-start gap-7 lg:grid-cols-12">
            <main class="space-y-6 lg:col-span-8">
                <article class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-700 text-sm font-black text-white">
                            {{ strtoupper(substr($discussion->user?->name ?? 'M', 0, 1)) }}
                        </div>

                        <div class="min-w-0">
                            <h1 class="text-lg font-black text-slate-950">
                                {{ $discussion->user?->name ?? 'Member' }}
                            </h1>

                            <p class="mt-1 text-xs font-medium text-slate-400">
                                {{ $discussion->user?->email }}
                                ·
                                {{ $discussion->created_at->translatedFormat('d M Y H:i') }}
                            </p>
                        </div>
                    </div>

                    <p class="mt-6 whitespace-pre-line text-sm font-medium leading-8 text-slate-600">{{ $discussion->message }}</p>
                </article>

                <section class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <h2 class="text-xl font-black text-slate-950">
                        Riwayat Jawaban
                    </h2>

                    <div class="mt-6 space-y-4">
                        @forelse ($discussion->replies as $reply)
                            <article class="rounded-2xl border border-blue-100 bg-blue-50/50 p-5">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-700 text-xs font-black text-white">
                                        {{ strtoupper(substr($reply->user?->name ?? 'A', 0, 1)) }}
                                    </div>

                                    <div>
                                        <p class="text-sm font-black text-slate-900">
                                            {{ $reply->user?->name ?? 'Admin' }}
                                        </p>

                                        <p class="mt-1 text-[10px] font-semibold text-slate-400">
                                            {{ $reply->created_at->translatedFormat('d M Y H:i') }}
                                        </p>
                                    </div>
                                </div>

                                <p class="mt-4 whitespace-pre-line text-sm font-medium leading-7 text-slate-600">{{ $reply->message }}</p>
                            </article>
                        @empty
                            <p class="rounded-2xl border border-dashed border-slate-300 px-5 py-10 text-center text-sm font-medium text-slate-500">
                                Pertanyaan ini belum memiliki jawaban.
                            </p>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <h2 class="text-xl font-black text-slate-950">
                        Kirim Jawaban Admin
                    </h2>

                    <form
                        method="POST"
                        action="{{ route('admin.lesson-discussions.reply', $discussion) }}"
                        class="mt-6"
                    >
                        @csrf

                        <textarea
                            name="message"
                            rows="7"
                            required
                            maxlength="5000"
                            placeholder="Tulis jawaban atau solusi untuk member..."
                            class="w-full rounded-2xl border-slate-200 bg-slate-50 text-sm font-medium leading-7 focus:border-blue-500 focus:bg-white focus:ring-blue-100"
                        >{{ old('message') }}</textarea>

                        @error('message')
                            <p class="mt-2 text-xs font-semibold text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                        <button
                            class="mt-4 inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-700 px-6 py-4 text-sm font-black text-white shadow-lg shadow-blue-700/20"
                        >
                            <i data-lucide="send" class="h-5 w-5"></i>
                            Kirim Jawaban
                        </button>
                    </form>
                </section>
            </main>

            <aside class="space-y-5 lg:col-span-4">
                <div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-[0.15em] text-blue-700">
                        Informasi Materi
                    </p>

                    <h2 class="mt-3 text-lg font-black leading-7 text-slate-950">
                        {{ $discussion->lesson?->title ?? '-' }}
                    </h2>

                    <p class="mt-3 text-xs font-semibold leading-6 text-slate-500">
                        {{ $discussion->lesson?->module?->course?->title ?? '-' }}
                    </p>

                    <div class="mt-6 border-t border-slate-100 pt-5">
                        @if ($discussion->is_answered)
                            <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-4 py-2 text-xs font-black text-emerald-700">
                                <i data-lucide="circle-check" class="h-4 w-4"></i>
                                Sudah terjawab
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 rounded-full bg-amber-50 px-4 py-2 text-xs font-black text-amber-700">
                                <i data-lucide="clock-3" class="h-4 w-4"></i>
                                Menunggu jawaban
                            </span>
                        @endif
                    </div>
                </div>

                <form
                    method="POST"
                    action="{{ route('admin.lesson-discussions.status', $discussion) }}"
                    class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm"
                >
                    @csrf
                    @method('PATCH')

                    <input
                        type="hidden"
                        name="is_answered"
                        value="{{ $discussion->is_answered ? 0 : 1 }}"
                    >

                    <button
                        class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4 text-xs font-black text-blue-700"
                    >
                        <i data-lucide="refresh-cw" class="h-4 w-4"></i>

                        {{ $discussion->is_answered
                            ? 'Tandai Belum Dijawab'
                            : 'Tandai Sudah Dijawab' }}
                    </button>
                </form>

                <form
                    method="POST"
                    action="{{ route('admin.lesson-discussions.destroy', $discussion) }}"
                    onsubmit="return confirm('Hapus diskusi beserta seluruh jawabannya?')"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-xs font-black text-red-600"
                    >
                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                        Hapus Diskusi
                    </button>
                </form>
            </aside>
        </div>
    </div>
</x-layouts.admin>
