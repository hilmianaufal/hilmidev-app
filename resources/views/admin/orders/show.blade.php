<x-layouts.admin>
    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-blue-600 via-cyan-500 to-blue-400 p-8 text-white shadow-2xl shadow-blue-500/20 mb-8">
            <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/20 rounded-full blur-3xl"></div>

            <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                <div>
                    <a href="{{ route('admin.orders.index') }}"
                       class="inline-flex items-center gap-2 bg-white/15 border border-white/20 px-4 py-2 rounded-full text-sm font-bold mb-4">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Kembali
                    </a>

                    <h1 class="text-3xl md:text-5xl font-black">
                        {{ $order->invoice_number }}
                    </h1>

                    <p class="text-blue-50 mt-3">
                        Detail invoice dan pengaturan status order.
                    </p>
                </div>

                <div class="w-20 h-20 rounded-[2rem] bg-white/20 hidden md:flex items-center justify-center">
                    <i data-lucide="receipt-text" class="w-10 h-10"></i>
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
                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center">
                            <i data-lucide="user" class="w-6 h-6"></i>
                        </div>

                        <div>
                            <h2 class="font-black text-slate-900">Data Client</h2>
                            <p class="text-sm text-slate-500">Informasi pembeli.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-blue-50 rounded-2xl p-4">
                            <p class="text-xs text-slate-500 font-bold mb-1">Nama</p>
                            <p class="font-black text-slate-900">{{ $order->user->name ?? '-' }}</p>
                        </div>

                        <div class="bg-blue-50 rounded-2xl p-4">
                            <p class="text-xs text-slate-500 font-bold mb-1">Email</p>
                            <p class="font-black text-slate-900">{{ $order->user->email ?? '-' }}</p>
                        </div>

                        <div class="bg-blue-50 rounded-2xl p-4">
                            <p class="text-xs text-slate-500 font-bold mb-1">Tanggal Order</p>
                            <p class="font-black text-slate-900">{{ $order->created_at->format('d M Y H:i') }}</p>
                        </div>

                        <div class="bg-blue-50 rounded-2xl p-4">
                            <p class="text-xs text-slate-500 font-bold mb-1">Invoice</p>
                            <p class="font-black text-blue-600">{{ $order->invoice_number }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-cyan-500 text-white flex items-center justify-center">
                            <i data-lucide="package" class="w-6 h-6"></i>
                        </div>

                        <div>
                            <h2 class="font-black text-slate-900">Produk Dibeli</h2>
                            <p class="text-sm text-slate-500">Item pada invoice ini.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach ($order->items as $item)
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-blue-50 rounded-2xl p-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-12 rounded-2xl bg-white border border-blue-100 overflow-hidden">
                                        @if ($item->product && $item->product->thumbnail)
                                            <img src="{{ asset('storage/' . $item->product->thumbnail) }}"
                                                 class="w-full h-full object-cover"
                                                 alt="{{ $item->product_name }}">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-blue-400">
                                                <i data-lucide="image" class="w-5 h-5"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <div>
                                        <p class="font-black text-slate-900">{{ $item->product_name }}</p>
                                        <p class="text-sm text-slate-500">
                                            {{ $item->product->technology ?? 'Source Code Premium' }}
                                        </p>
                                    </div>
                                </div>

                                <p class="font-black text-blue-600">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </p>
                            </div>
                        @endforeach
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
                            <p class="text-sm text-slate-500">Atur order dan pembayaran.</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Status Order</label>
                            <select name="status"
                                    class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500">
                                <option value="pending" @selected($order->status === 'pending')>Pending</option>
                                <option value="processing" @selected($order->status === 'processing')>Processing</option>
                                <option value="completed" @selected($order->status === 'completed')>Completed</option>
                                <option value="cancelled" @selected($order->status === 'cancelled')>Cancelled</option>
                            </select>
                            @error('status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Status Pembayaran</label>
                            <select name="payment_status"
                                    class="w-full rounded-2xl border-blue-100 focus:border-blue-500 focus:ring-blue-500">
                                <option value="unpaid" @selected($order->payment_status === 'unpaid')>Unpaid</option>
                                <option value="review" @selected($order->payment_status === 'review')>Review</option>
                                <option value="paid" @selected($order->payment_status === 'paid')>Paid</option>
                                <option value="failed" @selected($order->payment_status === 'failed')>Failed</option>
                                <option value="refunded" @selected($order->payment_status === 'refunded')>Refunded</option>
                            </select>
                            @error('payment_status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-black px-6 py-4 rounded-2xl shadow-lg shadow-blue-500/30">
                            <i data-lucide="save" class="w-5 h-5"></i>
                            Simpan Status
                        </button>
                    </form>
                </div>
                    @if ($order->payment_proof)
                        <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-12 h-12 rounded-2xl bg-cyan-500 text-white flex items-center justify-center">
                                    <i data-lucide="image" class="w-6 h-6"></i>
                                </div>

                                <div>
                                    <h2 class="font-black text-slate-900">Bukti Pembayaran</h2>
                                    <p class="text-sm text-slate-500">
                                        {{ $order->payment_method ?? 'Manual Transfer' }}
                                    </p>
                                </div>
                            </div>

                            <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank">
                                <img src="{{ asset('storage/' . $order->payment_proof) }}"
                                    class="w-full rounded-2xl border border-blue-100 shadow-lg shadow-blue-500/10"
                                    alt="Bukti Pembayaran">
                            </a>

                            <a href="{{ asset('storage/' . $order->payment_proof) }}"
                            target="_blank"
                            class="mt-4 w-full inline-flex items-center justify-center gap-2 bg-blue-50 text-blue-700 font-black px-5 py-3 rounded-2xl">
                                <i data-lucide="external-link" class="w-4 h-4"></i>
                                Lihat Full
                            </a>
                        </div>
                    @endif
                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                    <h2 class="font-black text-slate-900 mb-5">Ringkasan</h2>

                    <div class="space-y-3">
                        <div class="flex justify-between bg-blue-50 rounded-2xl p-4">
                            <span class="text-slate-500 font-bold">Order</span>
                            <span class="font-black text-slate-900">{{ strtoupper($order->status) }}</span>
                        </div>

                        <div class="flex justify-between bg-blue-50 rounded-2xl p-4">
                            <span class="text-slate-500 font-bold">Payment</span>
                            <span class="font-black {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-amber-600' }}">
                                {{ strtoupper($order->payment_status) }}
                            </span>
                        </div>

                        <div class="flex justify-between bg-blue-50 rounded-2xl p-4">
                            <span class="text-slate-500 font-bold">Total</span>
                            <span class="font-black text-blue-600">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-layouts.admin>