<x-layouts.admin>
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-blue-600 via-cyan-500 to-blue-400 p-8 text-white shadow-2xl shadow-blue-500/20 mb-8">
            <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/20 rounded-full blur-3xl"></div>

            <div class="relative">
                <div class="inline-flex items-center gap-2 bg-white/15 border border-white/20 px-4 py-2 rounded-full mb-4">
                    <i data-lucide="users" class="w-4 h-4"></i>
                    <span class="text-sm font-bold">Client Management</span>
                </div>

                <h1 class="text-3xl md:text-5xl font-black">
                    Clients
                </h1>

                <p class="text-blue-50 mt-3">
                    Kelola daftar client yang sudah register di HilmiDev.
                </p>
            </div>
        </div>

        <div class="bg-white border border-blue-100 rounded-[2rem] overflow-hidden shadow-xl shadow-blue-500/5">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-blue-50 text-blue-700">
                        <tr>
                            <th class="px-5 py-4 text-left">Client</th>
                            <th class="px-5 py-4 text-left">Orders</th>
                            <th class="px-5 py-4 text-left">Projects</th>
                            <th class="px-5 py-4 text-left">Bergabung</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-blue-50">
                        @forelse ($clients as $client)
                            <tr class="hover:bg-blue-50/40 transition">
                                <td class="px-5 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-400 text-white flex items-center justify-center font-black">
                                            {{ strtoupper(substr($client->name, 0, 1)) }}
                                        </div>

                                        <div>
                                            <p class="font-black text-slate-900">
                                                {{ $client->name }}
                                            </p>

                                            <p class="text-xs text-slate-500 mt-1">
                                                {{ $client->email }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-5">
                                    <span class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 px-3 py-2 rounded-2xl font-black">
                                        <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                                        {{ $client->orders_count }}
                                    </span>
                                </td>

                                <td class="px-5 py-5">
                                    <span class="inline-flex items-center gap-2 bg-cyan-50 text-cyan-700 px-3 py-2 rounded-2xl font-black">
                                        <i data-lucide="folder-kanban" class="w-4 h-4"></i>
                                        {{ $client->project_requests_count }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 text-slate-500">
                                    {{ $client->created_at->format('d M Y') }}
                                </td>

                                <td class="px-5 py-5 text-right">
                                    <a href="{{ route('admin.clients.show', $client) }}"
                                       class="inline-flex items-center justify-center gap-2 bg-blue-600 text-white px-4 py-3 rounded-2xl font-black shadow-lg shadow-blue-500/20">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-16 text-center">
                                    <div class="w-16 h-16 mx-auto rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                                        <i data-lucide="users" class="w-8 h-8"></i>
                                    </div>

                                    <p class="font-black text-slate-900">Belum ada client.</p>
                                    <p class="text-slate-500 text-sm mt-1">
                                        User yang register sebagai client akan tampil di sini.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $clients->links() }}
        </div>
    </div>
</x-layouts.admin>