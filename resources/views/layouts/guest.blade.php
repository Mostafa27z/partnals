<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        @if(app()->getLocale() == 'ar')
            <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
            <style> body { font-family: 'Cairo', sans-serif !important; } </style>
        @else
            <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @endif

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            // Theme initialization
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    <body class="font-sans text-gray-900 antialiased relative selection:bg-indigo-500 selection:text-white">
        <!-- Floating Language Switcher -->
        <div class="absolute top-4 {{ app()->getLocale() == 'ar' ? 'left-4' : 'right-4' }} z-50">
            @if(app()->getLocale() == 'ar')
                <a href="{{ route('lang.switch', 'en') }}" class="px-4 py-2 bg-indigo-600/10 backdrop-blur-md border border-white/40 shadow hover:bg-white/50 rounded-full text-sm font-bold text-white transition-all uppercase tracking-wide">
                    English
                </a>
            @else
                <a href="{{ route('lang.switch', 'ar') }}" class="px-4 py-2 bg-indigo-600/10 backdrop-blur-md border border-white/40 shadow hover:bg-white/50 rounded-full text-sm font-bold text-white transition-all uppercase tracking-wide">
                    العربية
                </a>
            @endif
        </div>

        <!-- Premium Gradient Background -->
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 dark:from-gray-900 dark:via-gray-800 dark:to-black">
            <div>
                <a href="/" class="text-3xl font-extrabold text-white tracking-widest drop-shadow-lg">
                    {{ __('Partnals System') }}
                </a>
            </div>

            <!-- Glassmorphism Card -->
            <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl shadow-2xl overflow-hidden sm:rounded-2xl border border-white/20 dark:border-gray-700">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
