<x-layouts.admin>
    <div class="max-w-7xl mx-auto px-4 py-10">

        <div class="flex items-center justify-between mb-8">
            <div>
                <a href="{{ route('admin.clients.index') }}"
                   class="inline-flex items-center gap-2 text-blue-600 font-bold mb-3">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Kembali ke Clients
                </a>

                <h1 class="text-4xl font-black text-slate-900">
                    {{ $user->name }}
                </h1>

                <p class="text-slate-500 mt-2">
                    Detail client dan riwayat aktivitas.
                </p>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">

            {{-- LEFT --}}
            <div class="space-y-6">

                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl">
                    <div class="flex flex-col items-center text-center">

                        <div class="w-24 h-24 rounded-[2rem] bg-gradient-to-br from-blue-600 to-cyan-400 text-white flex items-center justify-center text-4xl font-black mb-5">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>

                        <h2 class="text-2xl font-black">
                            {{ $user->name }}
                        </h2>

                        <p class="text-slate-500">
                            {{ $user->email }}
                        </p>

                        <div class="mt-6 w-full space-y-3">

                            <div class="bg-blue-50 rounded-2xl p-4">
                                <p class="text-xs text-slate-500 uppercase">
                                    Bergabung
                                </p>

                                <p class="font-black">
                                    {{ $user->created_at->format('d M Y') }}
                                </p>
                            </div>

                            <div class="bg-green-50 rounded-2xl p-4">
                                <p class="text-xs text-slate-500 uppercase">
                                    Total Belanja
                                </p>

                                <p class="text-2xl font-black text-green-600">
                                    Rp {{ number_format($totalSpent,0,',','.') }}
                                </p>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">

                    <div class="bg-white border border-blue-100 rounded-[2rem] p-5 shadow-xl text-center">
                        <p class="text-slate-500 text-sm">
                            Orders
                        </p>

                        <p class="text-3xl font-black text-blue-600 mt-2">
                            {{ $user->orders->count() }}
                        </p>
                    </div>

                    <div class="bg-white border border-blue-100 rounded-[2rem] p-5 shadow-xl text-center">
                        <p class="text-slate-500 text-sm">
                            Projects
                        </p>

                        <p class="text-3xl font-black text-cyan-600 mt-2">
                            {{ $user->projectRequests->count() }}
                        </p>
                    </div>

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- ORDERS --}}
                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl">

                    <h2 class="text-xl font-black mb-6">
                        Riwayat Order
                    </h2>

                    <div class="space-y-4">

                        @forelse($user->orders as $order)

                            <div class="border border-blue-50 rounded-2xl p-4">

                                <div class="flex items-center justify-between">

                                    <div>
                                        <p class="font-black">
                                            {{ $order->invoice_number }}
                                        </p>

                                        <p class="text-sm text-slate-500">
                                            {{ $order->created_at->format('d M Y H:i') }}
                                        </p>
                                    </div>

                                    <div class="text-right">
                                        <p class="font-black text-blue-600">
                                            Rp {{ number_format($order->total_price,0,',','.') }}
                                        </p>

                                        <p class="text-xs text-slate-500">
                                            {{ strtoupper($order->payment_status) }}
                                        </p>
                                    </div>

                                </div>

                            </div>

                        @empty

                            <p class="text-slate-500">
                                Belum ada order.
                            </p>

                        @endforelse

                    </div>

                </div>

                {{-- PROJECTS --}}
                <div class="bg-white border border-blue-100 rounded-[2rem] p-6 shadow-xl">

                    <h2 class="text-xl font-black mb-6">
                        Project Requests
                    </h2>

                    <div class="space-y-4">

                        @forelse($user->projectRequests as $project)

                            <div class="border border-blue-50 rounded-2xl p-4">

                                <div class="flex items-center justify-between">

                                    <div>
                                        <p class="font-black">
                                            {{ $project->project_name }}
                                        </p>

                                        <p class="text-sm text-slate-500">
                                            {{ $project->service->title ?? '-' }}
                                        </p>
                                    </div>

                                    <span class="px-3 py-2 rounded-xl bg-blue-50 text-blue-700 text-xs font-black">
                                        {{ strtoupper($project->status) }}
                                    </span>

                                </div>

                            </div>

                        @empty

                            <p class="text-slate-500">
                                Belum ada project request.
                            </p>

                        @endforelse

                    </div>

                </div>

            </div>

        </div>

    </div>
</x-layouts.admin>