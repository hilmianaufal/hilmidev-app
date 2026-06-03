<x-layouts.admin>
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-blue-600 via-cyan-500 to-blue-400 p-8 md:p-10 text-white shadow-2xl shadow-blue-500/20 mb-8">
            <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/20 rounded-full blur-3xl"></div>

            <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 bg-white/15 border border-white/20 px-4 py-2 rounded-full mb-4">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        <span class="text-sm font-bold">Admin Control Center</span>
                    </div>

                    <h1 class="text-3xl md:text-5xl font-black">Dashboard Admin</h1>

                    <p class="text-blue-50 mt-3">
                        Kelola produk, kategori, order, client, dan layanan HilmiDev.
                    </p>
                </div>

                <a href="{{ route('admin.products.create') }}"
                   class="inline-flex items-center justify-center gap-2 bg-white text-blue-700 px-6 py-4 rounded-2xl font-black shadow-xl shadow-blue-900/20">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    Tambah Produk
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center mb-5">
                    <i data-lucide="package" class="w-6 h-6"></i>
                </div>
                <p class="text-sm text-slate-500 font-semibold">Total Produk</p>
                <h2 class="text-4xl font-black text-slate-900 mt-2">{{ $totalProducts }}</h2>
            </div>

            <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                <div class="w-12 h-12 rounded-2xl bg-cyan-500 text-white flex items-center justify-center mb-5">
                    <i data-lucide="badge-check" class="w-6 h-6"></i>
                </div>
                <p class="text-sm text-slate-500 font-semibold">Produk Aktif</p>
                <h2 class="text-4xl font-black text-slate-900 mt-2">{{ $activeProducts }}</h2>
            </div>

            <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                <div class="w-12 h-12 rounded-2xl bg-indigo-500 text-white flex items-center justify-center mb-5">
                    <i data-lucide="grid-3x3" class="w-6 h-6"></i>
                </div>
                <p class="text-sm text-slate-500 font-semibold">Kategori</p>
                <h2 class="text-4xl font-black text-slate-900 mt-2">{{ $totalCategories }}</h2>
            </div>

            <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                <div class="w-12 h-12 rounded-2xl bg-sky-500 text-white flex items-center justify-center mb-5">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <p class="text-sm text-slate-500 font-semibold">Client</p>
                <h2 class="text-4xl font-black text-slate-900 mt-2">{{ $totalClients }}</h2>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center mb-5">
                    <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                </div>
                <p class="text-sm text-slate-500 font-semibold">Total Order</p>
                <h2 class="text-4xl font-black text-slate-900 mt-2">{{ $totalOrders }}</h2>
            </div>

            <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                <div class="w-12 h-12 rounded-2xl bg-green-500 text-white flex items-center justify-center mb-5">
                    <i data-lucide="badge-check" class="w-6 h-6"></i>
                </div>
                <p class="text-sm text-slate-500 font-semibold">Order Paid</p>
                <h2 class="text-4xl font-black text-slate-900 mt-2">{{ $paidOrders }}</h2>
            </div>

            <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                <div class="w-12 h-12 rounded-2xl bg-amber-500 text-white flex items-center justify-center mb-5">
                    <i data-lucide="clock" class="w-6 h-6"></i>
                </div>
                <p class="text-sm text-slate-500 font-semibold">Belum Bayar</p>
                <h2 class="text-4xl font-black text-slate-900 mt-2">{{ $pendingOrders }}</h2>
            </div>

            <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                <div class="w-12 h-12 rounded-2xl bg-cyan-500 text-white flex items-center justify-center mb-5">
                    <i data-lucide="wallet" class="w-6 h-6"></i>
                </div>
                <p class="text-sm text-slate-500 font-semibold">Revenue</p>
                <h2 class="text-2xl font-black text-blue-600 mt-3">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </h2>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
        <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center mb-5">
            <i data-lucide="briefcase" class="w-6 h-6"></i>
        </div>
        <p class="text-sm text-slate-500 font-semibold">Total Jasa</p>
        <h2 class="text-4xl font-black text-slate-900 mt-2">{{ $totalServices }}</h2>
    </div>

    <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
        <div class="w-12 h-12 rounded-2xl bg-cyan-500 text-white flex items-center justify-center mb-5">
            <i data-lucide="folder-kanban" class="w-6 h-6"></i>
        </div>
        <p class="text-sm text-slate-500 font-semibold">Project Request</p>
        <h2 class="text-4xl font-black text-slate-900 mt-2">{{ $totalProjectRequests }}</h2>
    </div>

    <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
        <div class="w-12 h-12 rounded-2xl bg-amber-500 text-white flex items-center justify-center mb-5">
            <i data-lucide="clock" class="w-6 h-6"></i>
        </div>
        <p class="text-sm text-slate-500 font-semibold">Project Pending</p>
        <h2 class="text-4xl font-black text-slate-900 mt-2">{{ $pendingProjectRequests }}</h2>
    </div>

    <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
        <div class="w-12 h-12 rounded-2xl bg-indigo-500 text-white flex items-center justify-center mb-5">
            <i data-lucide="rocket" class="w-6 h-6"></i>
        </div>
        <p class="text-sm text-slate-500 font-semibold">Development</p>
        <h2 class="text-4xl font-black text-slate-900 mt-2">{{ $developmentProjectRequests }}</h2>
    </div>
