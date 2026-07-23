<x-layouts.admin>
    <div class="mx-auto max-w-3xl px-4 py-10">
        <a href="{{ route('admin.membership-plans.index') }}" class="text-sm font-black text-blue-700">← Kembali</a>
        <h1 class="mt-5 text-3xl font-black text-slate-950">Tambah Paket Membership</h1>

        <form method="POST" action="{{ route('admin.membership-plans.store') }}" class="mt-8 space-y-5 rounded-[2rem] border border-slate-200 bg-white p-7 shadow-sm">
            @csrf
            @include('admin.membership-plans.form', ['plan' => null])
        </form>
    </div>
</x-layouts.admin>
