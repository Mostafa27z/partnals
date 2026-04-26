<x-app-layout> 
    <x-slot name="header"> 
        <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 flex items-center gap-3">
            <span class="w-10 h-10 bg-gradient-to-tr from-amber-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-amber-200 dark:shadow-none">
                <span class="text-white text-lg">📦</span>
            </span>
            {{ __('messages.manage_lines_for_sale') }}
        </h2> 
    </x-slot> 

    <div class="max-w-6xl mx-auto py-8 sm:px-6 lg:px-8"> 
        @if (session('success')) 
            <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/30 text-emerald-700 dark:text-emerald-300 rounded-2xl shadow-sm flex items-center gap-3 font-bold">
                <span class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg flex items-center justify-center text-lg shrink-0">✅</span>
                {{ session('success') }} 
            </div> 
        @endif 

        {{-- Search Section --}}
        <div class="mb-6 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50">
            <form method="GET" action="{{ route('lines.for-sale') }}" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-grow flex items-center relative">
                    <span class="absolute right-4 text-gray-400">🔍</span>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="{{ __('messages.search_by_number') ?? 'البحث برقم الهاتف...' }}"
                        class="w-full pr-12 pl-4 py-3 rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all font-mono"
                    >
                </div>
                <button type="submit" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/25">
                    {{ __('messages.search') ?? 'بحث' }}
                </button>
                @if(request('search'))
                    <a href="{{ route('lines.for-sale') }}" class="px-8 py-3 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all text-center">
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
                                <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 whitespace-nowrap">📍 {{ __('messages.for_sale') }}</th> 
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
                                            class="w-5 h-5 cursor-pointer rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500"
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
                    {{ $lines->links() }}
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
</x-app-layout>
