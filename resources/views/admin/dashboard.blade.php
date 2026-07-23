<x-layouts.admin>
    @php
        $statistics = [
            [
                'label' => 'Total Produk',
                'value' => number_format($totalProducts),
                'icon' => 'package',
                'class' => 'bg-blue-600',
            ],
            [
                'label' => 'Produk Aktif',
                'value' => number_format($activeProducts),
                'icon' => 'badge-check',
                'class' => 'bg-cyan-500',
            ],
            [
                'label' => 'Kategori',
                'value' => number_format($totalCategories),
                'icon' => 'grid-3x3',
                'class' => 'bg-indigo-500',
            ],
            [
                'label' => 'Client',
                'value' => number_format($totalClients),
                'icon' => 'users',
                'class' => 'bg-sky-500',
            ],
            [
                'label' => 'Total Order',
                'value' => number_format($totalOrders),
                'icon' => 'shopping-cart',
                'class' => 'bg-blue-700',
            ],
            [
                'label' => 'Order Paid',
                'value' => number_format($paidOrders),
                'icon' => 'circle-check',
                'class' => 'bg-emerald-500',
            ],
            [
                'label' => 'Belum Bayar',
                'value' => number_format($pendingOrders),
                'icon' => 'clock',
                'class' => 'bg-amber-500',
            ],
            [
                'label' => 'Revenue',
                'value' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),
                'icon' => 'wallet',
                'class' => 'bg-violet-500',
            ],
            [
                'label' => 'Total Jasa',
                'value' => number_format($totalServices),
                'icon' => 'briefcase-business',
                'class' => 'bg-blue-600',
            ],
            [
                'label' => 'Project Request',
                'value' => number_format($totalProjectRequests),
                'icon' => 'folder-kanban',
                'class' => 'bg-cyan-500',
            ],
            [
                'label' => 'Project Pending',
                'value' => number_format($pendingProjectRequests),
                'icon' => 'hourglass',
                'class' => 'bg-amber-500',
            ],
            [
                'label' => 'Development',
                'value' => number_format($developmentProjectRequests),
                'icon' => 'rocket',
                'class' => 'bg-indigo-500',
            ],
        ];
    @endphp

    <div class="mx-auto max-w-7xl px-4 py-10">
        <section class="relative mb-8 overflow-hidden rounded-[2rem] bg-gradient-to-br from-blue-700 via-blue-600 to-cyan-500 p-8 text-white shadow-2xl shadow-blue-500/20 md:p-10">
            <div class="absolute -right-20 -top-20 h-72 w-72 rounded-full bg-white/15 blur-3xl"></div>

            <div class="relative flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/15 px-4 py-2">
                        <i data-lucide="layout-dashboard" class="h-4 w-4"></i>
                        <span class="text-sm font-bold">Admin Control Center</span>
                    </div>

                    <h1 class="text-3xl font-black md:text-5xl">
                        Dashboard Admin
                    </h1>

                    <p class="mt-3 text-blue-50">
                        Ringkasan marketplace, layanan, pembayaran, dan project HilmiDev.
                    </p>
                </div>

                <a href="{{ route('admin.products.create') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-6 py-4 font-black text-blue-700 shadow-xl">
                    <i data-lucide="plus-circle" class="h-5 w-5"></i>
                    Tambah Produk
                </a>
            </div>
        </section>

        <section class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($statistics as $statistic)
                <article class="rounded-[1.75rem] border border-blue-100 bg-white p-6 shadow-xl shadow-blue-500/5">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl text-white {{ $statistic['class'] }}">
                        <i data-lucide="{{ $statistic['icon'] }}" class="h-6 w-6"></i>
                    </div>

                    <p class="mt-5 text-sm font-semibold text-slate-500">
                        {{ $statistic['label'] }}
                    </p>

                    <h2 class="mt-2 break-words text-3xl font-black text-slate-950">
                        {{ $statistic['value'] }}
                    </h2>
                </article>
            @endforeach
        </section>

        <div class="mt-8 grid gap-8 xl:grid-cols-2">
            <section class="overflow-hidden rounded-[2rem] border border-blue-100 bg-white shadow-xl shadow-blue-500/5">
                <header class="flex items-center justify-between border-b border-blue-50 p-6">
                    <h2 class="font-black text-slate-950">Order Terbaru</h2>

                    <a href="{{ route('admin.orders.index') }}"
                       class="text-sm font-black text-blue-700">
                        Lihat Semua
                    </a>
                </header>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-blue-50 text-left text-blue-700">
                            <tr>
                                <th class="px-5 py-4">Invoice</th>
                                <th class="px-5 py-4">Client</th>
                                <th class="px-5 py-4">Total</th>
                                <th class="px-5 py-4">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-blue-50">
                            @forelse ($latestOrders as $order)
                                <tr>
                                    <td class="px-5 py-4 font-bold text-slate-900">
                                        {{ $order->invoice_number }}
                                    </td>

                                    <td class="px-5 py-4">
                                        {{ $order->user?->name ?? 'User dihapus' }}
                                    </td>

                                    <td class="px-5 py-4 font-black text-blue-700">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                            {{ strtoupper($order->payment_status) }}
                                        </span>
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
            </section>

            <section class="overflow-hidden rounded-[2rem] border border-blue-100 bg-white shadow-xl shadow-blue-500/5">
                <header class="flex items-center justify-between border-b border-blue-50 p-6">
                    <h2 class="font-black text-slate-950">Produk Terbaru</h2>

                    <a href="{{ route('admin.products.index') }}"
                       class="text-sm font-black text-blue-700">
                        Lihat Semua
                    </a>
                </header>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-blue-50 text-left text-blue-700">
                            <tr>
                                <th class="px-5 py-4">Produk</th>
                                <th class="px-5 py-4">Kategori</th>
                                <th class="px-5 py-4">Harga</th>
                                <th class="px-5 py-4">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-blue-50">
                            @forelse ($latestProducts as $product)
                                <tr>
                                    <td class="px-5 py-4 font-bold text-slate-900">
                                        {{ $product->name }}
                                    </td>

                                    <td class="px-5 py-4">
                                        {{ $product->category?->name ?? '-' }}
                                    </td>

                                    <td class="px-5 py-4 font-black text-blue-700">
                                        Rp {{ number_format($product->final_price, 0, ',', '.') }}
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $product->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                            {{ $product->is_active ? 'AKTIF' : 'NONAKTIF' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-12 text-center text-slate-500">
                                        Belum ada produk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <section class="mt-8 overflow-hidden rounded-[2rem] border border-blue-100 bg-white shadow-xl shadow-blue-500/5">
            <header class="flex items-center justify-between border-b border-blue-50 p-6">
                <h2 class="font-black text-slate-950">Project Request Terbaru</h2>

                <a href="{{ route('admin.project-requests.index') }}"
                   class="text-sm font-black text-blue-700">
                    Lihat Semua
                </a>
            </header>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-blue-50 text-left text-blue-700">
                        <tr>
                            <th class="px-5 py-4">Project</th>
                            <th class="px-5 py-4">Client</th>
                            <th class="px-5 py-4">Layanan</th>
                            <th class="px-5 py-4">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-blue-50">
                        @forelse ($latestProjectRequests as $project)
                            <tr>
                                <td class="px-5 py-4 font-bold text-slate-900">
                                    {{ $project->project_name }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ $project->user?->name ?? 'User dihapus' }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ $project->service?->title ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                                        {{ strtoupper($project->status) }}
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
        </section>
    </div>
</x-layouts.admin>
