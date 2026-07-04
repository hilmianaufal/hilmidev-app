<section class="bg-white rounded-3xl shadow-[0_8px_30px_rgba(99,102,241,0.04)] border border-slate-100 p-6 sm:p-8 max-w-2xl w-full mx-auto overflow-hidden relative mt-8">

    <div class="absolute -top-24 -right-24 w-48 h-48 bg-blue-50 rounded-full mix-blend-multiply filter blur-2xl opacity-70 pointer-events-none"></div>

    <header class="border-b border-slate-100 pb-5 mb-6 relative z-10">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-blue-50 text-blue-600 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">
                    {{ __('Perbarui Password') }}
                </h2>
                <p class="mt-1 text-xs sm:text-sm text-slate-400 font-medium">
                    {{ __('Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.') }}
                </p>
            </div>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6 relative z-10">
        @csrf
        @method('put')

        <div class="group">
            <x-input-label for="update_password_current_password" :value="__('Password Saat Ini')" class="text-slate-600 font-bold text-xs uppercase tracking-wider block mb-2" />
            <div class="relative rounded-xl shadow-sm transition-all duration-300">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </div>
                <x-text-input id="update_password_current_password" name="current_password" type="password" class="pl-11 block w-full rounded-xl border-slate-200 bg-slate-50/50 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-all duration-200 py-3 text-sm font-medium" autocomplete="current-password" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-xs" />
        </div>

        <div class="group">
            <x-input-label for="update_password_password" :value="__('Password Baru')" class="text-slate-600 font-bold text-xs uppercase tracking-wider block mb-2" />
            <div class="relative rounded-xl shadow-sm transition-all duration-300">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </div>
                <x-text-input id="update_password_password" name="password" type="password" class="pl-11 block w-full rounded-xl border-slate-200 bg-slate-50/50 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-all duration-200 py-3 text-sm font-medium" autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-xs" />
        </div>

        <div class="group">
            <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Password Baru')" class="text-slate-600 font-bold text-xs uppercase tracking-wider block mb-2" />
            <div class="relative rounded-xl shadow-sm transition-all duration-300">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                    </svg>
                </div>
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="pl-11 block w-full rounded-xl border-slate-200 bg-slate-50/50 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-all duration-200 py-3 text-sm font-medium" autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-xs" />
        </div>

        <div class="flex items-center gap-4 pt-2 border-t border-slate-50">
            <button type="submit" class="flex items-center gap-2 px-5 py-2.5 border border-transparent rounded-xl shadow-[0_4px_12px_rgba(99,102,241,0.15)] text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
                {{ __('Perbarui Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <div x-data="{ show: true }"
                     x-show="show"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-x-2"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     x-init="setTimeout(() => show = false, 3000)"
                     class="flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-700 text-xs font-bold shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ __('Password Diperbarui') }}</span>
                </div>
            @endif
        </div>
    </form>
</section>
