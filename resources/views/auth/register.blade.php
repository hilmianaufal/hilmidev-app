<x-guest-layout>
    <!-- Card Utama dengan Efek Transisi Smooth -->
    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)"
         x-show="show"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="bg-white rounded-3xl shadow-[0_20px_50px_rgba(99,102,241,0.05)] border border-slate-100 p-8 sm:p-10 max-w-md w-full mx-auto">

        <div class="text-center mb-8">
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Buat Akun Baru 🚀</h2>
            <p class="text-sm text-slate-400 mt-1.5">Mulai beli source code dan request website impianmu</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Nama Lengkap')" class="text-slate-600 font-medium text-xs uppercase tracking-wider" />
                <x-text-input id="name" class="block mt-1.5 w-full rounded-xl border-slate-200 bg-slate-50/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-all duration-200 py-2.5" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Alamat Email')" class="text-slate-600 font-medium text-xs uppercase tracking-wider" />
                <x-text-input id="email" class="block mt-1.5 w-full rounded-xl border-slate-200 bg-slate-50/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-all duration-200 py-2.5" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" class="text-slate-600 font-medium text-xs uppercase tracking-wider" />
                <x-text-input id="password" class="block mt-1.5 w-full rounded-xl border-slate-200 bg-slate-50/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-all duration-200 py-2.5"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-slate-600 font-medium text-xs uppercase tracking-wider" />
                <x-text-input id="password_confirmation" class="block mt-1.5 w-full rounded-xl border-slate-200 bg-slate-50/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-all duration-200 py-2.5"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-[0_4px_12px_rgba(99,102,241,0.2)] text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0">
                    {{ __('Daftar Sekarang') }}
                </button>
            </div>

            <div class="text-center mt-5 pt-2 border-t border-slate-50">
                <p class="text-sm text-slate-500">Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">Masuk Di Sini</a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