</div>
        <div class="bg-white border border-blue-100 rounded-[2rem] overflow-hidden shadow-xl shadow-blue-500/5">
            <div class="bg-white border border-blue-100 rounded-[2rem] overflow-hidden shadow-xl shadow-blue-500/5 mt-8">
    <div class="p-6 border-b border-blue-50 flex items-center justify-between">
        <h2 class="font-black text-slate-900">Order Terbaru</h2>

        <a href="{{ route('admin.orders.index') }}" class="text-sm text-blue-600 font-black">
            Lihat Semua
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-blue-50 text-blue-700">
                <tr>
                    <th class="px-5 py-4 text-left">Invoice</th>
                    <th class="px-5 py-4 text-left">Client</th>
                    <th class="px-5 py-4 text-left">Total</th>
                    <th class="px-5 py-4 text-left">Payment</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-blue-50">
                @forelse ($latestOrders as $order)
                    <tr>
                        <td class="px-5 py-4 font-bold text-slate-900">
                            {{ $order->invoice_number }}
                        </td>

                        <td class="px-5 py-4">
                            {{ $order->user->name ?? '-' }}
                        </td>

                        <td class="px-5 py-4 font-black text-blue-600">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </td>

                        <td class="px-5 py-4">
                            @if ($order->payment_status === 'paid')
                                <span class="px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-bold">
                                    PAID
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-bold">
                                    {{ strtoupper($order->payment_status) }}
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-12 text-center text-slate-500">
                            Belum ada order.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
            <div class="p-6 border-b border-blue-50 flex items-center justify-between">
                <h2 class="font-black text-slate-900">Produk Terbaru</h2>
                <a href="{{ route('admin.products.index') }}" class="text-sm text-blue-600 font-black">Lihat Semua</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-blue-50 text-blue-700">
                        <tr>
                            <th class="px-5 py-4 text-left">Produk</th>
                            <th class="px-5 py-4 text-left">Kategori</th>
                            <th class="px-5 py-4 text-left">Harga</th>
                            <th class="px-5 py-4 text-left">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-blue-50">
                        @forelse ($latestProducts as $product)
                            <tr>
                                <td class="px-5 py-4 font-bold">{{ $product->name }}</td>
                                <td class="px-5 py-4">{{ $product->category->name ?? '-' }}</td>
                                <td class="px-5 py-4 font-black text-blue-600">
                                    Rp {{ number_format($product->final_price, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4">
                                    @if ($product->is_active)
                                        <span class="px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-bold">Aktif</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold">Nonaktif</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-12 text-center text-slate-500">Belum ada produk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    
                </table>
                <div class="bg-white border border-blue-100 rounded-[2rem] overflow-hidden shadow-xl shadow-blue-500/5 mt-8">
    <div class="p-6 border-b border-blue-50 flex items-center justify-between">
        <h2 class="font-black text-slate-900">Project Request Terbaru</h2>

        <a href="{{ route('admin.project-requests.index') }}" class="text-sm text-blue-600 font-black">
            Lihat Semua
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-blue-50 text-blue-700">
                <tr>
                    <th class="px-5 py-4 text-left">Project</th>
                    <th class="px-5 py-4 text-left">Client</th>
                    <th class="px-5 py-4 text-left">Layanan</th>
                    <th class="px-5 py-4 text-left">Status</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-blue-50">
                @forelse ($latestProjectRequests as $request)
                    <tr>
                        <td class="px-5 py-4 font-bold text-slate-900">
                            {{ $request->project_name }}
                        </td>

                        <td class="px-5 py-4">
                            {{ $request->user->name ?? '-' }}
                        </td>

                        <td class="px-5 py-4">
                            {{ $request->service->title ?? '-' }}
                        </td>

                        <td class="px-5 py-4">
                            <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold">
                                {{ strtoupper($request->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-12 text-center text-slate-500">
                            Belum ada project request.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
            </div>
        </div>
    </div>
</x-layouts.admin>