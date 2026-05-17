<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-black text-3xl text-gray-900 dark:text-white leading-tight">
                    {{ __('messages.dashboard') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">{{ __('messages.welcome_back') }}، {{ Auth::user()->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-10">
        {{-- Welcome Hero Section --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-indigo-700 to-violet-800 rounded-[2.5rem] p-1 shadow-2xl">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-indigo-400/20 rounded-full blur-3xl"></div>
            
            <div class="relative bg-white/5 backdrop-blur-sm rounded-[2.2rem] p-8 md:p-12 flex flex-col md:flex-row items-center justify-between gap-8 border border-white/10">
                <div class="max-w-xl text-center md:text-start rtl:md:text-right">
                    <span class="inline-block px-4 py-1.5 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-white text-[10px] font-black uppercase tracking-widest mb-6">
                        {{ __('messages.partner_management_system') }} • {{ __('messages.version') }} 2.1
                    </span>
                    <h3 class="text-3xl md:text-4xl font-black text-white mb-4 leading-tight">
                        {{ __('messages.welcome_to_env') }}
                    </h3>
                    <p class="text-indigo-100 text-lg leading-relaxed opacity-90 font-medium">
                        {{ __('messages.dashboard_desc') }}
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <div class="w-32 h-32 md:w-40 md:h-40 bg-white/10 backdrop-blur-md rounded-3xl border border-white/20 flex items-center justify-center text-6xl shadow-2xl">
                        🚀
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Stats Grid --}}
        <!-- <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
                $stats = [
                    ['title' => __('messages.active_customers'), 'value' => number_format($activeCustomersCount), 'icon' => '👥', 'color' => 'blue', 'label' => __('messages.total_registered')],
                    ['title' => __('messages.pending_requests'), 'value' => number_format($pendingRequestsCount), 'icon' => '📦', 'color' => 'amber', 'label' => __('messages.waiting_process')],
                    ['title' => __('messages.new_lines'), 'value' => number_format($newLinesCount), 'icon' => '📞', 'color' => 'indigo', 'label' => __('messages.added_this_month')],
                ];
            @endphp

            @foreach($stats as $stat)
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-{{ $stat['color'] }}-50 dark:bg-{{ $stat['color'] }}-900/30 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                            {{ $stat['icon'] }}
                        </div>
                        <span class="text-[10px] font-black text-indigo-600 bg-indigo-50 dark:bg-indigo-900/30 px-2.5 py-1 rounded-lg">
                            {{ $stat['label'] }}
                        </span>
                    </div>
                    <h4 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ $stat['title'] }}</h4>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $stat['value'] }}</p>
                </div>
            @endforeach
        </div> -->

        {{-- For Sale Link Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center text-2xl">
                        🏷️
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ __('messages.for_sale_public_title') }}</h4>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ __('messages.copy_for_sale_link') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <input id="for-sale-url" type="text" readonly
                        value="{{ route('public.for-sale') }}"
                        class="flex-1 sm:w-72 px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-600 dark:text-gray-300 font-mono truncate focus:outline-none" dir="ltr">
                    <button onclick="navigator.clipboard.writeText(document.getElementById('for-sale-url').value).then(() => { const btn = this; btn.innerHTML = '✅'; setTimeout(() => btn.innerHTML = '📋', 1500); })"
                        class="shrink-0 w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-100 dark:border-indigo-800/40 flex items-center justify-center text-lg hover:scale-110 transition-all cursor-pointer"
                        title="{{ __('messages.copy_for_sale_link') }}">
                        📋
                    </button>
                    <a href="{{ route('public.for-sale') }}"
                        class="shrink-0 w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-800/40 flex items-center justify-center text-lg hover:scale-110 transition-all"
                        title="{{ __('messages.view') }}">
                        🔗
                    </a>
                </div>
            </div>
        </div>

        {{-- Main Sections --}}
        <div class="grid lg:grid-cols-2 gap-8">
            <div class="space-y-8">

            <div class="space-y-8">
                {{-- Side Card: Tips --}}
                <!-- <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-[2.5rem] p-8 border border-indigo-100 dark:border-indigo-800/50">
                    <div class="w-12 h-12 rounded-2xl bg-white dark:bg-gray-800 shadow-sm flex items-center justify-center text-2xl mb-6">
                        💡
                    </div>
                    <h4 class="text-lg font-black text-indigo-900 dark:text-indigo-100 mb-2">نصيحة اليوم</h4>
                    <p class="text-indigo-700 dark:text-indigo-300 text-sm leading-relaxed font-medium">
                        استخدم اختصارات لوحة المفاتيح للوصول السريع إلى الطلبات الجديدة وتوفير الوقت!
                    </p>
                </div> -->

                {{-- Side Card: Support --}}
                <!-- <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-8 border border-gray-100 dark:border-gray-700 shadow-sm">
                    <h4 class="text-lg font-black text-gray-800 dark:text-white mb-4">هل تحتاج مساعدة؟</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 font-medium">نحن هنا لمساعدتك في أي استفسار يخص النظام.</p>
                    <button class="w-full py-3.5 bg-gray-900 dark:bg-indigo-600 text-white rounded-2xl text-sm font-black shadow-lg transition-all active:scale-95">
                        فتح تذكرة دعم
                    </button>
                </div> -->
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
        <script>
            AOS.init({
                duration: 600,
                once: true
            });
        </script>
    @endpush
</x-app-layout>
