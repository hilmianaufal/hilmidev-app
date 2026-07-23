<x-layouts.admin>
    <div class="mx-auto max-w-4xl px-4 py-10">
        <a href="{{ route('admin.members.index') }}" class="text-sm font-black text-blue-700">← Kembali</a>
        <h1 class="mt-5 text-3xl font-black text-slate-950">Buat Akun Member</h1>

        <form method="POST" action="{{ route('admin.members.store') }}" class="mt-8 space-y-6 rounded-[2rem] border border-slate-200 bg-white p-7 shadow-sm">
            @csrf
            @include('admin.members.form', ['user' => null, 'membership' => null])
        </form>
    </div>
</x-layouts.admin>
