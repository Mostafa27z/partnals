<x-app-layout>  
    <x-slot name="header">  
        <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 flex items-center gap-3">
            <span class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-violet-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none">
                <span class="text-white text-lg">📞</span>
            </span>
            {{ __('messages.line_details') }}
        </h2>  
    </x-slot>  

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">  
        <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 space-y-8">  
            
            <!-- Details Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-gray-700 dark:text-gray-300">  
                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.id') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->id }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.customer_id') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->customer_id ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.attached_date') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->attached_at ?? '-' }}</p>
                </div>

                <div class="bg-indigo-50/50 dark:bg-indigo-900/10 p-4 rounded-xl border border-indigo-100 dark:border-indigo-800/30">
                    <p class="text-[10px] font-black uppercase tracking-widest text-indigo-500 dark:text-indigo-400 mb-1">{{ __('messages.phone_number') }}</p>
                    <p class="font-mono font-black text-indigo-700 dark:text-indigo-300 text-lg">{{ $line->phone_number }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.serial_number') }}</p>
                    <p class="font-mono font-bold text-gray-800 dark:text-gray-200">{{ $line->serial_number ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.secondary_phone') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->second_phone ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.provider') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->provider }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.status') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->status ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.offer_name') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->offer_name ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.branch_name') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->branch_name ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.employee_name') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->employee_name ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.gcode') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->gcode ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.distributor') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->distributor->name ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.line_type') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">
                        {{ $line->line_type === 'prepaid' ? __('messages.prepaid') : __('messages.postpaid') }}
                    </p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.plan') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->plan->name ?? __('messages.not_specified') }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.package') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->package ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.last_invoice_date') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->last_invoice_date ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.payment_date') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->payment_date ?? '-' }}</p>
                </div>

                <div class="bg-indigo-50/30 dark:bg-indigo-900/10 p-4 rounded-xl border border-indigo-100 dark:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-indigo-500 dark:text-indigo-400 mb-1">{{ __('messages.provider_day') ?? 'يوم تشغيل المزود' }}</p>
                    <p class="font-black text-indigo-700 dark:text-indigo-300">
                        {{ $line->providerData->invoice_day ?? '-' }} {{ __('messages.day_of_month') ?? 'من الشهر' }}
                    </p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors col-span-1 sm:col-span-2 lg:col-span-3">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.notes') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->notes ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.added_by') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->addedBy->name ?? __('messages.unknown') }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.created_at') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->created_at->format('Y-m-d H:i') }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.updated_at') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->updated_at->format('Y-m-d H:i') }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.for_sale') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">
                        {{ $line->for_sale ? __('messages.yes') : __('messages.no') }}
                    </p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.sale_price') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->sale_price ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors col-span-1 sm:col-span-2 lg:col-span-3">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.deleted_at') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">
                        {{ $line->deleted_at ? $line->deleted_at->format('Y-m-d H:i') : __('messages.not_deleted') }}
                    </p>
                </div>
            </div>

            <!-- Back Button -->
            @if($line->customer)  
                <div class="pt-4">  
                    <a href="{{ route('customers.show', $line->customer) }}"  
                       class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold text-sm border border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all shadow-sm">  
                        🔙 {{ __('messages.back_to_customer_details') }}
                    </a>  
                </div>  
            @endif  
        </div>  
    </div>  
</x-app-layout>
