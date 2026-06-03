<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 py-10">
        <div class="bg-gradient-to-br from-blue-600 via-cyan-500 to-blue-400 rounded-[2rem] p-8 text-white shadow-2xl shadow-blue-500/20 mb-8">
            <div class="flex items-center gap-3 mb-4">
                <i data-lucide="receipt" class="w-8 h-8"></i>
                <span class="font-bold">Invoice Pembelian</span>
            </div>

            <h1 class="text-3xl md:text-5xl font-black">
                {{ $order->invoice_number }}
            </h1>

            <p class="text-blue-50 mt-3">
                Detail transaksi pembelian source code HilmiDev.
            </p>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-100 text-green-700 px-5 py-4 rounded-2xl font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl shadow-blue-500/5">
                <h2 class="text-xl font-black text-slate-900 mb-5">
                    Produk Dibeli
                </h2>

                <div class="space-y-4">
                    @foreach ($order->items as $item)
                        <div class="flex items-center justify-between gap-4 bg-blue-50 rounded-2xl p-4">
                            <div>
                                <p class="font-black text-slate-900">
                                    {{ $item->product_name }}
                                </p>
                                <p class="text-sm text-slate-500">
                                    {{ $item->product->technology ?? 'Source Code Premium' }}
                                </p>
                            </div>

                            <p class="font-black text-blue-600">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            <aside class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-2xl shadow-blue-500/10 h-fit">
                <h2 class="text-xl font-black text-slate-900 mb-5">
                    Ringkasan
                </h2>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between bg-blue-50 rounded-2xl p-4">
                        <span class="text-slate-500">Status Order</span>
                        <span class="font-black text-slate-800">
                            {{ strtoupper($order->status) }}
                        </span>
                    </div>

                    <div class="flex justify-between bg-blue-50 rounded-2xl p-4">
                        <span class="text-slate-500">Pembayaran</span>
                        <span class="font-black {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-amber-600' }}">
                            {{ strtoupper($order->payment_status) }}
                        </span>
                    </div>

                    <div class="flex justify-between bg-blue-50 rounded-2xl p-4">
                        <span class="text-slate-500">Total</span>
                        <span class="font-black text-blue-600">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                @if ($order->payment_status === 'paid')
                    <div class="mt-6 space-y-3">
                        @foreach ($order->items as $item)
                            @if ($item->product && $item->product->file_path)
                                <a href="{{ route('orders.items.download', [$order, $item]) }}"
                                class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 text-white px-5 py-4 rounded-2xl font-black">
                                    <i data-lucide="download" class="w-5 h-5"></i>
                                    Download {{ $item->product_name }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                @else
                    <button class="mt-6 w-full inline-flex items-center justify-center gap-2 bg-blue-600 text-white px-5 py-4 rounded-2xl font-black">
                        <i data-lucide="credit-card" class="w-5 h-5"></i>
                        Bayar Sekarang
                    </button>
                        @if ($order->payment_status !== 'paid')
                            <div class="mt-6 bg-blue-50 border border-blue-100 rounded-2xl p-4">
                                <h3 class="font-black text-slate-900 mb-3">Upload Bukti Pembayaran</h3>

                                <form action="{{ route('orders.payment-proof.store', $order) }}"
                                    method="POST"
                                    enctype="multipart/form-data"
                                    class="space-y-4">
                                    @csrf

                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Metode Pembayaran</label>
                                        <select name="payment_method" class="w-full rounded-2xl border-blue-100">
                                            <option value="BCA">BCA</option>
                                            <option value="BRI">BRI</option>
                                            <option value="BNI">BNI</option>
                                            <option value="Mandiri">Mandiri</option>
                                            <option value="DANA">DANA</option>
                                            <option value="OVO">OVO</option>
                                            <option value="QRIS">QRIS</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Bukti Transfer</label>
                                        <input type="file"
                                            name="payment_proof"
                                            class="w-full rounded-2xl border border-blue-100 bg-white p-3 text-sm"
                                            required>
                                    </div>

                                    <button class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 text-white px-5 py-4 rounded-2xl font-black">
                                        <i data-lucide="upload" class="w-5 h-5"></i>
                                        Kirim Bukti Pembayaran
                                    </button>
                                </form>
                            </div>
                            <option value="review" @selected($order->payment_status === 'review')>Review</option>
                        @endif
                    <p class="text-xs text-slate-500 text-center mt-3">
                        Tombol pembayaran akan kita hubungkan ke payment gateway setelah ini.
                    </p>
                @endif
            </aside>
        </div>
    </div>
</x-app-layout>