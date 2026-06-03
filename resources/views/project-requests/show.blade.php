<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-blue-600 via-cyan-500 to-blue-400 p-8 text-white shadow-2xl shadow-blue-500/20 mb-8">
            <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/20 rounded-full blur-3xl"></div>

            <div class="relative">
                <a href="{{ route('project-requests.index') }}"
                   class="inline-flex items-center gap-2 bg-white/15 border border-white/20 px-4 py-2 rounded-full text-sm font-bold mb-4">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Kembali
                </a>

                <h1 class="text-3xl md:text-5xl font-black">
                    {{ $projectRequest->project_name }}
                </h1>

                <p class="text-blue-50 mt-3">
                    Detail request project kamu.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white border border-blue-100 rounded-[2rem] p-6 md:p-8 shadow-xl shadow-blue-500/5">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center">
                        <i data-lucide="clipboard-list" class="w-6 h-6"></i>
                    </div>

                    <div>
                        <h2 class="font-black text-slate-900 text-xl">Detail Kebutuhan</h2>
                        <p class="text-sm text-slate-500">Informasi project yang dikirim.</p>
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
                            <p class="text-xs text-slate-500 font-bold mb-1">Perusahaan</p>
                            <p class="font-black text-slate-900">
                                {{ $projectRequest->company_name ?? '-' }}
                            </p>
                        </div>

                        <div class="bg-blue-50 rounded-2xl p-4">
                            <p class="text-xs text-slate-500 font-bold mb-1">WhatsApp</p>
                            <p class="font-black text-slate-900">
                                {{ $projectRequest->phone }}
                            </p>
                        </div>

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
                        <p class="text-xs text-slate-500 font-bold mb-2">Deskripsi</p>
                        <p class="text-slate-700 leading-relaxed">
                            {!! nl2br(e($projectRequest->description)) !!}
                        </p>
                    </div>
                </div>
            </div>

            <aside class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-2xl shadow-blue-500/10 h-fit">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-400 text-white flex items-center justify-center mb-5">
                    <i data-lucide="activity" class="w-7 h-7"></i>
                </div>

                <h3 class="text-2xl font-black text-slate-900 mb-2">
                    Status Project
                </h3>

                <p class="text-slate-500 text-sm mb-6">
                    Admin HilmiDev akan menghubungi kamu melalui WhatsApp.
                </p>

                <div class="bg-blue-50 rounded-2xl p-4 mb-4">
                    <p class="text-sm text-slate-500">Status</p>
                    <p class="text-2xl font-black text-blue-600">
                        {{ strtoupper($projectRequest->status) }}
                    </p>
                </div>

                <a href="https://wa.me/6281234567890?text=Halo%20HilmiDev,%20saya%20ingin%20menanyakan%20request%20project%20{{ urlencode($projectRequest->project_name) }}"
                   target="_blank"
                   class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 text-white px-6 py-4 rounded-2xl font-black shadow-lg shadow-blue-500/30">
                    <i data-lucide="message-circle" class="w-5 h-5"></i>
                    Chat Admin
                </a>
            </aside>
        </div>
    </div>
</x-app-layout>