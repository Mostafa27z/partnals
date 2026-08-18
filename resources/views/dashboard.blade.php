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

        {{-- Section: Lines For Sale --}}
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-6 md:p-8 border border-gray-100 dark:border-gray-700/50 shadow-sm space-y-8">
            <div>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                    📱 {{ __('messages.for_sale_public_title') }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">{{ __('messages.for_sale_public_desc') }}</p>
            </div>

            {{-- Stats Bar --}}
            <div class="flex flex-wrap gap-6 p-5 rounded-2xl bg-gray-50 dark:bg-gray-900/30 border border-gray-100 dark:border-gray-700/50">
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

            {{-- Filters Form --}}
            <form action="{{ route('dashboard') }}" method="GET" class="space-y-6">
                @if(request('phone')) <input type="hidden" name="phone" value="{{ request('phone') }}"> @endif
                @if(request('nid')) <input type="hidden" name="nid" value="{{ request('nid') }}"> @endif
                @if(request('customer_name')) <input type="hidden" name="customer_name" value="{{ request('customer_name') }}"> @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Providers Multi-select -->
                    <div>
                        <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3">{{ __('messages.providers') ?? 'المشغلين' }}</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($allProviders as $p)
                                @php
                                    $isChecked = in_array($p, request('providers', []));
                                @endphp
                                <label class="cursor-pointer select-none">
                                    <input type="checkbox" name="providers[]" value="{{ $p }}" class="hidden peer" {{ $isChecked ? 'checked' : '' }}>
                                    <span class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-bold text-gray-700 dark:text-gray-300 transition-all peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 dark:bg-gray-800 dark:peer-checked:bg-indigo-600">
                                        {{ $p }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Plans Multi-select -->
                    <div>
                        <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3">{{ __('messages.plans') ?? 'الأنظمة' }}</label>
                        <div class="flex flex-wrap gap-2 max-h-40 overflow-y-auto p-3 border border-gray-100 dark:border-gray-700/50 rounded-2xl bg-gray-50 dark:bg-gray-900/20">
                            @foreach($allPlans as $plan)
                                @php
                                    $isChecked = in_array($plan->id, request('plans', []));
                                @endphp
                                <label class="cursor-pointer select-none">
                                    <input type="checkbox" name="plans[]" value="{{ $plan->id }}" class="hidden peer" {{ $isChecked ? 'checked' : '' }}>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 dark:border-gray-700 text-xs font-bold text-gray-700 dark:text-gray-300 transition-all peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 dark:bg-gray-800 dark:peer-checked:bg-indigo-600">
                                        {{ $plan->name }} ({{ $plan->provider }})
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-sm transition-all shadow-lg shadow-indigo-600/25 active:scale-95 cursor-pointer">
                        🔍 {{ __('messages.search') }}
                    </button>
                    <a href="{{ route('public.for-sale.export', request()->only(['providers', 'plans'])) }}" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black text-sm transition-all shadow-lg shadow-emerald-600/25 active:scale-95">
                        ⬇️ تصدير إكسل
                    </a>
                    @if(request()->anyFilled(['providers', 'plans']))
                        <a href="{{ route('dashboard') }}" class="w-12 h-12 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 rounded-2xl font-black text-sm flex items-center justify-center transition-all hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600">
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
        </script>
    @endpush
</x-app-layout>
