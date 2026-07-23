<x-layouts.admin>
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-blue-600 via-cyan-500 to-blue-400 p-8 text-white shadow-2xl shadow-blue-500/20 mb-8">
            <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/20 rounded-full blur-3xl"></div>

            <div class="relative">
                <div class="inline-flex items-center gap-2 bg-white/15 border border-white/20 px-4 py-2 rounded-full mb-4">
                    <i data-lucide="credit-card" class="w-4 h-4"></i>
                    <span class="text-sm font-bold">Payment Management</span>
                </div>

                <h1 class="text-3xl md:text-5xl font-black">
                    Pembayaran
                </h1>

                <p class="text-blue-50 mt-3">
                    Verifikasi bukti transfer dan kelola status pembayaran client.
                </p>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 flex items-center gap-3 bg-green-50 text-green-700 px-5 py-4 rounded-2xl border border-green-100 font-semibold">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-blue-100 rounded-[2rem] overflow-hidden shadow-xl shadow-blue-500/5">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-blue-50 text-blue-700">
                        <tr>
                            <th class="px-5 py-4 text-left">Invoice</th>
                            <th class="px-5 py-4 text-left">Client</th>
                            <th class="px-5 py-4 text-left">Metode</th>
                            <th class="px-5 py-4 text-left">Total</th>
                            <th class="px-5 py-4 text-left">Status</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-blue-50">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="px-5 py-5">
                                    <p class="font-black text-slate-900">
                                        {{ $order->invoice_number }}
                                    </p>

                                    <p class="text-xs text-slate-500 mt-1">
                                        {{ $order->created_at->format('d M Y H:i') }}
                                    </p>
                                </td>

                                <td class="px-5 py-5">
                                    <p class="font-bold text-slate-800">
                                        {{ $order->user?->name ?? '-' }}
                                    </p>

                                    <p class="text-xs text-slate-500">
                                        {{ $order->user?->email ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-5 py-5">
                                    <span class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 px-3 py-2 rounded-2xl font-bold text-xs">
                                        <i data-lucide="wallet" class="w-3 h-3"></i>
                                        {{ $order->payment_method ?? '-' }}
                                    </span>
                                </td>

                                <td class="px-5 py-5">
                                    <p class="font-black text-blue-600">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </p>
                                </td>

                                <td class="px-5 py-5">
                                    @if ($order->payment_status === 'paid')
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-bold">
                                            <i data-lucide="check-circle" class="w-3 h-3"></i>
                                            PAID
                                        </span>
                                    @elseif ($order->payment_status === 'failed')
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold">
                                            <i data-lucide="x-circle" class="w-3 h-3"></i>
                                            REJECTED
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-bold">
                                            <i data-lucide="clock" class="w-3 h-3"></i>
                                            {{ strtoupper($order->payment_status) }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-5 text-right">
                                    <a href="{{ route('admin.payments.show', $order) }}"
                                       class="inline-flex items-center justify-center gap-2 bg-blue-600 text-white px-4 py-3 rounded-2xl font-black shadow-lg shadow-blue-500/20">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">
                                    <div class="w-16 h-16 mx-auto rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                                        <i data-lucide="credit-card" class="w-8 h-8"></i>
                                    </div>

                                    <p class="font-black text-slate-900">Belum ada pembayaran.</p>
                                    <p class="text-slate-500 text-sm mt-1">
                                        Bukti pembayaran client akan tampil di sini.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
</x-layouts.admin>