<nav x-data="{ open: false, mobileOpen: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 shadow-sm transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Left Side -->
            <div class="flex items-center gap-4">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex-shrink-0 flex items-center font-bold text-2xl text-indigo-600 dark:text-indigo-400 uppercase tracking-widest drop-shadow-sm">
                    {{ __('Partnals') }}
                </a>

                <!-- Primary Nav Links (Desktop) -->
                <div class="hidden lg:flex gap-2">
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('messages.dashboard') }}
    </x-nav-link>

    <x-nav-link :href="route('plans.index')" :active="request()->routeIs('plans.*')">
        {{ __('messages.plans') }}
    </x-nav-link>

    <x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">
        {{ __('messages.customers') }}
    </x-nav-link>

    <x-nav-link :href="route('lines.all')" :active="request()->routeIs('lines.*')">
        {{ __('messages.lines') }}
    </x-nav-link>

    <x-nav-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')">
        {{ __('messages.invoices') }}
    </x-nav-link>
</div>

<!-- Secondary Nav Links Dropdown (Desktop) -->
<div class="hidden lg:block">
    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" class="flex items-center gap-1 px-3 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:text-gray-200 transition text-sm">
            More
            <svg class="h-4 w-4 transition" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="open" @click.away="open = false" x-transition
             class="absolute left-0 mt-2 bg-white dark:bg-gray-800 rounded shadow-lg border z-50 w-48">
            <x-dropdown-link :href="route('users.index')">
                👥 {{ __('messages.users') }}
            </x-dropdown-link>
            <x-dropdown-link :href="route('permissions.index')">
                🔑 {{ __('messages.permissions') }}
            </x-dropdown-link>
            <x-dropdown-link :href="route('change-logs.index')">
                📝 {{ __('messages.change_log') }}
            </x-dropdown-link>
            
            <div class="border-t border-gray-200 dark:border-gray-700"></div>
            
            <x-dropdown-link :href="route('requests.all')">
                📄 {{ __('messages.all_requests') }}
            </x-dropdown-link>
            <x-dropdown-link :href="route('requests.summary')">
                📊 {{ __('messages.summary') }}
            </x-dropdown-link>
            <x-dropdown-link :href="route('requests.history')">
                🕓 {{ __('messages.history') }}
            </x-dropdown-link>
        </div>
    </div>
</div>
            </div>

            <!-- Right Side -->
            <div class="hidden sm:flex items-center gap-4">
                <!-- Theme Toggle -->
                <button onclick="toggleTheme()" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:bg-gray-900 dark:hover:bg-gray-700 focus:outline-none rounded-lg text-sm p-2 transition-colors duration-200">
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                </button>

                <!-- Language Switcher -->
                <div>
                    @php
                        $currentLocale = app()->getLocale();
                        $newLocale = $currentLocale === 'ar' ? 'en' : 'ar';
                    @endphp
                    <a href="{{ route('lang.switch', $newLocale) }}"
                       class="text-sm font-bold text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition bg-gray-100 dark:bg-gray-700 px-3 py-2 rounded-full hidden sm:inline-flex items-center justify-center">
                        🌐 {{ strtoupper($newLocale) }}
                    </a>
                </div>

                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-500 dark:text-gray-300 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-white">
                            <div class="truncate max-w-[120px]">{{ Auth::user()->name }}</div>
                            <svg class="ml-1 h-4 w-4 fill-current" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.3 7.3a1 1 0 011.4 0L10 10.6l3.3-3.3a1 1 0 111.4 1.4l-4 4a1 1 0 01-1.4 0l-4-4a1 1 0 010-1.4z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('messages.profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('messages.logout') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Menu Button -->
            <div class="sm:hidden flex items-center">
                <button @click="mobileOpen = ! mobileOpen" class="p-2 rounded-md text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path :class="{ 'hidden': mobileOpen, 'inline-flex': !mobileOpen }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{ 'hidden': !mobileOpen, 'inline-flex': mobileOpen }" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{ 'block': mobileOpen, 'hidden': !mobileOpen }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('messages.dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('plans.index')" :active="request()->routeIs('plans.*')">
                {{ __('messages.plans') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">
                {{ __('messages.customers') }}
            </x-responsive-nav-link>
        </div>
        
        <!-- Secondary Links in Mobile -->
        <div class="pt-2 pb-3 space-y-1 px-4 border-t border-gray-200 dark:border-gray-700">
            <x-responsive-nav-link :href="route('lines.all')" :active="request()->routeIs('lines.*')">
                {{ __('messages.lines') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')">
                {{ __('messages.invoices') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                👥 {{ __('messages.users') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('permissions.index')" :active="request()->routeIs('permissions.*')">
                🔑 {{ __('messages.permissions') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('change-logs.index')" :active="request()->routeIs('change-logs.*')">
                📝 {{ __('messages.change_log') }}
            </x-responsive-nav-link>
            
            <!-- Requests in Mobile -->
            <div x-data="{ requestsOpen: false }" class="border-t border-gray-200 dark:border-gray-700 pt-2">
                <button @click="requestsOpen = !requestsOpen" class="w-full flex justify-between items-center px-3 py-2 text-left text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <span>📂 {{ __('messages.requests') }}</span>
                    <svg class="h-5 w-5" :class="{ 'rotate-180': requestsOpen }" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="requestsOpen" class="space-y-1 pl-4">
                    <x-responsive-nav-link :href="route('requests.all')">
                        📄 {{ __('messages.all_requests') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('requests.summary')">
                        📊 {{ __('messages.summary') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('requests.history')">
                        🕓 {{ __('messages.history') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        </div>

        <!-- User Info and Actions -->
        <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-700 px-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200 truncate">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</div>
                </div>
                
                <!-- Language Switch in Mobile -->
                <a href="{{ route('lang.switch', $newLocale) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 transition p-2">
                    🌐 {{ strtoupper($newLocale) }}
                </a>
            </div>
            
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('messages.profile') }}
                </x-responsive-nav-link>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('messages.logout') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

    // Change the icons inside the button based on previous settings
    if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        if(themeToggleLightIcon) themeToggleLightIcon.classList.remove('hidden');
    } else {
        if(themeToggleDarkIcon) themeToggleDarkIcon.classList.remove('hidden');
    }

    function toggleTheme() {
        // toggle icons inside button
        themeToggleDarkIcon.classList.toggle('hidden');
        themeToggleLightIcon.classList.toggle('hidden');

        // if set via local storage previously
        if (localStorage.getItem('theme')) {
            if (localStorage.getItem('theme') === 'light') {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        // if NOT set via local storage previously
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