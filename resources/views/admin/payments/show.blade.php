<x-layouts.admin>
    <div class="max-w-7xl mx-auto px-4 py-10">

        <div class="flex items-center justify-between mb-8">
            <div>
                <a href="{{ route('admin.payments.index') }}"
                   class="inline-flex items-center gap-2 text-blue-600 font-bold mb-3">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Kembali ke Pembayaran
                </a>

                <h1 class="text-4xl font-black text-slate-900">
                    {{ $order->invoice_number }}
                </h1>

                <p class="text-slate-500 mt-2">
                    Detail pembayaran dan verifikasi transaksi.
                </p>
            </div>

            @if($order->payment_status === 'pending')
                <div class="flex gap-3">

                    <form method="POST"
                          action="{{ route('admin.payments.approve', $order) }}">
                        @csrf
                        @method('PATCH')

                        <button
                            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-2xl font-black shadow-lg">
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                            Approve
                        </button>
                    </form>

                    <form method="POST"
                          action="{{ route('admin.payments.reject', $order) }}">
                        @csrf
                        @method('PATCH')

                        <button
                            class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-2xl font-black shadow-lg">
                            <i data-lucide="x-circle" class="w-5 h-5"></i>
                            Reject
                        </button>
                    </form>

                </div>
            @endif
        </div>

        <div class="grid lg:grid-cols-3 gap-8">

            {{-- LEFT --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- PAYMENT PROOF --}}
                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl">

                    <h2 class="text-xl font-black mb-6">
                        Bukti Pembayaran
                    </h2>

                    @if($order->payment_proof)

                        <img
                            src="{{ asset('storage/' . $order->payment_proof) }}"
                            class="w-full rounded-3xl border border-blue-100">

                    @else

                        <div
                            class="h-72 rounded-3xl border-2 border-dashed border-blue-100 flex items-center justify-center text-slate-400">
                            Belum upload bukti pembayaran
                        </div>

                    @endif

                </div>

                {{-- ITEMS --}}
                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl">

                    <h2 class="text-xl font-black mb-6">
                        Item Order
                    </h2>

                    <div class="space-y-4">

                        @foreach($order->items as $item)

                            <div
                                class="flex items-center justify-between border border-blue-50 rounded-2xl p-4">

                                <div>
                                    <p class="font-black text-slate-900">
                                        {{ $item->product->name }}
                                    </p>

                                    <p class="text-sm text-slate-500">
                                        Qty {{ $item->quantity }}
                                    </p>
                                </div>

                                <div class="font-black text-blue-600">
                                    Rp {{ number_format($item->price,0,',','.') }}
                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="space-y-6">

                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl">

                    <h2 class="text-xl font-black mb-5">
                        Informasi Client
                    </h2>

                    <div class="space-y-4">

                        <div>
                            <p class="text-xs text-slate-400 uppercase">
                                Nama
                            </p>

                            <p class="font-bold">
                                {{ $order->user->name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-400 uppercase">
                                Email
                            </p>

                            <p class="font-bold">
                                {{ $order->user->email }}
                            </p>
                        </div>

                    </div>

                </div>

                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl">

                    <h2 class="text-xl font-black mb-5">
                        Informasi Pembayaran
                    </h2>

                    <div class="space-y-4">

                        <div>
                            <p class="text-xs text-slate-400 uppercase">
                                Invoice
                            </p>

                            <p class="font-bold">
                                {{ $order->invoice_number }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-400 uppercase">
                                Status
                            </p>

                            <p class="font-bold">
                                {{ strtoupper($order->payment_status) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-slate-400 uppercase">
                                Total
                            </p>

                            <p class="text-2xl font-black text-blue-600">
                                Rp {{ number_format($order->total_price,0,',','.') }}
                            </p>
                        </div>

                        @if($order->paid_at)
                            <div>
                                <p class="text-xs text-slate-400 uppercase">
                                    Paid At
                                </p>

                                <p class="font-bold">
                                    {{ $order->paid_at->format('d M Y H:i') }}
                                </p>
                            </div>
                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>
</x-layouts.admin>