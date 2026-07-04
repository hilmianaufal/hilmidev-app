<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HilmiDev') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Animasi mengapung halus untuk background blob */
            @keyframes float-slow {
                0%, 100% { transform: translateY(0px) scale(1); }
                50% { transform: translateY(-30px) scale(1.05); }
            }
            .animate-float {
                animation: float-slow 10s ease-in-out infinite;
            }
            .animate-float-delayed {
                animation: float-slow 14s ease-in-out infinite;
                animation-delay: 3s;
            }
        </style>
    </head>
    <body class="font-[Plus_Jakarta_Sans] text-slate-900 antialiased h-full bg-slate-50/50 overflow-x-hidden selection:bg-indigo-600 selection:text-white">

        <div class="fixed inset-0 z-0 overflow-hidden bg-gradient-to-br from-slate-50 via-indigo-50/30 to-blue-50/40">
            <div class="absolute top-[-15%] right-[-10%] w-[55vw] h-[55vw] rounded-full bg-indigo-200/40 blur-[100px] animate-float"></div>
            <div class="absolute bottom-[-15%] left-[-10%] w-[60vw] h-[60vw] rounded-full bg-sky-200/40 blur-[120px] animate-float-delayed"></div>
        </div>

        <div class="relative z-10 min-h-screen flex flex-col justify-center items-center p-4 sm:p-6">

            <div class="mb-6 transition-transform duration-300 hover:scale-105">
                <a href="/" class="flex flex-col items-center gap-2 group">
                    <div class="p-3 bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_rgba(99,102,241,0.06)] group-hover:border-indigo-500/30 group-hover:shadow-[0_4px_25px_rgba(99,102,241,0.12)] transition-all duration-300">
                        <x-application-logo class="w-10 h-10 fill-current text-indigo-600" />
                    </div>
                    <span class="text-[11px] font-bold tracking-widest text-slate-400 uppercase mt-1 group-hover:text-indigo-600 transition-colors">
                        HilmiDev Platform
                    </span>
                </a>
            </div>

            <div class="w-full sm:max-w-md">
                {{ $slot }}
            </div>

            <div class="mt-8 text-center">
                <p class="text-xs text-slate-400 font-medium tracking-wide">
                    &copy; {{ date('Y') }} HilmiDev. All rights reserved.
                </p>
            </div>
        </div>
    </body>
</html>
