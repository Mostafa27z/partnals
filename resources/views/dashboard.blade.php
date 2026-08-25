<x-app-layout>
    {{-- <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-black text-3xl text-gray-900 dark:text-white leading-tight">
                    {{ __('messages.dashboard') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">{{ __('messages.welcome_back') }}، {{ Auth::user()->name }}</p>
            </div>
        </div>
    </x-slot> --}}

    <div class="space-y-10" dir="rtl">
        {{-- Search Section --}}
        <div class="p-6 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700/50">
            <h3 class="text-lg font-black text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                🔍 {{ __('messages.search') ?? 'البحث' }}
            </h3>
            <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap gap-4 items-end">
                <!-- Phone (الرقم) -->
                <div class="w-full md:flex-1 min-w-[200px]">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2">الرقم (الهاتف)</label>
                    <input type="text" name="phone" value="{{ request('phone') }}" placeholder="مثال: 01012345678" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition placeholder:text-gray-400 text-sm" />
                </div>
                <!-- National ID (الرقم القومي) -->
                <div class="w-full md:flex-1 min-w-[200px]">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2">الرقم القومي</label>
                    <input type="text" name="nid" value="{{ request('nid') }}" placeholder="14 رقم" maxlength="14" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition placeholder:text-gray-400 text-sm" />
                </div>
                <!-- Customer Name (اسم العميل) -->
                <div class="w-full md:flex-1 min-w-[200px]">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2">اسم العميل</label>
                    <input type="text" name="customer_name" value="{{ request('customer_name') }}" placeholder="الاسم الكامل" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition placeholder:text-gray-400 text-sm" />
                </div>
                
                <div class="w-full md:w-auto">
                    <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/25 cursor-pointer">
                        🔍 بحث
                    </button>
                </div>
            </form>

            {{-- Validation / Search Errors --}}
            @if(isset($searchErrors) && !empty($searchErrors))
                @foreach($searchErrors as $error)
                    <div class="mt-4 p-4 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800/30 text-rose-700 dark:text-rose-300 rounded-xl flex items-center gap-3 font-bold">
                        <span class="text-lg">⚠️</span>
                        {{ $error }}
                    </div>
                @endforeach
            @endif

            {{-- Input Length Warning --}}
            @if(isset($searchWarnings) && !empty($searchWarnings))
                @foreach($searchWarnings as $warning)
                    <div class="mt-4 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/30 text-amber-700 dark:text-amber-300 rounded-xl flex items-center gap-3 font-bold">
                        <span class="text-lg">⚠️</span>
                        {{ $warning }}
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Search Results --}}
        @if(isset($hasDashboardSearch) && $hasDashboardSearch)
            @if(isset($searchResults) && $searchResults->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                    <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">نتائج البحث</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-center text-sm">
                            <thead>
                                <tr class="bg-gray-50/80 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.phone_number') }}</th>
                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.national_id') }}</th>
                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.customer_name') }}</th>
                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">الموزع</th>
                                    <th colspan="3" class="px-4 py-4 text-center text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                                @foreach($searchResults as $line)
                                    <tr class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">
                                        <td class="px-4 py-3.5 font-mono font-bold text-gray-800 dark:text-gray-200">{{ $line->phone_number }}</td>
                                        <td class="px-4 py-3.5 text-gray-600 dark:text-gray-400">{{ $line->customer->national_id ?? '-' }}</td>
                                        <td class="px-4 py-3.5 font-medium text-gray-700 dark:text-gray-300">{{ $line->customer->full_name ?? '-' }}</td>
                                        <td class="px-4 py-3.5">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400">
                                                {{ $line->distributor?->name ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-1.5 py-3.5 whitespace-nowrap">
                                            <a href="{{ route('lines.show', $line->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400 font-bold text-xs hover:bg-indigo-100 dark:hover:bg-indigo-900/40 transition-all">
                                                👁️ {{ __('messages.view') }}
                                            </a>
                                        </td>
                                        <td class="px-1.5 py-3.5 whitespace-nowrap">
                                            <a href="{{ route('lines.edit', $line->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 font-bold text-xs hover:bg-amber-100 dark:hover:bg-amber-900/40 transition-all">
                                                ✏️ {{ __('messages.edit') }}
                                            </a>
                                        </td>
                                        <td class="px-1.5 py-3.5 whitespace-nowrap">
                                            @if($line->plan)
                                                <a href="{{ route('invoices.create', $line) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 font-bold text-xs hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition-all">
                                                    💳 {{ __('messages.pay') }}
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    <div class="p-5 border-t border-gray-100 dark:border-gray-700">
                        {{ $searchResults->appends(request()->query())->links() }}
                    </div>
                </div>
            @elseif(empty($searchErrors))
                @if(empty($searchWarnings))
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm text-center text-gray-500 dark:text-gray-400 font-bold">
                        لا توجد نتائج بحث مطابقة.
                    </div>
                @endif
            @endif
        @endif



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

        

        {{-- Section: Lines For Sale --}}
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-6 md:p-8 border border-gray-100 dark:border-gray-700/50 shadow-sm space-y-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                        📱 {{ __('messages.for_sale_public_title') }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">{{ __('messages.for_sale_public_desc') }}</p>
                </div>

                {{-- Stats Bar --}}
                <div class="flex items-center gap-6 p-5 rounded-2xl bg-gray-50 dark:bg-gray-900/30 border border-gray-100 dark:border-gray-700/50">
                    <div class="text-start">
                        <p class="text-2xl font-black text-indigo-600 dark:text-indigo-400">{{ $totalForSaleCount }}</p>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">{{ __('messages.lines_available') }}</p>
                    </div>
                    <div class="w-px h-10 bg-gray-200 dark:bg-gray-700"></div>
                    <div class="text-start">
                        <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
                            {{ $totalForSaleCount > 0 ? number_format($minSalePrice, 0) : '0' }}
                        </p>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">{{ __('messages.starting_from') }}</p>
                    </div>
                </div>
            </div>

            {{-- Filters Form --}}
            <form action="{{ route('dashboard') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                @if(request('phone')) <input type="hidden" name="phone" value="{{ request('phone') }}"> @endif
                @if(request('nid')) <input type="hidden" name="nid" value="{{ request('nid') }}"> @endif
                @if(request('customer_name')) <input type="hidden" name="customer_name" value="{{ request('customer_name') }}"> @endif

                <!-- Providers Multi-select -->
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3">{{ __('messages.providers') ?? 'المشغلين' }}</label>
                    <div class="relative inline-block w-full text-right" id="providers-select-container">
                        <button type="button" id="providers-btn" class="w-full flex items-center justify-between px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 text-gray-700 dark:text-gray-300 font-bold text-sm transition-all focus:ring-2 focus:ring-indigo-500">
                            <span id="providers-btn-text">كل المشغلين</span>
                            <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div id="providers-dropdown" class="hidden absolute right-0 z-50 mt-2 w-full max-h-60 overflow-y-auto rounded-2xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-xl p-3 space-y-1">
                            @foreach($allProviders as $p)
                                @php
                                    $isChecked = in_array($p, request('providers', []));
                                @endphp
                                <label class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition select-none">
                                    <input type="checkbox" name="providers[]" value="{{ $p }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700" {{ $isChecked ? 'checked' : '' }}>
                                    <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $p }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Plans Multi-select -->
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3">{{ __('messages.plans') ?? 'الأنظمة' }}</label>
                    <div class="relative inline-block w-full text-right" id="plans-select-container">
                        <button type="button" id="plans-btn" class="w-full flex items-center justify-between px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 text-gray-700 dark:text-gray-300 font-bold text-sm transition-all focus:ring-2 focus:ring-indigo-500">
                            <span id="plans-btn-text">كل الأنظمة</span>
                            <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div id="plans-dropdown" class="hidden absolute right-0 z-50 mt-2 w-full rounded-2xl border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-xl p-3 space-y-2">
                            <div class="px-2 pb-1">
                                <input type="text" id="plans-search" placeholder="ابحث عن نظام..." class="w-full px-3 py-1.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition placeholder:text-gray-400 text-xs" />
                            </div>
                            <div class="max-h-48 overflow-y-auto space-y-1 pr-1 custom-scrollbar">
                                @foreach($allPlans as $plan)
                                    @php
                                        $isChecked = in_array($plan->id, request('plans', []));
                                    @endphp
                                    <label class="plan-option-label flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition select-none" data-name="{{ strtolower($plan->name) }}" data-provider="{{ strtolower($plan->provider) }}">
                                        <input type="checkbox" name="plans[]" value="{{ $plan->id }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700" {{ $isChecked ? 'checked' : '' }}>
                                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $plan->name }} ({{ $plan->provider }})</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-sm transition-all shadow-lg shadow-indigo-600/25 active:scale-95 cursor-pointer">
                        🔍 {{ __('messages.search') }}
                    </button>
                    <a href="{{ route('public.for-sale.export', request()->only(['providers', 'plans'])) }}" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black text-sm transition-all shadow-lg shadow-emerald-600/25 active:scale-95 whitespace-nowrap">
                        ⬇️ تصدير إكسل
                    </a>
                    @if(request()->anyFilled(['providers', 'plans']))
                        <a href="{{ route('dashboard') }}" class="w-12 h-12 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 rounded-2xl font-black text-sm flex items-center justify-center transition-all hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 shrink-0">
                            ✖️
                        </a>
                    @endif
                </div>
            </form>

            {{-- Lines Grid --}}
            @if($forSaleLines->isEmpty())
                <div class="text-center py-20 bg-gray-50 dark:bg-gray-900/20 rounded-3xl">
                    <div class="w-24 h-24 mx-auto bg-gray-100 dark:bg-gray-800 rounded-3xl flex items-center justify-center text-5xl mb-6">📭</div>
                    <h3 class="text-xl font-bold text-gray-500 dark:text-gray-400">{{ __('messages.no_lines_for_sale') }}</h3>
                    <p class="text-gray-400 dark:text-gray-500 mt-2">{{ __('messages.check_back_later') }}</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($forSaleLines as $line)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/50 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                            {{-- Card Header --}}
                            <div class="p-5 pb-4 space-y-4">
                                <div class="flex items-center justify-between">
                                    @php
                                        $providerColors = [
                                            'Vodafone' => ['from' => '#e11d48', 'to' => '#f43f5e', 'bg' => 'bg-red-50 dark:bg-red-900/20', 'text' => 'text-red-600 dark:text-red-400'],
                                            'Etisalat' => ['from' => '#059669', 'to' => '#10b981', 'bg' => 'bg-emerald-50 dark:bg-emerald-900/20', 'text' => 'text-emerald-600 dark:text-emerald-400'],
                                            'Orange'   => ['from' => '#ea580c', 'to' => '#f97316', 'bg' => 'bg-orange-50 dark:bg-orange-900/20', 'text' => 'text-orange-600 dark:text-orange-400'],
                                            'WE'       => ['from' => '#7c3aed', 'to' => '#8b5cf6', 'bg' => 'bg-violet-50 dark:bg-violet-900/20', 'text' => 'text-violet-600 dark:text-violet-400'],
                                        ];
                                        $pc = $providerColors[$line->provider] ?? ['from' => '#6366f1', 'to' => '#818cf8', 'bg' => 'bg-indigo-50 dark:bg-indigo-900/20', 'text' => 'text-indigo-600 dark:text-indigo-400'];
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl {{ $pc['bg'] }} {{ $pc['text'] }} text-xs font-black uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full" style="background: {{ $pc['from'] }}"></span>
                                        {{ $line->provider }}
                                    </span>
                                </div>

                                {{-- Phone Number --}}
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-700/50 flex items-center justify-center text-lg shrink-0">
                                        📞
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">{{ __('messages.phone_number') }}</p>
                                        <p class="text-base font-black text-gray-900 dark:text-white font-mono tracking-wide" dir="ltr">
                                            {{ $line->phone_number }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Plan Details --}}
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-lg shrink-0">
                                        📜
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">{{ __('messages.plan') }}</p>
                                        <p class="text-sm font-black text-gray-700 dark:text-gray-300 line-clamp-1">
                                            {{ $line->plan?->name ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Price Footer --}}
                            <div class="px-5 py-4 bg-gradient-to-r from-indigo-50 to-violet-50 dark:from-indigo-900/20 dark:to-violet-900/20 border-t border-gray-100 dark:border-gray-700/50">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.sale_price') }}</span>
                                    <span class="text-lg font-black text-indigo-600 dark:text-indigo-400">
                                        {{ $line->sale_price ? number_format($line->sale_price, 0) : '-' }}
                                        <span class="text-xs font-bold">{{ __('messages.currency') }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
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

            document.addEventListener('DOMContentLoaded', function () {
                // Providers Dropdown
                const providersBtn = document.getElementById('providers-btn');
                const providersDropdown = document.getElementById('providers-dropdown');
                const providersBtnText = document.getElementById('providers-btn-text');
                const providersCheckboxes = document.querySelectorAll('#providers-dropdown input[type="checkbox"]');

                function updateProvidersLabel() {
                    const selected = Array.from(providersCheckboxes)
                        .filter(cb => cb.checked)
                        .map(cb => cb.nextElementSibling.textContent.trim());
                    
                    if (selected.length === 0) {
                        providersBtnText.textContent = "كل المشغلين";
                    } else {
                        providersBtnText.textContent = selected.join(', ');
                    }
                }

                providersBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    providersDropdown.classList.toggle('hidden');
                    plansDropdown.classList.add('hidden');
                });

                providersCheckboxes.forEach(cb => {
                    cb.addEventListener('change', updateProvidersLabel);
                });

                // Plans Dropdown
                const plansBtn = document.getElementById('plans-btn');
                const plansDropdown = document.getElementById('plans-dropdown');
                const plansBtnText = document.getElementById('plans-btn-text');
                const plansCheckboxes = document.querySelectorAll('#plans-dropdown input[type="checkbox"]');
                const plansSearch = document.getElementById('plans-search');
                const planOptionLabels = document.querySelectorAll('.plan-option-label');

                function updatePlansLabel() {
                    const selected = Array.from(plansCheckboxes)
                        .filter(cb => cb.checked)
                        .map(cb => cb.nextElementSibling.textContent.trim());
                    
                    if (selected.length === 0) {
                        plansBtnText.textContent = "كل الأنظمة";
                    } else if (selected.length <= 2) {
                        plansBtnText.textContent = selected.join(', ');
                    } else {
                        plansBtnText.textContent = `${selected.length} أنظمة مختارة`;
                    }
                }

                plansBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    plansDropdown.classList.toggle('hidden');
                    providersDropdown.classList.add('hidden');
                    if (!plansDropdown.classList.contains('hidden')) {
                        plansSearch.focus();
                    }
                });

                plansCheckboxes.forEach(cb => {
                    cb.addEventListener('change', updatePlansLabel);
                });

                // Search plans
                plansSearch.addEventListener('input', function () {
                    const query = plansSearch.value.toLowerCase().trim();
                    planOptionLabels.forEach(label => {
                        const name = label.getAttribute('data-name');
                        const provider = label.getAttribute('data-provider');
                        if (name.includes(query) || provider.includes(query)) {
                            label.style.display = 'flex';
                        } else {
                            label.style.display = 'none';
                        }
                    });
                });

                // Prevent closing when clicking inside dropdowns
                providersDropdown.addEventListener('click', function (e) {
                    e.stopPropagation();
                });
                plansDropdown.addEventListener('click', function (e) {
                    e.stopPropagation();
                });

                // Close when clicking outside
                document.addEventListener('click', function () {
                    providersDropdown.classList.add('hidden');
                    plansDropdown.classList.add('hidden');
                });

                // Run updates on load
                updateProvidersLabel();
                updatePlansLabel();
            });
        </script>
    @endpush
</x-app-layout>
