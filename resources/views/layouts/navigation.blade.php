@php
    $unreadCount = Auth::check() ? Auth::user()->unreadNotifications->count() : 0;
    $notifications = Auth::check() ? Auth::user()->unreadNotifications()->latest()->take(5)->get() : collect();
    $newLocale = app()->getLocale() === 'ar' ? 'en' : 'ar';
@endphp

<nav x-data="{ mobileOpen: false }" class="sticky top-0 z-[100] w-full bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 shadow-sm transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Nav Layout -->
        <div class="flex justify-between h-20 items-center gap-4">
            <!-- Start: Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('dashboard') }}" class="group flex items-center transition-all duration-300">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-violet-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none transition-transform group-hover:rotate-12">
                            <span class="text-white font-black text-xl">P</span>
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-gray-800 dark:text-white uppercase hidden md:inline-block">
                            {{ config('app.name', 'Partnals') }}
                        </span>
                    </div>
                </a>
            </div>

            <!-- Center: Navigation Links (Desktop) -->
            <div class="hidden lg:flex items-center bg-gray-50/50 dark:bg-gray-900/40 backdrop-blur-md px-2 py-1.5 rounded-2xl border border-gray-100/50 dark:border-gray-700/50 shadow-inner">
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

                <!-- More Dropdown -->
                <div x-data="{ moreOpen: false }" class="relative">
                    <button @click="moreOpen = !moreOpen" 
                            class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all duration-200 text-sm font-bold">
                        <span>{{ __('messages.more') }}</span>
                        <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': moreOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="moreOpen" 
                         x-cloak
                         @click.away="moreOpen = false" 
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 scale-95 translate-y-[-10px]" 
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                         class="absolute left-1/2 -translate-x-1/2 mt-4 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 z-50 w-64 overflow-hidden">
                        <div class="p-2 space-y-0.5">
                            <x-dropdown-link :href="route('providers.index')" class="rounded-xl">
                                <span class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">📡</span>
                                    <span>{{ __('messages.providers') ?? 'المزودين' }}</span>
                                </span>
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('users.index')" class="rounded-xl">
                                <span class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">👥</span>
                                    <span>{{ __('messages.users') }}</span>
                                </span>
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('permissions.index')" class="rounded-xl">
                                <span class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">🔑</span>
                                    <span>{{ __('messages.permissions') }}</span>
                                </span>
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('roles.index')" class="rounded-xl">
                                <span class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">🛡️</span>
                                    <span>{{ __('messages.manage_roles') }}</span>
                                </span>
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('change-logs.index')" class="rounded-xl">
                                <span class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">📝</span>
                                    <span>{{ __('messages.change_log') }}</span>
                                </span>
                            </x-dropdown-link>
                            
                            <div class="h-[1px] bg-gray-100 dark:bg-gray-700 my-2 mx-2"></div>
                            
                            <x-dropdown-link :href="route('accounting.dashboard')" class="rounded-xl">
                                <span class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">💰</span>
                                    <span>{{ __('messages.accounting') }}</span>
                                </span>
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('hr.dashboard')" class="rounded-xl">
                                <span class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">🤵</span>
                                    <span>{{ __('messages.hr') }}</span>
                                </span>
                            </x-dropdown-link>
                            
                            <div class="h-[1px] bg-gray-100 dark:bg-gray-700 my-2 mx-2"></div>
                            
                            <x-dropdown-link :href="route('requests.all')" class="rounded-xl">
                                <span class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">📄</span>
                                    <span>{{ __('messages.all_requests') }}</span>
                                </span>
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('requests.history')" class="rounded-xl">
                                <span class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">📜</span>
                                    <span>{{ __('messages.requests_history') }}</span>
                                </span>
                            </x-dropdown-link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- End: Actions & Profile -->
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Notification Bell -->
                <div x-data="{ notifOpen: false }" class="relative">
                    <button @click="notifOpen = !notifOpen" class="relative p-2.5 text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-700 hover:shadow-sm focus:outline-none rounded-xl transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if($unreadCount > 0)
                            <span class="absolute top-2.5 right-2.5 flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                            </span>
                        @endif
                    </button>

                    <div x-show="notifOpen" 
                         x-cloak
                         @click.away="notifOpen = false" 
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 scale-95 translate-y-[-10px]" 
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
                         class="absolute right-0 ltr:right-0 rtl:left-0 mt-4 w-80 bg-white/95 dark:bg-gray-800/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 z-50 overflow-hidden">
                        <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/50">
                            <span class="font-bold text-sm text-gray-700 dark:text-gray-200">الإشعارات</span>
                            @if($unreadCount > 0)
                                <a href="{{ route('notifications.markAllAsRead') }}" class="text-[11px] text-indigo-600 dark:text-indigo-400 hover:underline font-black uppercase">Mark all</a>
                            @endif
                        </div>
                        <div class="max-h-[400px] overflow-y-auto custom-scrollbar">
                            @forelse($notifications as $notification)
                                <a href="{{ route('notifications.markAsRead', $notification->id) }}" class="flex items-start gap-4 p-4 hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors border-b border-gray-50 dark:border-gray-700/50 {{ $notification->unread() ? 'bg-indigo-50/20 dark:bg-indigo-900/10' : '' }}">
                                    <div class="flex-shrink-0 mt-0.5">
                                        <div class="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 p-2 rounded-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed font-bold">{{ $notification->data['message'] ?? 'إشعار جديد' }}</p>
                                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1 uppercase tracking-wider font-bold">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if($notification->unread())
                                        <div class="w-2 h-2 bg-indigo-600 rounded-full mt-2 ring-4 ring-indigo-50 dark:ring-indigo-900/20"></div>
                                    @endif
                                </a>
                            @empty
                                <div class="p-12 text-center">
                                    <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900/50 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-400">No notifications</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Theme Toggle -->
                <button onclick="toggleTheme()" class="text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-700 hover:shadow-sm focus:outline-none rounded-xl text-sm p-3 transition-all duration-300">
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                </button>

                <!-- Language Selector -->
                <div class="hidden sm:block">
                    <a href="{{ route('lang.switch', $newLocale) }}"
                       class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gray-100/50 dark:bg-gray-900/50 hover:bg-white dark:hover:bg-gray-700 transition-all border border-transparent hover:border-gray-100 dark:hover:border-gray-600 shadow-sm text-xs font-black uppercase">
                        <span class="text-sm">🌐</span>
                        <span>{{ strtoupper($newLocale) }}</span>
                    </a>
                </div>

                <div class="h-8 w-[1px] bg-gray-200/50 dark:bg-gray-700/50 mx-1 hidden sm:block"></div>

                <!-- User Profile -->
                <x-dropdown align="right" width="60">
                    <x-slot name="trigger">
                        <button class="flex items-center p-1 rounded-2xl bg-gray-50/50 dark:bg-gray-900/50 border border-transparent hover:border-gray-100 dark:hover:border-gray-700 transition-all group">
                            <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-xl flex items-center justify-center font-black text-xs text-white shadow-lg group-hover:scale-105 transition-transform">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="hidden md:block ltr:ml-3 rtl:mr-3 text-start rtl:text-right">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Supervisor</p>
                                <p class="text-xs font-black text-gray-800 dark:text-white leading-none truncate max-w-[80px]">{{ Auth::user()->name }}</p>
                            </div>
                            <div class="ltr:ml-2 rtl:mr-2 ltr:mr-1 rtl:ml-1 text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-900/30">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Account Info</p>
                            <p class="text-sm font-black text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate mt-0.5">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="p-2">
                            <x-dropdown-link :href="route('profile.edit')" class="rounded-xl flex items-center gap-3">
                                <span class="text-lg">👤</span>
                                <span class="font-bold text-gray-700 dark:text-gray-300">{{ __('messages.profile') }}</span>
                            </x-dropdown-link>
                            
                            <div class="h-[1px] bg-gray-100 dark:bg-gray-700 my-2 mx-2"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="rounded-xl flex items-center gap-3 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20">
                                    <span class="text-lg">🚪</span>
                                    <span class="font-black">{{ __('messages.logout') }}</span>
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>

                <!-- Mobile Trigger -->
                <div class="lg:hidden flex items-center">
                    <button @click="mobileOpen = !mobileOpen" class="p-2.5 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-500 hover:text-indigo-600 transition-all">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/><path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>
        </div>
    <!-- Mobile Menu Overlay -->
    <div x-show="mobileOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="lg:hidden absolute top-20 inset-x-0 z-40 bg-white/95 dark:bg-gray-800/95 backdrop-blur-xl border-b border-gray-100 dark:border-gray-700 shadow-2xl overflow-hidden rounded-b-3xl">
        
        <div class="p-6 pt-10">
            <!-- Mobile Notifications -->
            <div class="mb-8 overflow-hidden rounded-[2rem] bg-indigo-50/50 dark:bg-indigo-900/10 border border-indigo-100/50 dark:border-indigo-800/20">
                <div class="p-5 flex items-center justify-between">
                    <h3 class="text-xs font-black text-indigo-900 dark:text-indigo-100 uppercase tracking-widest flex items-center gap-2">
                        <span class="relative flex h-2 w-2">
                            @if($unreadCount > 0)
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                            @else
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-400"></span>
                            @endif
                        </span>
                        الإشعارات {{ $unreadCount > 0 ? "($unreadCount)" : '' }}
                    </h3>
                    @if($unreadCount > 0)
                        <a href="{{ route('notifications.markAllAsRead') }}" class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 uppercase">تحديد الكل</a>
                    @endif
                </div>
                <div class="max-h-[200px] overflow-y-auto custom-scrollbar px-2 pb-2">
                    @forelse($notifications as $notification)
                        <a href="{{ route('notifications.markAsRead', $notification->id) }}" class="flex items-start gap-3 p-3 rounded-2xl mb-1 hover:bg-white dark:hover:bg-gray-800 transition-all {{ $notification->unread() ? 'bg-white/50 dark:bg-gray-800/50 shadow-sm' : '' }}">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shrink-0 text-xs">🔔</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[11px] font-bold text-gray-800 dark:text-gray-200 leading-tight truncate">{{ $notification->data['message'] ?? 'إشعار جديد' }}</p>
                                <p class="text-[9px] text-gray-400 font-bold uppercase mt-0.5">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </a>
                    @empty
                        <div class="p-4 text-center">
                            <p class="text-[10px] font-bold text-gray-400">لا توجد إشعارات جديدة</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Navigation Group -->
            <div class="space-y-1">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-3 px-3">Navigation</p>
                <div class="grid grid-cols-1 gap-1">
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="rounded-2xl">
                        <span class="flex items-center gap-3">
                            <span class="text-xl">🏠</span>
                            <span>{{ __('messages.dashboard') }}</span>
                        </span>
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('plans.index')" :active="request()->routeIs('plans.*')" class="rounded-2xl">
                        <span class="flex items-center gap-3">
                            <span class="text-xl">📋</span>
                            <span>{{ __('messages.plans') }}</span>
                        </span>
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')" class="rounded-2xl">
                        <span class="flex items-center gap-3">
                            <span class="text-xl">👥</span>
                            <span>{{ __('messages.customers') }}</span>
                        </span>
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('lines.all')" :active="request()->routeIs('lines.*')" class="rounded-2xl">
                        <span class="flex items-center gap-3">
                            <span class="text-xl">📞</span>
                            <span>{{ __('messages.lines') }}</span>
                        </span>
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')" class="rounded-2xl">
                        <span class="flex items-center gap-3">
                            <span class="text-xl">🧾</span>
                            <span>{{ __('messages.invoices') }}</span>
                        </span>
                    </x-responsive-nav-link>
                </div>
            </div>

            <!-- Management Group -->
            <div class="space-y-1 border-t border-gray-100 dark:border-gray-700/50 pt-6">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-3 px-3">Management</p>
                <div class="grid grid-cols-2 gap-2">
                    <x-responsive-nav-link :href="route('users.index')" class="rounded-2xl border border-gray-50 dark:border-gray-700/30">
                        <div class="flex flex-col gap-1 py-1">
                            <span class="text-lg">👥</span>
                            <span class="text-xs font-bold">{{ __('messages.users') }}</span>
                        </div>
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('permissions.index')" class="rounded-2xl border border-gray-50 dark:border-gray-700/30">
                        <div class="flex flex-col gap-1 py-1">
                            <span class="text-lg">🔑</span>
                            <span class="text-xs font-bold whitespace-nowrap">{{ __('messages.permissions') }}</span>
                        </div>
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('roles.index')" class="rounded-2xl border border-gray-50 dark:border-gray-700/30">
                        <div class="flex flex-col gap-1 py-1">
                            <span class="text-lg">🛡️</span>
                            <span class="text-xs font-bold whitespace-nowrap">{{ __('messages.manage_roles') }}</span>
                        </div>
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('accounting.dashboard')" class="rounded-2xl border border-gray-50 dark:border-gray-700/30">
                        <div class="flex flex-col gap-1 py-1">
                            <span class="text-lg">💰</span>
                            <span class="text-xs font-bold">{{ __('messages.accounting') }}</span>
                        </div>
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('hr.dashboard')" class="rounded-2xl border border-gray-50 dark:border-gray-700/30">
                        <div class="flex flex-col gap-1 py-1">
                            <span class="text-lg">🤵</span>
                            <span class="text-xs font-bold">{{ __('messages.hr') }}</span>
                        </div>
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('requests.history')" class="rounded-2xl border border-gray-50 dark:border-gray-700/30">
                        <div class="flex flex-col gap-1 py-1">
                            <span class="text-lg">📜</span>
                            <span class="text-xs font-bold">{{ __('messages.requests_history') }}</span>
                        </div>
                    </x-responsive-nav-link>
                </div>
            </div>

            <!-- User Group -->
            <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700/50">
                <div class="flex items-center gap-4 px-3 mb-6">
                    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 w-12 h-12 rounded-2xl flex items-center justify-center text-white font-bold text-lg shadow-lg">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-black text-gray-800 dark:text-white leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate leading-tight mt-0.5">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-2">
                    <x-responsive-nav-link :href="route('profile.edit')" class="rounded-2xl bg-gray-50 dark:bg-gray-900/50">
                        <span class="flex items-center gap-3">
                            <span>👤</span>
                            <span class="font-bold">{{ __('messages.profile') }}</span>
                        </span>
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-rose-600 bg-rose-50 dark:bg-rose-900/20 font-bold transition-all active:scale-95">
                            <span>🚪</span>
                            <span>{{ __('messages.logout') }}</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Language Switch in Mobile Footer -->
            <div class="flex justify-center pt-4">
                <a href="{{ route('lang.switch', $newLocale) }}" class="flex items-center gap-2 px-6 py-2 rounded-full bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 font-black text-xs">
                    <span>🌐 Switch to {{ strtoupper($newLocale) }}</span>
                </a>
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