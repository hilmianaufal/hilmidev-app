<x-guest-layout>
    <!-- Card Utama dengan Efek Transisi Smooth -->
    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)"
         x-show="show"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="bg-white rounded-3xl shadow-[0_20px_50px_rgba(99,102,241,0.05)] border border-slate-100 p-8 sm:p-10 max-w-md w-full mx-auto">

        <div class="text-center mb-8">
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Selamat Datang Kembali ✨</h2>
            <p class="text-sm text-slate-400 mt-1.5">Masuk untuk mengakses produk dan layananmu</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-slate-600 font-medium text-xs uppercase tracking-wider" />
                <x-text-input id="email" class="block mt-1.5 w-full rounded-xl border-slate-200 bg-slate-50/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-all duration-200 py-2.5" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" class="text-slate-600 font-medium text-xs uppercase tracking-wider" />
                <x-text-input id="password" class="block mt-1.5 w-full rounded-xl border-slate-200 bg-slate-50/50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-all duration-200 py-2.5"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between mt-4">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 transition duration-150 ease-in-out cursor-pointer" name="remember">
                    <span class="ms-2 text-sm text-slate-500 font-medium select-none">{{ __('Ingat Saya') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition-colors duration-200" href="{{ route('password.request') }}">
                        {{ __('Lupa Password?') }}
                    </a>
                @endif
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-[0_4px_12px_rgba(99,102,241,0.2)] text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0">
                    {{ __('Masuk Ke Akun') }}
                </button>
            </div>

            <div class="text-center mt-5 pt-2 border-t border-slate-50">
                <p class="text-sm text-slate-500">Belum memiliki akun?
                    <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">Daftar Sekarang</a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
