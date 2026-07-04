<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-blue-700 via-blue-500 to-cyan-400 p-8 md:p-10 text-white shadow-2xl shadow-blue-500/20 mb-8">
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/20 rounded-full blur-3xl"></div>

            <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 bg-white/15 border border-white/20 backdrop-blur-xl px-4 py-2 rounded-full mb-5">
                        <i data-lucide="sparkles" class="w-4 h-4"></i>
                        <span class="text-sm font-black">Client Control Center</span>
                    </div>

                    <h1 class="text-4xl md:text-6xl font-black tracking-tight">
                        Halo, {{ auth()->user()->name }}
                    </h1>

                    <p class="text-blue-50 mt-4 max-w-2xl">
                        Pantau order source code, invoice, download file, dan progress request project kamu dalam satu dashboard.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('products.index') }}"
                       class="inline-flex items-center justify-center gap-2 bg-white text-blue-700 px-6 py-4 rounded-2xl font-black shadow-xl shadow-blue-900/20">
                        <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                        Beli Source Code
                    </a>

                    <a href="{{ route('services.index') }}"
                       class="inline-flex items-center justify-center gap-2 bg-blue-900/20 border border-white/30 backdrop-blur-xl px-6 py-4 rounded-2xl font-black">
                        <i data-lucide="briefcase" class="w-5 h-5"></i>
                        Request Project
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center mb-5">
                    <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                </div>
                <p class="text-sm text-slate-500 font-bold">Total Order</p>
                <h2 class="text-4xl font-black text-slate-900 mt-2">{{ $totalOrders }}</h2>
            </div>

            <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                <div class="w-12 h-12 rounded-2xl bg-green-500 text-white flex items-center justify-center mb-5">
                    <i data-lucide="badge-check" class="w-6 h-6"></i>
                </div>
                <p class="text-sm text-slate-500 font-bold">Order Paid</p>
                <h2 class="text-4xl font-black text-slate-900 mt-2">{{ $paidOrders }}</h2>
            </div>

            <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                <div class="w-12 h-12 rounded-2xl bg-cyan-500 text-white flex items-center justify-center mb-5">
                    <i data-lucide="file-text" class="w-6 h-6"></i>
                </div>
                <p class="text-sm text-slate-500 font-bold">Invoice</p>
                <h2 class="text-4xl font-black text-slate-900 mt-2">{{ $totalInvoices }}</h2>
            </div>

            <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                <div class="w-12 h-12 rounded-2xl bg-indigo-500 text-white flex items-center justify-center mb-5">
                    <i data-lucide="folder-kanban" class="w-6 h-6"></i>
                </div>
                <p class="text-sm text-slate-500 font-bold">Project Aktif</p>
                <h2 class="text-4xl font-black text-slate-900 mt-2">{{ $activeProjects }}</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white border border-blue-100 rounded-[2rem] overflow-hidden shadow-xl shadow-blue-500/5">
                    <div class="p-6 border-b border-blue-50 flex items-center justify-between">
                        <div>
                            <h2 class="font-black text-xl text-slate-900">Order Terbaru</h2>
                            <p class="text-sm text-slate-500">Invoice dan download source code.</p>
                        </div>

                        <a href="{{ route('orders.index') }}" class="text-sm text-blue-600 font-black">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="divide-y divide-blue-50">
                        @forelse ($orders as $order)
                            <div class="p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4 hover:bg-blue-50/40">
                                <div>
                                    <p class="font-black text-slate-900">
                                        {{ $order->invoice_number }}
                                    </p>

                                    <p class="text-sm text-slate-500 mt-1">
                                        {{ $order->created_at->format('d M Y H:i') }}
                                    </p>

                                    <div class="flex flex-wrap gap-2 mt-3">
                                        <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold">
                                            {{ strtoupper($order->status) }}
                                        </span>

                                        <span class="px-3 py-1 rounded-full {{ $order->payment_status === 'paid' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }} text-xs font-bold">
                                            {{ strtoupper($order->payment_status) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-2">
                                    <a href="{{ route('orders.show', $order) }}"
                                       class="inline-flex items-center justify-center gap-2 bg-blue-600 text-white px-4 py-3 rounded-2xl font-black">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                        Detail
                                    </a>

                                    <a href="{{ route('orders.invoice-pdf', $order) }}"
                                       class="inline-flex items-center justify-center gap-2 bg-blue-50 text-blue-700 px-4 py-3 rounded-2xl font-black">
                                        <i data-lucide="file-down" class="w-4 h-4"></i>
                                        Invoice
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-10 text-center">
                                <div class="w-16 h-16 mx-auto rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                                    <i data-lucide="shopping-bag" class="w-8 h-8"></i>
                                </div>
                                <p class="font-black text-slate-900">Belum ada order.</p>
                                <a href="{{ route('products.index') }}" class="mt-4 inline-flex bg-blue-600 text-white px-5 py-3 rounded-2xl font-black">
                                    Lihat Source Code
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white border border-blue-100 rounded-[2rem] overflow-hidden shadow-xl shadow-blue-500/5">
                    <div class="p-6 border-b border-blue-50 flex items-center justify-between">
                        <div>
                            <h2 class="font-black text-xl text-slate-900">Project Request</h2>
                            <p class="text-sm text-slate-500">Progress jasa website dan aplikasi.</p>
                        </div>

                        <a href="{{ route('project-requests.index') }}" class="text-sm text-blue-600 font-black">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="divide-y divide-blue-50">
                        @forelse ($projectRequests as $request)
                            @php
                                $progress = match($request->status) {
                                    'pending' => 15,
                                    'review' => 30,
                                    'quotation' => 45,
                                    'development' => 70,
                                    'completed' => 100,
                                    'cancelled' => 0,
                                    default => 10,
                                };
                            @endphp

                            <div class="p-5 hover:bg-blue-50/40">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div>
                                        <p class="font-black text-slate-900">
                                            {{ $request->project_name }}
                                        </p>

                                        <p class="text-sm text-slate-500 mt-1">
                                            {{ $request->service->title ?? 'Custom Project' }}
                                        </p>
                                    </div>

                                    <a href="{{ route('project-requests.show', $request) }}"
                                       class="inline-flex items-center justify-center gap-2 bg-blue-600 text-white px-4 py-3 rounded-2xl font-black">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                        Detail
                                    </a>
                                </div>

                                <div class="mt-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-black text-blue-600">
                                            {{ strtoupper($request->status) }}
                                        </span>
                                        <span class="text-xs font-black text-slate-500">
                                            {{ $progress }}%
                                        </span>
                                    </div>

                                    <div class="w-full h-3 bg-blue-50 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-blue-600 to-cyan-400 rounded-full"
                                             style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-10 text-center">
                                <div class="w-16 h-16 mx-auto rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                                    <i data-lucide="folder-kanban" class="w-8 h-8"></i>
                                </div>
                                <p class="font-black text-slate-900">Belum ada project request.</p>
                                <a href="{{ route('services.index') }}" class="mt-4 inline-flex bg-blue-600 text-white px-5 py-3 rounded-2xl font-black">
                                    Request Project
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="bg-slate-950 text-white rounded-[2rem] p-6 shadow-2xl shadow-blue-500/20">
                    <div class="w-14 h-14 rounded-2xl bg-blue-600 flex items-center justify-center mb-5">
                        <i data-lucide="rocket" class="w-7 h-7"></i>
                    </div>

                    <h3 class="text-2xl font-black">
                        Upgrade Bisnis Kamu
                    </h3>

                    <p class="text-slate-400 text-sm mt-3">
                        Butuh website custom, dashboard, atau aplikasi bisnis? Kirim request project sekarang.
                    </p>

                    <a href="{{ route('services.index') }}"
                       class="mt-6 w-full inline-flex items-center justify-center gap-2 bg-white text-blue-700 px-5 py-4 rounded-2xl font-black">
                        <i data-lucide="send" class="w-5 h-5"></i>
                        Mulai Project
                    </a>
                </div>

                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                    <h3 class="font-black text-xl text-slate-900 mb-5">
                        Shortcut
                    </h3>

                    <div class="space-y-3">
                        <a href="{{ route('products.index') }}"
                           class="flex items-center gap-3 bg-blue-50 p-4 rounded-2xl font-bold text-slate-700 hover:text-blue-600">
                            <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                            Marketplace Source Code
                        </a>

                        <a href="{{ route('services.index') }}"
                           class="flex items-center gap-3 bg-blue-50 p-4 rounded-2xl font-bold text-slate-700 hover:text-blue-600">
                            <i data-lucide="briefcase" class="w-5 h-5"></i>
                            Jasa Website
                        </a>

                        <a href="{{ route('orders.index') }}"
                           class="flex items-center gap-3 bg-blue-50 p-4 rounded-2xl font-bold text-slate-700 hover:text-blue-600">
                            <i data-lucide="receipt-text" class="w-5 h-5"></i>
                            Invoice & Order
                        </a>

                        <a href="{{ route('project-requests.index') }}"
                           class="flex items-center gap-3 bg-blue-50 p-4 rounded-2xl font-bold text-slate-700 hover:text-blue-600">
                            <i data-lucide="folder-kanban" class="w-5 h-5"></i>
                            Project Saya
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>