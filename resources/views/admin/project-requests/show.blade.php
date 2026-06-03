<x-layouts.admin>
    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-blue-600 via-cyan-500 to-blue-400 p-8 text-white shadow-2xl shadow-blue-500/20 mb-8">
            <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/20 rounded-full blur-3xl"></div>

            <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                <div>
                    <a href="{{ route('admin.project-requests.index') }}"
                       class="inline-flex items-center gap-2 bg-white/15 border border-white/20 px-4 py-2 rounded-full text-sm font-bold mb-4">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Kembali
                    </a>

                    <h1 class="text-3xl md:text-5xl font-black">
                        {{ $projectRequest->project_name }}
                    </h1>

                    <p class="text-blue-50 mt-3">
                        Detail request project dari client.
                    </p>
                </div>

                <div class="w-20 h-20 rounded-[2rem] bg-white/20 hidden md:flex items-center justify-center">
                    <i data-lucide="folder-kanban" class="w-10 h-10"></i>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 flex items-center gap-3 bg-green-50 text-green-700 px-5 py-4 rounded-2xl border border-green-100 font-semibold">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 md:p-8 shadow-xl shadow-blue-500/5">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center">
                            <i data-lucide="user" class="w-6 h-6"></i>
                        </div>

                        <div>
                            <h2 class="font-black text-slate-900 text-xl">Data Client</h2>
                            <p class="text-sm text-slate-500">Informasi pengirim request.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-blue-50 rounded-2xl p-4">
                            <p class="text-xs text-slate-500 font-bold mb-1">Nama Client</p>
                            <p class="font-black text-slate-900">
                                {{ $projectRequest->user->name ?? '-' }}
                            </p>
                        </div>

                        <div class="bg-blue-50 rounded-2xl p-4">
                            <p class="text-xs text-slate-500 font-bold mb-1">Email</p>
                            <p class="font-black text-slate-900">
                                {{ $projectRequest->user->email ?? '-' }}
                            </p>
                        </div>

                        <div class="bg-blue-50 rounded-2xl p-4">
                            <p class="text-xs text-slate-500 font-bold mb-1">WhatsApp</p>
                            <p class="font-black text-slate-900">
                                {{ $projectRequest->phone }}
                            </p>
                        </div>

                        <div class="bg-blue-50 rounded-2xl p-4">
                            <p class="text-xs text-slate-500 font-bold mb-1">Perusahaan</p>
                            <p class="font-black text-slate-900">
                                {{ $projectRequest->company_name ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 md:p-8 shadow-xl shadow-blue-500/5">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-cyan-500 text-white flex items-center justify-center">
                            <i data-lucide="clipboard-list" class="w-6 h-6"></i>
                        </div>

                        <div>
                            <h2 class="font-black text-slate-900 text-xl">Detail Project</h2>
                            <p class="text-sm text-slate-500">Kebutuhan project dari client.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-blue-50 rounded-2xl p-4">
                            <p class="text-xs text-slate-500 font-bold mb-1">Layanan</p>
                            <p class="font-black text-slate-900">
                                {{ $projectRequest->service->title ?? '-' }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-blue-50 rounded-2xl p-4">
                                <p class="text-xs text-slate-500 font-bold mb-1">Budget</p>
                                <p class="font-black text-blue-600">
                                    {{ $projectRequest->budget ?? '-' }}
                                </p>
                            </div>

                            <div class="bg-blue-50 rounded-2xl p-4">
                                <p class="text-xs text-slate-500 font-bold mb-1">Deadline</p>
                                <p class="font-black text-slate-900">
                                    {{ $projectRequest->deadline ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="bg-blue-50 rounded-2xl p-4">
                            <p class="text-xs text-slate-500 font-bold mb-2">Deskripsi Kebutuhan</p>
                            <p class="text-slate-700 leading-relaxed">
                                {!! nl2br(e($projectRequest->description)) !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-2xl shadow-blue-500/10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center">
                            <i data-lucide="settings" class="w-6 h-6"></i>
                        </div>

                        <div>
                            <h2 class="font-black text-slate-900">Update Status</h2>
                            <p class="text-sm text-slate-500">Atur status request.</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.project-requests.update-status', $projectRequest) }}"
                          method="POST"
                          class="space-y-5">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Status Project
                            </label>

                            <select name="status"
                                    class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500">
                                <option value="pending" @selected($projectRequest->status === 'pending')>Pending</option>
                                <option value="review" @selected($projectRequest->status === 'review')>Review</option>
                                <option value="quotation" @selected($projectRequest->status === 'quotation')>Quotation</option>
                                <option value="development" @selected($projectRequest->status === 'development')>Development</option>
                                <option value="completed" @selected($projectRequest->status === 'completed')>Completed</option>
                                <option value="cancelled" @selected($projectRequest->status === 'cancelled')>Cancelled</option>
                            </select>

                            @error('status')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-black px-6 py-4 rounded-2xl shadow-lg shadow-blue-500/30">
                            <i data-lucide="save" class="w-5 h-5"></i>
                            Simpan Status
                        </button>
                    </form>
                </div>

                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                    <h2 class="font-black text-slate-900 mb-5">Ringkasan</h2>

                    <div class="space-y-3">
                        <div class="flex justify-between bg-blue-50 rounded-2xl p-4">
                            <span class="text-slate-500 font-bold">Status</span>
                            <span class="font-black text-blue-600">
                                {{ strtoupper($projectRequest->status) }}
                            </span>
                        </div>

                        <div class="flex justify-between bg-blue-50 rounded-2xl p-4">
                            <span class="text-slate-500 font-bold">Tanggal</span>
                            <span class="font-black text-slate-900">
                                {{ $projectRequest->created_at->format('d M Y') }}
                            </span>
                        </div>
                    </div>

                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $projectRequest->phone) }}?text=Halo%20{{ urlencode($projectRequest->user->name ?? 'Client') }},%20kami%20dari%20HilmiDev%20ingin%20membahas%20request%20project%20{{ urlencode($projectRequest->project_name) }}"
                       target="_blank"
                       class="mt-5 w-full inline-flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white font-black px-6 py-4 rounded-2xl shadow-lg shadow-green-500/30">
                        <i data-lucide="message-circle" class="w-5 h-5"></i>
                        Hubungi Client
                    </a>
                </div>
            </aside>
        </div>
    </div>
</x-layouts.admin>