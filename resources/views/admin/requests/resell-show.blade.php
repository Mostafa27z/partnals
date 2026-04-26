<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-2xl">📄</span>
            <h2 class="text-xl font-black text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.request_details') }} #{{ $requestModel->id }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden">
                <!-- Status Banner -->
                <div class="px-8 py-6 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-center sm:text-start">
                    <div class="flex items-center gap-4 justify-center sm:justify-start">
                        <div class="w-12 h-12 bg-white dark:bg-gray-800 rounded-2xl shadow-sm flex items-center justify-center text-xl">
                            🔁
                        </div>
                        <div>
                            <h3 class="font-black text-gray-900 dark:text-white uppercase tracking-widest text-xs">{{ __('messages.request_type_resell') }}</h3>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 font-bold uppercase tracking-widest">{{ $requestModel->resellDetails->request_date }}</p>
                        </div>
                    </div>
                    <div>
                        @php
                            $statusColors = [
                                'pending' => 'bg-amber-100 dark:bg-amber-900 text-amber-700 dark:text-amber-300',
                                'inprogress' => 'bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300',
                                'done' => 'bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300',
                            ];
                        @endphp
                        <span class="px-6 py-2 rounded-2xl text-sm font-black uppercase tracking-widest {{ $statusColors[$requestModel->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">
                            {{ __('messages.status_' . $requestModel->status) }}
                        </span>
                    </div>
                </div>

                <div class="p-8 sm:p-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <!-- Basic Info -->
                        <div class="space-y-6 text-center sm:text-start">
                            <h4 class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.3em] mb-4">{{ __('messages.customer_details') }}</h4>
                            
                            <div class="space-y-1">
                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.phone_number') }}</p>
                                <p class="text-xl font-black text-gray-900 dark:text-white font-mono tracking-tighter">{{ $requestModel->line->phone_number }}</p>
                            </div>

                            <div class="space-y-1">
                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.customer') }}</p>
                                <p class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $requestModel->line->customer->full_name ?? '-' }}</p>
                            </div>

                            <div class="space-y-1">
                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.national_id') }}</p>
                                <p class="text-sm font-bold text-gray-500 dark:text-gray-400 font-mono tracking-widest">{{ $requestModel->line->customer->national_id ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Request Details -->
                        <div class="space-y-6 text-center sm:text-start pt-6 md:pt-0 border-t md:border-t-0 md:{{ $direction == 'rtl' ? 'border-r' : 'border-l' }} border-gray-50 dark:border-gray-700/50 md:{{ $direction == 'rtl' ? 'pr' : 'pl' }}-10">
                            <h4 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.3em] mb-4">{{ __('messages.request_details') }}</h4>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.change_type') }}</p>
                                    <p class="text-sm font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">
                                        {{ $requestModel->resellDetails->resell_type == 'chip' ? __('messages.on_chip') : __('messages.at_branch') }}
                                    </p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.request_date') }}</p>
                                    <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $requestModel->resellDetails->request_date }}</p>
                                </div>
                            </div>

                            @if($requestModel->resellDetails->old_serial || $requestModel->resellDetails->new_serial)
                                <div class="space-y-4 pt-4 border-t border-gray-50 dark:border-gray-700/50">
                                    <div class="space-y-1">
                                        <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.old_serial') }}</p>
                                        <p class="text-xs font-bold text-gray-800 dark:text-gray-200 font-mono tracking-wider">{{ $requestModel->resellDetails->old_serial ?? '-' }}</p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.new_serial') }}</p>
                                        <p class="text-xs font-bold text-gray-800 dark:text-gray-200 font-mono tracking-wider">{{ $requestModel->resellDetails->new_serial ?? '-' }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($requestModel->resellDetails->resell_type == 'branch')
                                <div class="space-y-4 pt-4 border-t border-gray-50 dark:border-gray-700/50">
                                    <div class="space-y-1">
                                        <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.full_name_label') }}</p>
                                        <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $requestModel->resellDetails->full_name ?? '-' }}</p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.national_id_label') }}</p>
                                        <p class="text-sm font-bold text-gray-800 dark:text-gray-200 font-mono">{{ $requestModel->resellDetails->national_id ?? '-' }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Notes Container -->
                    @if($requestModel->resellDetails->comment)
                        <div class="mt-10 p-6 rounded-3xl bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/30">
                            <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest mb-2">{{ __('messages.notes') }}</p>
                            <p class="text-sm text-amber-800 dark:text-amber-200 font-medium leading-relaxed">{{ $requestModel->resellDetails->comment }}</p>
                        </div>
                    @endif

                    <!-- Back Action -->
                    <div class="mt-10 pt-10 border-t border-gray-50 dark:border-gray-700/50 flex justify-center">
                        <a href="{{ route('requests.resell.index') }}" class="flex items-center gap-2 text-xs font-black text-gray-400 dark:text-gray-500 hover:text-indigo-600 transition-colors uppercase tracking-[0.2em]">
                            <span class="text-lg">←</span> {{ __('messages.back_to_list') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
