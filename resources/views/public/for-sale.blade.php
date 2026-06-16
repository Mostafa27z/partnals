<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.for_sale_public_title') }} — {{ config('app.name') }}</title>
    <meta name="description" content="{{ __('messages.for_sale_public_desc') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        body { font-family: 'Inter', 'Cairo', sans-serif; }
        [dir="rtl"] body { font-family: 'Cairo', 'Inter', sans-serif; }

        .hero-glow {
            background: radial-gradient(ellipse at 50% 0%, rgba(99,102,241,0.15) 0%, transparent 60%);
        }
        .dark .hero-glow {
            background: radial-gradient(ellipse at 50% 0%, rgba(99,102,241,0.08) 0%, transparent 60%);
        }
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -12px rgba(99,102,241,0.15);
        }
        .dark .card-hover:hover {
            box-shadow: 0 20px 40px -12px rgba(99,102,241,0.08);
        }
        .provider-badge {
            background: linear-gradient(135deg, var(--badge-from), var(--badge-to));
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fadeInUp 0.5s ease-out forwards;
            opacity: 0;
        }
    </style>
</head>
<body class="min-h-screen bg-[#f8fafc] dark:bg-[#0a0f1a] text-gray-800 dark:text-gray-200 antialiased">

    {{-- Theme Toggle --}}
    <button onclick="document.documentElement.classList.toggle('dark'); localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';"
        class="fixed top-5 {{ app()->getLocale() === 'ar' ? 'left-5' : 'right-5' }} z-50 w-11 h-11 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg flex items-center justify-center text-lg hover:scale-110 transition-all duration-200 cursor-pointer">
        <span class="dark:hidden">🌙</span>
        <span class="hidden dark:inline">☀️</span>
    </button>

    <div class="hero-glow">
        {{-- Header --}}
        <div class="max-w-5xl mx-auto px-6 pt-16 pb-8">
            <div class="text-center animate-in">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-100 dark:border-indigo-800/40 mb-6">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider">{{ __('messages.available_now') }}</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4 leading-tight">
                    📱 {{ __('messages.for_sale_public_title') }}
                </h1>
                <p class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto font-medium">
                    {{ __('messages.for_sale_public_desc') }}
                </p>
            </div>

            {{-- Stats Bar --}}
            <div class="flex justify-center mt-8 animate-in" style="animation-delay: 0.15s">
                <div class="inline-flex items-center gap-6 px-8 py-4 rounded-2xl bg-white dark:bg-gray-800/80 border border-gray-100 dark:border-gray-700/50 shadow-sm backdrop-blur-sm">
                    <div class="text-center">
                        <p class="text-2xl font-black text-indigo-600 dark:text-indigo-400">{{ $lines->count() }}</p>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">{{ __('messages.lines_available') }}</p>
                    </div>
                    <div class="w-px h-10 bg-gray-200 dark:bg-gray-700"></div>
                    <div class="text-center">
                        <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
                            {{ $lines->count() > 0 ? number_format($lines->min('sale_price'), 0) : '0' }}
                        </p>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">{{ __('messages.starting_from') }}</p>
                    </div>
                </div>
            </div>

            {{-- Filter Bar --}}
            <div class="mt-8 animate-in" style="animation-delay: 0.1s">
                <form action="{{ route('public.for-sale') }}" method="GET" class="bg-white/80 dark:bg-gray-800/60 p-4 rounded-3xl border border-white dark:border-gray-700/30 shadow-xl backdrop-blur-md flex flex-wrap items-end gap-4 max-w-4xl mx-auto">
                    <div class="flex-1 min-w-[150px]">
                        <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1.5 px-1">{{ __('messages.provider') }}</label>
                        <select id="provider-select" name="provider" class="w-full h-12 bg-gray-50 dark:bg-gray-900/50 border-none rounded-2xl px-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 transition-all text-gray-900 dark:text-white">
                            <option value="">{{ __('messages.all_providers') }}</option>
                            @foreach($providers as $p)
                                <option value="{{ $p }}" {{ request('provider') == $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-[150px]">
                        <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1.5 px-1">{{ __('messages.plan') }}</label>
                        <select id="plan-select" name="plan_id" class="w-full h-12 bg-gray-50 dark:bg-gray-900/50 border-none rounded-2xl px-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 transition-all text-gray-900 dark:text-white">
                            <option value="">{{ __('messages.all_plans') }}</option>
                            @foreach($plans as $p)
                                <option value="{{ $p->id }}" {{ request('plan_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="h-12 px-8 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-sm transition-all shadow-lg shadow-indigo-600/25 active:scale-95">
                        🔍 {{ __('messages.search') }}
                    </button>
                    <a id="export-link" data-base-url="{{ route('public.for-sale.export') }}" href="{{ route('public.for-sale.export', request()->only(['provider', 'plan_id'])) }}" class="h-12 px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black text-sm transition-all shadow-lg shadow-emerald-600/25 active:scale-95">
                        ⬇️ تصدير إكسل
                    </a>
                    @if(request()->anyFilled(['provider', 'plan_id']))
                        <a href="{{ route('public.for-sale') }}" class="h-12 w-12 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 rounded-2xl font-black text-sm flex items-center justify-center transition-all hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600">
                            ✖️
                        </a>
                    @endif
                </form>
            </div>
        </div>
    <script>
        const providerSelect = document.getElementById('provider-select');
        const planSelect = document.getElementById('plan-select');
        const exportLink = document.getElementById('export-link');

        const updateExportHref = () => {
            if (!exportLink) return;

            const params = new URLSearchParams();
            if (providerSelect?.value) {
                params.set('provider', providerSelect.value);
            }
            if (planSelect?.value) {
                params.set('plan_id', planSelect.value);
            }

            exportLink.href = exportLink.dataset.baseUrl + (params.toString() ? `?${params.toString()}` : '');
        };

        providerSelect?.addEventListener('change', async function () {
            const provider = this.value;
            const params = new URLSearchParams();
            if (provider) params.set('q', provider);

            const response = await fetch(`/ajax/plans/by-provider?${params.toString()}`);
            if (!response.ok) {
                return;
            }

            const plans = await response.json();
            planSelect.innerHTML = '<option value="">{{ __('messages.all_plans') }}</option>';

            plans.forEach(plan => {
                const option = document.createElement('option');
                option.value = plan.id;
                option.textContent = plan.name;
                planSelect.appendChild(option);
            });

            planSelect.value = '';
            updateExportHref();
        });

        planSelect?.addEventListener('change', updateExportHref);
    </script>

        {{-- Lines Grid --}}
        <div class="max-w-5xl mx-auto px-6 pb-20">
            @if($lines->isEmpty())
                <div class="text-center py-20 animate-in" style="animation-delay: 0.2s">
                    <div class="w-24 h-24 mx-auto bg-gray-100 dark:bg-gray-800 rounded-3xl flex items-center justify-center text-5xl mb-6">📭</div>
                    <h3 class="text-xl font-bold text-gray-500 dark:text-gray-400">{{ __('messages.no_lines_for_sale') }}</h3>
                    <p class="text-gray-400 dark:text-gray-500 mt-2">{{ __('messages.check_back_later') }}</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($lines as $index => $line)
                        <div class="card-hover bg-white dark:bg-gray-800/80 rounded-2xl border border-gray-100 dark:border-gray-700/50 overflow-hidden animate-in backdrop-blur-sm"
                             style="animation-delay: {{ 0.2 + ($index * 0.05) }}s">
                            {{-- Card Header --}}
                            <div class="p-5 pb-4">
                                <div class="flex items-center justify-between mb-4">
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
                                <div class="flex items-center gap-3 mb-1">
                                    <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-700/50 flex items-center justify-center text-lg">
                                        📞
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">{{ __('messages.phone_number') }}</p>
                                        <p class="text-lg font-black text-gray-900 dark:text-white font-mono tracking-wide" dir="ltr">
                                            {{ $line->phone_number }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Plan Details --}}
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-lg">
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
                                    <span class="text-xl font-black text-indigo-600 dark:text-indigo-400">
                                        {{ $line->sale_price ? number_format($line->sale_price, 0) : '-' }}
                                        <span class="text-sm font-bold">{{ __('messages.currency') }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Footer --}}
    <div class="border-t border-gray-100 dark:border-gray-800 py-8">
        <p class="text-center text-sm text-gray-400 dark:text-gray-500 font-medium">
            © {{ date('Y') }} {{ config('app.name') }} — {{ __('messages.all_rights_reserved') }}
        </p>
    </div>
</body>
</html>
