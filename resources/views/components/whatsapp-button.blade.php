@php
    $whatsappNumber = preg_replace(
        '/[^0-9]/',
        '',
        (string) config('contact.whatsapp.number')
    );

    $whatsappMessage = rawurlencode(
        (string) config('contact.whatsapp.message')
    );

    $whatsappLabel = config(
        'contact.whatsapp.label',
        'Chat WhatsApp'
    );

    $whatsappUrl = $whatsappNumber !== ''
        ? "https://wa.me/{$whatsappNumber}?text={$whatsappMessage}"
        : null;
@endphp

@if ($whatsappUrl)
    <div
        x-data="{ tooltip: false }"
        class="fixed bottom-5 right-4 z-[60] flex items-center gap-3 sm:bottom-7 sm:right-7"
    >
        {{-- LABEL DESKTOP --}}
        <div
            x-show="tooltip"
            x-cloak
            x-transition
            class="hidden rounded-2xl border border-emerald-100 bg-white px-4 py-3 shadow-2xl shadow-slate-950/15 sm:block"
        >
            <p class="text-xs font-black text-slate-900">
                Butuh bantuan?
            </p>

            <p class="mt-1 text-[10px] font-semibold text-slate-500">
                Konsultasi langsung melalui WhatsApp
            </p>
        </div>

        <a
            href="{{ $whatsappUrl }}"
            target="_blank"
            rel="noopener noreferrer"
            aria-label="{{ $whatsappLabel }}"
            title="{{ $whatsappLabel }}"
            @mouseenter="tooltip = true"
            @mouseleave="tooltip = false"
            class="group relative inline-flex h-15 items-center justify-center gap-3 rounded-full bg-emerald-500 px-4 text-white shadow-2xl shadow-emerald-600/30 transition duration-300 hover:-translate-y-1 hover:bg-emerald-600 sm:h-16 sm:px-5"
        >
            {{-- EFEK PING --}}
            <span class="absolute inset-0 -z-10 animate-ping rounded-full bg-emerald-400 opacity-20"></span>

            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/15">
                <svg
                    viewBox="0 0 32 32"
                    aria-hidden="true"
                    class="h-6 w-6 fill-current"
                >
                    <path d="M16.02 3C8.84 3 3 8.71 3 15.74c0 2.25.6 4.45 1.75 6.38L3 29l7.08-1.82a13.14 13.14 0 0 0 5.93 1.42h.01C23.2 28.6 29 22.89 29 15.86 29 8.82 23.2 3 16.02 3Zm0 23.45h-.01a10.92 10.92 0 0 1-5.56-1.5l-.4-.24-4.2 1.08 1.12-4.02-.26-.41a10.42 10.42 0 0 1-1.65-5.62c0-5.84 4.92-10.59 10.97-10.59 2.93 0 5.68 1.12 7.75 3.15a10.34 10.34 0 0 1 3.22 7.56c0 5.84-4.92 10.59-10.98 10.59Zm6.02-7.93c-.33-.16-1.96-.94-2.26-1.05-.3-.11-.52-.16-.74.16-.22.32-.85 1.05-1.04 1.27-.19.21-.38.24-.71.08-.33-.16-1.39-.5-2.65-1.59a9.86 9.86 0 0 1-1.84-2.23c-.19-.32-.02-.5.14-.66.15-.14.33-.37.49-.56.16-.19.22-.32.33-.54.11-.21.05-.4-.03-.56-.08-.16-.74-1.74-1.01-2.38-.27-.64-.54-.55-.74-.56h-.63c-.22 0-.57.08-.87.4-.3.32-1.15 1.1-1.15 2.67 0 1.58 1.18 3.1 1.34 3.31.16.21 2.32 3.46 5.62 4.85.79.33 1.4.53 1.88.68.79.24 1.5.21 2.07.13.63-.09 1.96-.78 2.23-1.53.27-.75.27-1.39.19-1.53-.08-.13-.3-.21-.63-.37Z"/>
                </svg>
            </span>

            <span class="hidden pr-1 text-left sm:block">
                <span class="block text-[9px] font-bold uppercase tracking-wider text-emerald-50">
                    Konsultasi Gratis
                </span>

                <span class="mt-0.5 block text-xs font-black">
                    {{ $whatsappLabel }}
                </span>
            </span>
        </a>
    </div>
@endif
