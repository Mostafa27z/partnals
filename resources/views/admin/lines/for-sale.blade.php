<x-app-layout> 
    <x-slot name="header"> 
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 md:gap-6">
            <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 flex items-center gap-3">
                <span class="w-10 h-10 bg-gradient-to-tr from-amber-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-amber-200 dark:shadow-none">
                    <span class="text-white text-lg">📦</span>
                </span>
                {{ __('messages.manage_lines_for_sale') }}
            </h2> 
            
            <div class="flex gap-3">
                <a href="{{ route('lines.all') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold text-sm border border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all shadow-sm">
                    📱 {{ __('messages.all_lines') }}
                </a>
            </div>
        </div>
    </x-slot> 

    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8 text-sm sm:text-base" dir="rtl"> 
        @if (session('success')) 
            <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/30 text-emerald-700 dark:text-emerald-300 rounded-2xl shadow-sm flex items-center gap-3 font-bold">
                <span class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg flex items-center justify-center text-lg shrink-0">✅</span>
                {{ session('success') }} 
            </div> 
        @endif 

        {{-- Excel Import Form --}}
        <div class="mb-6 p-5 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50">
            <h3 class="text-lg font-black text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                <span>📥</span> استيراد خطوط للبيع عبر الإكسيل
            </h3>
            <form action="{{ route('lines.for-sale.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
                @csrf
                <input type="file" name="file" accept=".xlsx" required
                       class="text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 dark:file:bg-indigo-900/30 file:text-indigo-700 dark:file:text-indigo-400 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/50 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 w-full sm:w-auto cursor-pointer" />
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/25 w-full sm:w-auto justify-center">
                    🚀 استيراد الملف
                </button>
                <a href="{{ route('lines.for-sale.sample') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold text-sm border border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all shadow-sm w-full sm:w-auto justify-center">
                    📄 تنزيل نموذج فارغ
                </a>
                                    <a href="{{ route('lines.for-sale.export') }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-green-100 dark:bg-green-700 text-green-700 dark:text-green-300 font-bold text-sm border border-green-200 dark:border-green-600 hover:bg-green-200 dark:hover:bg-green-600 transition-all shadow-sm w-full sm:w-auto justify-center"
                    >
                        📤 تصدير جميع الخطوط
                    </a>

            </form>
        </div>

        {{-- Filters Section --}}
        <div class="mb-6 p-5 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50">
            <form method="GET" action="{{ route('lines.for-sale') }}" class="flex flex-wrap gap-3 items-end">
                <input type="text" name="phone" value="{{ request('phone') }}" placeholder="{{ __('messages.phone_number') }}" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 w-full sm:w-40 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition placeholder:text-gray-400 text-sm" />
                <input type="text" name="nid" id="filter_nid" value="{{ request('nid') }}" placeholder="{{ __('messages.national_id') }}" maxlength="14" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 w-full sm:w-40 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition placeholder:text-gray-400 text-sm" />
                <input type="text" name="provider" value="{{ request('provider') }}" placeholder="{{ __('messages.provider') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 w-full sm:w-40 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition placeholder:text-gray-400 text-sm" />
                @if(auth()->user()->role->name !== 'موزع')
                <select name="distributor_id" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 w-full sm:w-40 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
                    <option value="">-- {{ __('messages.distributor') }} --</option>
                    @foreach($distributors as $distributor)
                        <option value="{{ $distributor->id }}" {{ request('distributor_id') == $distributor->id ? 'selected' : '' }}>
                            {{ $distributor->name }}
                        </option>
                    @endforeach
                </select>
                @endif
                <select name="plan_id" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 w-full sm:w-40 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
                    <option value="">-- {{ __('messages.plan') }} --</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/25">
                    🔍 {{ __('messages.search') }}
                </button>
                @if(request()->hasAny(['phone', 'nid', 'provider', 'distributor_id', 'plan_id', 'search']))
                    <a href="{{ route('lines.for-sale') }}" class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all text-center text-sm border border-gray-200 dark:border-gray-600">
                        {{ __('messages.clear') ?? 'تفريغ' }}
                    </a>
                @endif
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
            <form method="POST" action="{{ route('lines.mark-for-sale') }}"> 
                @csrf 

                <div class="overflow-x-auto">
                    <table class="min-w-full text-center text-sm"> 
                        <thead> 
                            <tr class="bg-gray-50/80 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700"> 
                                <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 whitespace-nowrap">📞 {{ __('messages.phone_number') }}</th> 
                                <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 whitespace-nowrap">👤 {{ __('messages.customer') }}</th> 
                                <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 whitespace-nowrap">📥 سعر الشراء</th> 
                                <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 whitespace-nowrap">💰 {{ __('messages.sale_price') }}</th> 
                                <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 whitespace-nowrap flex items-center justify-center gap-1 select-none">
                                    <input 
                                        type="checkbox" 
                                        id="select-all-for-sale"
                                        class="w-4 h-4 cursor-pointer rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500"
                                    >
                                    <label for="select-all-for-sale" class="cursor-pointer">📍 {{ __('messages.for_sale') }}</label>
                                </th> 
                                <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-emerald-600 dark:text-emerald-400 whitespace-nowrap">✅ بيع نهائي</th> 
                            </tr> 
                        </thead> 
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50"> 
                            @foreach ($lines as $line) 
                                <tr class="hover:bg-amber-50/30 dark:hover:bg-amber-900/10 transition-colors duration-200"> 
                                    <td class="px-4 py-3.5 font-mono font-bold text-gray-800 dark:text-gray-200">{{ $line->phone_number }}</td> 
                                    <td class="px-4 py-3.5 text-gray-600 dark:text-gray-400">{{ $line->customer?->full_name ?? '-' }}</td> 

                                    <td class="px-4 py-3.5"> 
                                        <input 
                                            type="number" step="0.01" name="lines[{{ $line->id }}][buy_price]" 
                                            value="{{ old("lines.$line->id.buy_price", $line->buy_price) }}" 
                                            class="w-24 px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-center text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" 
                                            placeholder="شراء"
                                        > 
                                    </td> 

                                    <td class="px-4 py-3.5"> 
                                        <input 
                                            type="number" step="0.01" name="lines[{{ $line->id }}][sale_price]" 
                                            value="{{ old("lines.$line->id.sale_price", $line->sale_price) }}" 
                                            class="w-24 px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-center text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" 
                                            placeholder="بيع"
                                        > 
                                    </td> 

                                    <td class="px-4 py-3.5"> 
                                        <input 
                                            type="checkbox" name="lines[{{ $line->id }}][selected]" value="1" 
                                            {{ $line->for_sale ? 'checked' : '' }} 
                                            class="w-5 h-5 cursor-pointer rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500 for-sale-checkbox"
                                        > 
                                    </td> 

                                    <td class="px-4 py-3.5"> 
                                        <input 
                                            type="checkbox" name="lines[{{ $line->id }}][sell_done]" value="1" 
                                            {{ $line->is_sold ? 'checked' : '' }} 
                                            class="w-5 h-5 cursor-pointer rounded border-gray-300 dark:border-gray-600 text-emerald-600 focus:ring-emerald-500"
                                        > 
                                        @if($line->is_sold)
                                            <div class="inline-flex items-center ml-2 px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-black">
                                                +{{ number_format($line->sale_price - $line->buy_price, 2) }} ج
                                            </div>
                                        @endif
                                    </td> 
                                </tr> 
                            @endforeach 
                        </tbody> 
                    </table> 
                </div>

                {{-- Pagination --}}
                <div class="p-5 border-t border-gray-100 dark:border-gray-700">
                    {{ $lines->appends(request()->query())->links() }}
                </div>

                {{-- Submit --}}
                <div class="p-5 bg-gray-50/80 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700 flex justify-end"> 
                    <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/25 hover:-translate-y-0.5"
                    > 
                         {{ __('messages.save_changes') }} 
                    </button> 
                </div> 
            </form>
        </div> 
    </div> 

    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Select/Deselect All Checkboxes
            const selectAllCheckbox = document.getElementById('select-all-for-sale');
            const checkboxes = document.querySelectorAll('.for-sale-checkbox');

            selectAllCheckbox?.addEventListener('change', function () {
                checkboxes.forEach(cb => {
                    cb.checked = selectAllCheckbox.checked;
                });
            });

            // NID Filter input formatting
            document.getElementById('filter_nid')?.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 14);
            });
        });
    </script>
    @endpush
</x-app-layout>
