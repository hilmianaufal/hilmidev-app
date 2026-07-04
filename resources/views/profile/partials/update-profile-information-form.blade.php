<section class="bg-white rounded-3xl shadow-[0_8px_30px_rgba(99,102,241,0.04)] border border-slate-100 p-6 sm:p-8 max-w-2xl w-full mx-auto overflow-hidden relative">

    <!-- Ornamen Dekorasi Background (Cerah & Estetik) -->
    <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-50 rounded-full mix-blend-multiply filter blur-2xl opacity-70 pointer-events-none"></div>

    <header class="border-b border-slate-100 pb-5 mb-6 relative z-10">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                <!-- Icon User Geometris -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">
                    {{ __('Informasi Profil') }}
                </h2>
                <p class="mt-1 text-xs sm:text-sm text-slate-400 font-medium">
                    {{ __('Perbarui data personal akun dan alamat email Anda secara berkala.') }}
                </p>
            </div>
        </div>
    </header>

    <!-- Form Tersembunyi untuk Verifikasi -->
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6 relative z-10">
        @csrf
        @method('patch')

        <!-- Input Nama Lengkap -->
        <div class="group">
            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-slate-600 font-bold text-xs uppercase tracking-wider block mb-2" />
            <div class="relative rounded-xl shadow-sm transition-all duration-300">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </div>
                <x-text-input id="name" name="name" type="text" class="pl-11 block w-full rounded-xl border-slate-200 bg-slate-50/50 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-all duration-200 py-3 text-sm font-medium" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            </div>
            <x-input-error class="mt-2 text-xs" :messages="$errors->get('name')" />
        </div>

        <!-- Input Alamat Email -->
        <div class="group">
            <x-input-label for="email" :value="__('Alamat Email')" class="text-slate-600 font-bold text-xs uppercase tracking-wider block mb-2" />
            <div class="relative rounded-xl shadow-sm transition-all duration-300">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                </div>
                <x-text-input id="email" name="email" type="email" class="pl-11 block w-full rounded-xl border-slate-200 bg-slate-50/50 focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-all duration-200 py-3 text-sm font-medium" :value="old('email', $user->email)" required autocomplete="username" />
            </div>
            <x-input-error class="mt-2 text-xs" :messages="$errors->get('email')" />

            <!-- Logika Verifikasi Email -->
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3.5 bg-amber-50/70 border border-amber-100 rounded-xl flex items-start gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-amber-500 mt-0.5 shrink-0">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.25v2.75a.75.75 0 0 0 1.5 0v-3.5A.75.75 0 0 0 10 9H9Z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-xs font-semibold text-amber-800 leading-normal">
                            {{ __('Alamat email Anda belum terverifikasi.') }}
                            <button form="send-verification" class="block sm:inline sm:ml-1 text-xs font-bold text-amber-600 hover:text-amber-700 underline focus:outline-none transition-colors mt-0.5 sm:mt-0">
                                {{ __('Klik di sini untuk mengirim ulang link verifikasi.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-bold text-xs text-emerald-600 flex items-center gap-1.5 animate-fade-in">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                                </svg>
                                {{ __('Tautan verifikasi baru telah dikirim ke email Anda.') }}
                            </p>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Tombol Aksi dengan Notifikasi Status Sukses -->
        <div class="flex items-center gap-4 pt-2 border-t border-slate-50">
            <button type="submit" class="flex items-center gap-2 px-5 py-2.5 border border-transparent rounded-xl shadow-[0_4px_12px_rgba(99,102,241,0.15)] text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
                {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
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
                    <span>{{ __('Berhasil Disimpan') }}</span>
                </div>
            @endif
        </div>
    </form>
</section>
