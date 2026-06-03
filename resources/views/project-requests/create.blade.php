<x-app-layout>
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-cyan-500 to-blue-400"></div>

        <div class="relative max-w-7xl mx-auto px-4 py-16 text-white">
            <a href="{{ route('services.show', $service) }}"
               class="inline-flex items-center gap-2 bg-white/15 border border-white/20 backdrop-blur-xl px-4 py-2 rounded-full text-sm font-bold mb-8">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali
            </a>

            <div class="max-w-4xl">
                <div class="inline-flex items-center gap-2 bg-white/15 border border-white/20 px-4 py-2 rounded-full mb-5">
                    <i data-lucide="folder-kanban" class="w-4 h-4"></i>
                    <span class="text-sm font-bold">Request Project</span>
                </div>

                <h1 class="text-4xl md:text-6xl font-black leading-tight">
                    Request {{ $service->title }}
                </h1>

                <p class="text-blue-50 text-lg mt-5">
                    Kirim kebutuhan project kamu dan tim HilmiDev akan memberikan estimasi harga serta timeline pengerjaan.
                </p>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 py-12">
        <form action="{{ route('project-requests.store', $service) }}"
              method="POST"
              class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf

            <div class="lg:col-span-2">
                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 md:p-8 shadow-xl shadow-blue-500/5">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center">
                            <i data-lucide="clipboard-list" class="w-6 h-6"></i>
                        </div>

                        <div>
                            <h2 class="font-black text-slate-900 text-xl">
                                Informasi Project
                            </h2>

                            <p class="text-sm text-slate-500">
                                Lengkapi detail kebutuhan project.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Nama Project
                            </label>

                            <input type="text"
                                   name="project_name"
                                   value="{{ old('project_name') }}"
                                   class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Contoh: Sistem ERP Perusahaan">

                            @error('project_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Nama Perusahaan
                            </label>

                            <input type="text"
                                   name="company_name"
                                   value="{{ old('company_name') }}"
                                   class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="PT Hilmi Teknologi">

                            @error('company_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                WhatsApp
                            </label>

                            <input type="text"
                                   name="phone"
                                   value="{{ old('phone') }}"
                                   class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="08xxxxxxxxxx">

                            @error('phone')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">
                                    Budget
                                </label>

                                <input type="text"
                                       name="budget"
                                       value="{{ old('budget') }}"
                                       class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Rp 5.000.000">

                                @error('budget')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">
                                    Deadline
                                </label>

                                <input type="text"
                                       name="deadline"
                                       value="{{ old('deadline') }}"
                                       class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="30 Hari">

                                @error('deadline')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Detail Kebutuhan
                            </label>

                            <textarea name="description"
                                      rows="8"
                                      class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="Jelaskan fitur, kebutuhan bisnis, referensi desain, dll...">{{ old('description') }}</textarea>

                            @error('description')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <aside>
                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-2xl shadow-blue-500/10 sticky top-28">
                    <div class="w-16 h-16 rounded-3xl bg-gradient-to-br from-blue-600 to-cyan-400 text-white flex items-center justify-center mb-5">
                        <i data-lucide="briefcase-business" class="w-8 h-8"></i>
                    </div>

                    <h3 class="text-2xl font-black text-slate-900">
                        {{ $service->title }}
                    </h3>

                    <p class="text-slate-500 text-sm mt-3">
                        {{ $service->short_description }}
                    </p>

                    <div class="bg-blue-50 rounded-2xl p-4 mt-6">
                        <p class="text-sm text-slate-500">Harga Mulai</p>

                        <p class="text-3xl font-black text-blue-600">
                            Rp {{ number_format($service->starting_price, 0, ',', '.') }}
                        </p>
                    </div>

                    <button class="w-full mt-6 inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-black px-6 py-4 rounded-2xl shadow-lg shadow-blue-500/30">
                        <i data-lucide="send" class="w-5 h-5"></i>
                        Kirim Request
                    </button>
                </div>
            </aside>
        </form>
    </section>
</x-app-layout>