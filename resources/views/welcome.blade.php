<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('welcome.title') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;400;500;600;700;800;900&family=IBM+Plex+Sans+Arabic:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        :root {
            --primary: #6366f1;
            --primary-hv: #4f46e5;
        }

        body {
            font-family: {{ app()->getLocale() == 'ar' ? "'IBM Plex Sans Arabic', sans-serif" : "'Outfit', sans-serif" }};
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .dark .glass-card {
            background: rgba(17, 24, 39, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .hero-gradient {
            background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.15), transparent 400px),
                        radial-gradient(circle at bottom left, rgba(139, 92, 246, 0.15), transparent 400px);
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
    </style>

    <script>
        // Synchronize theme with local storage
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 selection:bg-indigo-500 selection:text-white">
    
    <div class="min-h-screen relative overflow-hidden hero-gradient">
        <!-- Floating shapes for aesthetics -->
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-violet-500/10 rounded-full blur-3xl"></div>

        <!-- Navigation -->
        <nav class="relative z-50 flex items-center justify-between px-6 py-8 mx-auto max-w-7xl">
            <div class="flex items-center gap-3 group">
                <div class="w-12 h-12 bg-gradient-to-tr from-indigo-600 to-violet-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/20 group-hover:rotate-12 transition-transform duration-300">
                    <span class="text-white font-black text-2xl">P</span>
                </div>
                <span class="text-xl font-extrabold uppercase tracking-widest text-gray-800 dark:text-white hidden sm:block">
                    {{ config('app.name', 'Partnals') }}
                </span>
            </div>

            <div class="flex items-center gap-4">
                <!-- Language Switcher -->
                <a href="{{ route('lang.switch', app()->getLocale() == 'ar' ? 'en' : 'ar') }}" 
                   class="px-4 py-2 rounded-xl glass-card text-sm font-bold flex items-center gap-2 hover:bg-white dark:hover:bg-gray-800 transition-all">
                    <span class="text-lg">🌐</span>
                    <span>{{ app()->getLocale() == 'ar' ? 'English' : 'العربية' }}</span>
                </a>

                <!-- Theme Toggle -->
                <button onclick="toggleTheme()" class="p-2.5 rounded-xl glass-card hover:bg-white dark:hover:bg-gray-800 transition-all">
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                </button>

                @if (Route::has('login'))
                    <div class="hidden sm:flex gap-2">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/25">
                                {{ __('welcome.get_started') }}
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-6 py-2.5 rounded-xl glass-card font-bold hover:bg-white dark:hover:bg-gray-800 transition-all">
                                {{ __('welcome.login') }}
                            </a>
                        @endauth
                    </div>
                @endif
            </div>
        </nav>

        <!-- Hero Section -->
        <header class="relative z-10 px-6 py-12 md:py-24 mx-auto max-w-7xl">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-8 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                    <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-600 dark:text-indigo-400 text-xs font-black uppercase tracking-widest">
                        🚀 {{ __('welcome.title') }} 2026
                    </div>
                    <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight leading-[1.1]">
                        {{ __('welcome.title') }} <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-500">
                            {{ app()->getLocale() == 'ar' ? 'حلول اتصالات ذكية' : 'Smart Solutions' }}
                        </span>
                    </h1>
                    <p class="text-xl text-gray-600 dark:text-gray-400 max-w-xl leading-relaxed">
                        {{ __('welcome.subtitle') }}
                    </p>
                    <div class="flex flex-wrap gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-8 py-4 rounded-2xl bg-indigo-600 text-white font-bold text-lg hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-500/40 hover:-translate-y-1">
                                {{ __('welcome.get_started') }}
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-8 py-4 rounded-2xl bg-indigo-600 text-white font-bold text-lg hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-500/40 hover:-translate-y-1">
                                {{ __('welcome.get_started') }}
                            </a>
                        @endauth
                        <a href="#guide" class="px-8 py-4 rounded-2xl glass-card font-bold text-lg hover:shadow-lg transition-all hover:-translate-y-1">
                            {{ __('welcome.how_it_works') }}
                        </a>
                    </div>
                </div>

                <div class="relative lg:block">
                    <div class="absolute inset-0 bg-gradient-to-tr from-indigo-500/30 to-violet-500/30 blur-[100px]"></div>
                    <div class="relative glass-card rounded-[2.5rem] p-4 p-x6 overflow-hidden shadow-2xl float-animation border-white/20">
                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=1000" alt="Dashboard Preview" class="rounded-[2rem] w-full h-auto object-cover opacity-90">
                    </div>
                </div>
            </div>
        </header>

        <!-- Guide Section -->
        <section id="guide" class="relative z-10 px-6 py-24 mx-auto max-w-7xl">
            <div class="text-center space-y-4 mb-20">
                <h2 class="text-4xl md:text-5xl font-black">{{ __('welcome.guide_title') }}</h2>
                <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto">{{ __('welcome.guide_subtitle') }}</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1: Dashboard -->
                <div class="p-8 rounded-3xl glass-card border border-white/20 hover:border-indigo-500/50 transition-all group overflow-hidden relative">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-500/5 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-colors"></div>
                    <div class="w-16 h-16 rounded-2xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-3xl mb-6">📊</div>
                    <h3 class="text-xl font-bold mb-4">{{ __('welcome.feature_dashboard_title') }}</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">{{ __('welcome.feature_dashboard_desc') }}</p>
                </div>

                <!-- Feature 2: Customers -->
                <div class="p-8 rounded-3xl glass-card border border-white/20 hover:border-violet-500/50 transition-all group overflow-hidden relative">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-violet-500/5 rounded-full blur-2xl group-hover:bg-violet-500/20 transition-colors"></div>
                    <div class="w-16 h-16 rounded-2xl bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center text-3xl mb-6">👥</div>
                    <h3 class="text-xl font-bold mb-4">{{ __('welcome.feature_customers_title') }}</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">{{ __('welcome.feature_customers_desc') }}</p>
                </div>

                <!-- Feature 3: Lines -->
                <div class="p-8 rounded-3xl glass-card border border-white/20 hover:border-blue-500/50 transition-all group overflow-hidden relative">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-500/5 rounded-full blur-2xl group-hover:bg-blue-500/20 transition-colors"></div>
                    <div class="w-16 h-16 rounded-2xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-3xl mb-6">📞</div>
                    <h3 class="text-xl font-bold mb-4">{{ __('welcome.feature_lines_title') }}</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">{{ __('welcome.feature_lines_desc') }}</p>
                </div>

                <!-- Feature 4: Requests -->
                <div class="p-8 rounded-3xl glass-card border border-white/20 hover:border-emerald-500/50 transition-all group overflow-hidden relative">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-colors"></div>
                    <div class="w-16 h-16 rounded-2xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-3xl mb-6">⚙️</div>
                    <h3 class="text-xl font-bold mb-4">{{ __('welcome.feature_requests_title') }}</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">{{ __('welcome.feature_requests_desc') }}</p>
                </div>

                <!-- Feature 5: Accounting -->
                <div class="p-8 rounded-3xl glass-card border border-white/20 hover:border-amber-500/50 transition-all group overflow-hidden relative">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-500/5 rounded-full blur-2xl group-hover:bg-amber-500/20 transition-colors"></div>
                    <div class="w-16 h-16 rounded-2xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-3xl mb-6">💰</div>
                    <h3 class="text-xl font-bold mb-4">{{ __('welcome.feature_accounting_title') }}</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">{{ __('welcome.feature_accounting_desc') }}</p>
                </div>

                <!-- Feature 6: HR -->
                <div class="p-8 rounded-3xl glass-card border border-white/20 hover:border-rose-500/50 transition-all group overflow-hidden relative">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-rose-500/5 rounded-full blur-2xl group-hover:bg-rose-500/20 transition-colors"></div>
                    <div class="w-16 h-16 rounded-2xl bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center text-3xl mb-6">🤵</div>
                    <h3 class="text-xl font-bold mb-4">{{ __('welcome.feature_hr_title') }}</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">{{ __('welcome.feature_hr_desc') }}</p>
                </div>
            </div>
        </section>

        <!-- CTAs / How it works -->
        <section class="relative z-10 px-6 py-24 mx-auto max-w-5xl">
            <div class="p-12 md:p-20 rounded-[3rem] glass-card text-center border-white/20 shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 via-violet-500 to-indigo-500"></div>
                <h2 class="text-4xl md:text-5xl font-black mb-12">{{ __('welcome.how_it_works') }}</h2>
                
                <div class="grid md:grid-cols-3 gap-8 relative">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xl mx-auto shadow-lg shadow-indigo-500/30">1</div>
                        <p class="font-bold text-lg">{{ __('welcome.step_1') }}</p>
                    </div>
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xl mx-auto shadow-lg shadow-indigo-500/30">2</div>
                        <p class="font-bold text-lg">{{ __('welcome.step_2') }}</p>
                    </div>
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xl mx-auto shadow-lg shadow-indigo-500/30">3</div>
                        <p class="font-bold text-lg">{{ __('welcome.step_3') }}</p>
                    </div>
                </div>

                <div class="mt-16">
                    <a href="{{ route('login') }}" class="px-10 py-5 rounded-2xl bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-extrabold text-xl hover:scale-105 transition-transform shadow-2xl">
                        {{ __('welcome.get_started') }}
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="relative z-10 px-6 py-12 text-center text-gray-500 dark:text-gray-400">
            <div class="flex items-center justify-center gap-2 mb-4">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-black text-sm">P</div>
                <span class="font-black uppercase tracking-tighter">{{ config('app.name', 'Partnals') }}</span>
            </div>
            <p class="text-sm font-medium">
                {{ __('welcome.footer_text') }}
            </p>
            <p class="mt-2 text-xs opacity-50">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </p>
        </footer>
    </div>

    <script>
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        function toggleTheme() {
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            if (localStorage.getItem('theme')) {
                if (localStorage.getItem('theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            }
        }
    </script>
</body>
</html>
