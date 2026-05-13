<!-- resources/views/admin/lines/index.blade.php --> 
<x-app-layout> 
    <x-slot name="header"> 
        <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 flex items-center gap-3"> 
            <span class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-violet-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none">
                <span class="text-white text-lg">📞</span>
            </span>
            {{ __('messages.customer_lines_for', ['name' => $customer->full_name]) }}
        </h2> 
    </x-slot> 
 
    <div class="py-8 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8"> 
        <div class="mb-6 text-right"> 
            <a href="{{ route('customers.lines.create', $customer) }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/25 hover:-translate-y-0.5">
                ➕ {{ __('messages.add_new_line') }}
            </a> 
        </div> 
 
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden"> 
            @if ($customer->lines->count()) 
                <div class="overflow-x-auto">
                    <table class="min-w-full text-center text-sm"> 
                        <thead> 
                            <tr class="bg-gray-50/80 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700"> 
                                <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.phone_number') }}</th> 
                                <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.line_type') }}</th> 
                                <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.provider') }}</th> 
                                <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.plan') }}</th> 
                                <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.status') }}</th> 
                                <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.payment_date') }}</th> 
                                <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.actions') }}</th> 
                            </tr> 
                        </thead> 
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50"> 
                            @foreach($customer->lines as $line) 
                                <tr class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200"> 
                                    <td class="px-4 py-3.5 font-mono font-bold text-gray-800 dark:text-gray-200 whitespace-nowrap">{{ $line->phone_number }}</td> 
                                    <td class="px-4 py-3.5 text-gray-600 dark:text-gray-400 whitespace-nowrap"> 
                                        {{ $line->line_type == 'prepaid' ? __('messages.prepaid') : __('messages.postpaid') }} 
                                    </td> 
                                    <td class="px-4 py-3.5 text-gray-600 dark:text-gray-400 whitespace-nowrap">{{ $line->provider }}</td> 
                                    <td class="px-4 py-3.5 text-gray-600 dark:text-gray-400 whitespace-nowrap">{{ $line->plan->name ?? '-' }}</td> 
                                    <td class="px-4 py-3.5 whitespace-nowrap"> 
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black {{ $line->status === 'active' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' }}">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $line->status === 'active' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                            {{ $line->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                                        </span> 
                                    </td> 
                                    <td class="px-4 py-3.5 text-gray-500 dark:text-gray-400 whitespace-nowrap text-sm">{{ $line->payment_date }}</td> 
                                    <td class="px-4 py-3.5 whitespace-nowrap"> 
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('customers.lines.edit', [$customer, $line]) }}" 
                                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 font-bold text-xs hover:bg-amber-100 dark:hover:bg-amber-900/40 transition-all"> 
                                                ✏️ {{ __('messages.edit') }} 
                                            </a> 
                                        </div> 
                                    </td> 
                                </tr> 
                            @endforeach 
                        </tbody> 
                    </table> 
                </div>
            @else 
                <div class="py-12 text-center">
                    <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900/50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <span class="text-3xl">📭</span>
                    </div>
                    <p class="text-gray-400 dark:text-gray-500 font-bold">{{ __('messages.no_lines_for_customer') }}</p>
                </div> 
            @endif 
        </div> 
    </div> 
</x-app-layout>
