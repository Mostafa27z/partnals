<x-app-layout>  
    <x-slot name="header">  
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
            📞 {{ __('messages.line_details') }}
        </h2>  
    </x-slot>  

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">  
        <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg space-y-8">  
            
            <!-- Details Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-gray-700 dark:text-gray-300">  
                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.id') }}</p>
                    <p class="font-semibold">{{ $line->id }}</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.customer_id') }}</p>
                    <p class="font-semibold">{{ $line->customer_id ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.attached_date') }}</p>
                    <p class="font-semibold">{{ $line->attached_at ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.phone_number') }}</p>
                    <p class="font-mono font-semibold">{{ $line->phone_number }}</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.secondary_phone') }}</p>
                    <p class="font-semibold">{{ $line->second_phone ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.provider') }}</p>
                    <p class="font-semibold">{{ $line->provider }}</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.status') }}</p>
                    <p class="font-semibold">{{ $line->status ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.offer_name') }}</p>
                    <p class="font-semibold">{{ $line->offer_name ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.branch_name') }}</p>
                    <p class="font-semibold">{{ $line->branch_name ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.employee_name') }}</p>
                    <p class="font-semibold">{{ $line->employee_name ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.gcode') }}</p>
                    <p class="font-semibold">{{ $line->gcode ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.distributor') }}</p>
                    <p class="font-semibold">{{ $line->distributor ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.line_type') }}</p>
                    <p class="font-semibold">
                        {{ $line->line_type === 'prepaid' ? __('messages.prepaid') : __('messages.postpaid') }}
                    </p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.plan') }}</p>
                    <p class="font-semibold">{{ $line->plan->name ?? __('messages.not_specified') }}</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.package') }}</p>
                    <p class="font-semibold">{{ $line->package ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.last_invoice_date') }}</p>
                    <p class="font-semibold">{{ $line->last_invoice_date ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow-sm col-span-1 sm:col-span-2 lg:col-span-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.notes') }}</p>
                    <p class="font-semibold">{{ $line->notes ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.added_by') }}</p>
                    <p class="font-semibold">{{ $line->addedBy->name ?? __('messages.unknown') }}</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.created_at') }}</p>
                    <p class="font-semibold">{{ $line->created_at->format('Y-m-d H:i') }}</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.updated_at') }}</p>
                    <p class="font-semibold">{{ $line->updated_at->format('Y-m-d H:i') }}</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.for_sale') }}</p>
                    <p class="font-semibold">
                        {{ $line->for_sale ? __('messages.yes') : __('messages.no') }}
                    </p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.sale_price') }}</p>
                    <p class="font-semibold">{{ $line->sale_price ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg shadow-sm col-span-1 sm:col-span-2 lg:col-span-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.deleted_at') }}</p>
                    <p class="font-semibold">
                        {{ $line->deleted_at ? $line->deleted_at->format('Y-m-d H:i') : __('messages.not_deleted') }}
                    </p>
                </div>
            </div>

            <!-- Back Button -->
            @if($line->customer)  
                <div class="pt-4">  
                    <a href="{{ route('customers.show', $line->customer) }}"  
                       class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 dark:text-gray-200 font-medium px-5 py-2 rounded-lg shadow transition">  
                        🔙 {{ __('messages.back_to_customer_details') }}
                    </a>  
                </div>  
            @endif  
        </div>  
    </div>  
</x-app-layout>
