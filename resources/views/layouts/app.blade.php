<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        body {
            font-family: 'Inter', 'Cairo', sans-serif;
        }

        [dir="rtl"] body {
            font-family: 'Cairo', 'Inter', sans-serif;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 4px; }
        html.dark ::-webkit-scrollbar-thumb { background: #4b5563; }
        
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        html.dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #374151; }
    </style>
</head>
<body class="font-sans antialiased text-gray-800 dark:text-gray-200 bg-[#f8fafc] dark:bg-[#0f172a] duration-300">

    <div class="min-h-screen">
        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Header Area -->
        @isset($header)
            <div class="bg-white/40 dark:bg-gray-900/40 border-b border-gray-200/50 dark:border-gray-800/50 backdrop-blur-xl">
                <div class="max-w-7xl mx-auto py-10 px-6 sm:px-8">
                    {{ $header }}
                </div>
            </div>
        @endisset

        <!-- Main Content Section -->
        <main class="py-12">
            <div class="max-w-7xl mx-auto px-6 sm:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    @stack('scripts')
</body>
</html>
