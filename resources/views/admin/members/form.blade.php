<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Nama</label>
        <input name="name" value="{{ old('name', $user?->name) }}" class="w-full rounded-2xl border-slate-200" required>
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Email</label>
        <input type="email" name="email" value="{{ old('email', $user?->email) }}" class="w-full rounded-2xl border-slate-200" required>
        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Password {{ $user ? '(opsional)' : '' }}</label>
        <input type="password" name="password" class="w-full rounded-2xl border-slate-200" {{ $user ? '' : 'required' }}>
    </div>

    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="w-full rounded-2xl border-slate-200" {{ $user ? '' : 'required' }}>
    </div>
</div>

<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Paket</label>
        <select name="membership_plan_id" class="w-full rounded-2xl border-slate-200" required>
            <option value="">Pilih paket</option>

            @foreach ($plans as $plan)
                <option value="{{ $plan->id }}" @selected((string) old('membership_plan_id', $membership?->membership_plan_id) === (string) $plan->id)>
                    {{ $plan->name }} — Rp {{ number_format($plan->price, 0, ',', '.') }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Status</label>
        <select name="status" class="w-full rounded-2xl border-slate-200" required>
            @foreach (['pending', 'active', 'suspended', 'expired', 'cancelled'] as $status)
                <option value="{{ $status }}" @selected(old('status', $membership?->status ?? 'active') === $status)>
                    {{ strtoupper($status) }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Mulai Aktif</label>
        <input type="datetime-local"
               name="starts_at"
               value="{{ old('starts_at', $membership?->starts_at?->format('Y-m-d\TH:i')) }}"
               class="w-full rounded-2xl border-slate-200">
    </div>

    <div>
        <label class="mb-2 block text-sm font-black text-slate-700">Berakhir</label>
        <input type="datetime-local"
               name="expires_at"
               value="{{ old('expires_at', $membership?->expires_at?->format('Y-m-d\TH:i')) }}"
               class="w-full rounded-2xl border-slate-200">

        <p class="mt-1 text-xs text-slate-400">Kosongkan agar otomatis mengikuti durasi paket.</p>
    </div>
</div>

<div>
    <label class="mb-2 block text-sm font-black text-slate-700">Catatan</label>
    <textarea name="notes" rows="4" class="w-full rounded-2xl border-slate-200">{{ old('notes', $membership?->notes) }}</textarea>
</div>

<button class="w-full rounded-2xl bg-blue-700 px-6 py-4 font-black text-white">Simpan Data Member</button>
