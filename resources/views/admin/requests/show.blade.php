<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-2xl">📋</span>
            <h2 class="text-xl font-black text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.request_details') }} #{{ $request->id }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 px-4 max-w-5xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Basic Info Card -->
            <div class="lg:col-span-1 space-y-8">
                <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 p-8">
                    <h3 class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.3em] mb-6">{{ __('messages.request_summary') }}</h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.phone_number') }}</span>
                            <span class="font-mono font-black text-gray-900 dark:text-white tracking-widest text-sm">{{ $request->line->phone_number }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.request_type') }}</span>
                            <span class="px-3 py-1 bg-gray-50 dark:bg-gray-900 rounded-full text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest">
                                {{ __('messages.request_type_' . $request->request_type) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.status') }}</span>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-amber-100 dark:bg-amber-900 text-amber-700 dark:text-amber-300',
                                    'inprogress' => 'bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300',
                                    'done' => 'bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300',
                                ];
                            @endphp
                            <span class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest {{ $statusColors[$request->status] ?? '' }}">
                                {{ __('messages.status_' . $request->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-8 pt-8 border-t border-gray-50 dark:border-gray-700/50 space-y-4 text-center sm:text-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}">
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.customer') }}</p>
                            <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $request->line->customer->full_name ?? '-' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.national_id') }}</p>
                            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 font-mono tracking-widest">{{ $request->line->customer->national_id ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Logs/System Card -->
                <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 p-8">
                    <h3 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.3em] mb-6">{{ __('messages.request_log') }}</h3>
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 rounded-xl bg-gray-50 dark:bg-gray-900 flex items-center justify-center text-xs">👤</div>
                            <div class="flex-1">
                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-tighter leading-none mb-1">{{ __('messages.requested_by') }}</p>
                                <p class="text-xs font-bold text-gray-800 dark:text-gray-200">{{ $request->requestedBy?->name ?? 'System' }}</p>
                            </div>
                        </div>
                        @if($request->doneBy)
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-xs text-emerald-600">✅</div>
                            <div class="flex-1">
                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-tighter leading-none mb-1">{{ __('messages.processed_by') }}</p>
                                <p class="text-xs font-bold text-gray-800 dark:text-gray-200">{{ $request->doneBy->name }}</p>
                            </div>
                        </div>
                        @endif
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 rounded-xl bg-gray-50 dark:bg-gray-900 flex items-center justify-center text-xs uppercase font-black text-gray-400">@</div>
                            <div class="flex-1">
                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-tighter leading-none mb-1">{{ __('messages.created_at') }}</p>
                                <p class="text-xs font-bold text-gray-800 dark:text-gray-200">{{ $request->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details Content Card -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 overflow-hidden min-h-full flex flex-col">
                    <div class="p-8 sm:p-12 flex-1 relative overflow-hidden">
                        
                        {{-- Background Decorative Element --}}
                        <div class="absolute -top-24 -right-24 w-64 h-64 bg-gray-50 dark:bg-gray-900/50 rounded-full blur-3xl opacity-50"></div>

                        <div class="relative z-10 h-full">
                            <h3 class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.3em] mb-10">{{ __('messages.request_details') }}</h3>

                            <div class="space-y-12">
                                @if ($request->request_type === 'stop' && $request->stopDetails)
                                    <div class="space-y-6 animate-fade-in">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-rose-50 dark:bg-rose-900/30 rounded-2xl flex items-center justify-center text-rose-600 text-xl">🔻</div>
                                            <h4 class="font-black text-gray-900 dark:text-white uppercase tracking-widest text-sm">{{ __('messages.request_type_stop') }}:</h4>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                                            <div class="space-y-1">
                                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.last_invoice_date') ?? 'Last Invoice Date' }}</p>
                                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $request->stopDetails->last_invoice_date ?? '-' }}</p>
                                            </div>
                                            @if($request->stopDetails->reason)
                                            <div class="space-y-1">
                                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.reason') }}</p>
                                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $request->stopDetails->reason }}</p>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                @elseif ($request->request_type === 'resell' && $request->resellDetails)
                                    <div class="space-y-8 animate-fade-in">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center text-indigo-600 text-xl">🔄</div>
                                            <h4 class="font-black text-gray-900 dark:text-white uppercase tracking-widest text-sm">{{ __('messages.request_type_resell') }}</h4>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-8">
                                            <div class="space-y-1">
                                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.resell_type') ?? 'Resell Type' }}</p>
                                                <p class="text-xs font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">{{ $request->resellDetails->resell_type === 'chip' ? __('messages.on_chip') : __('messages.at_branch') }}</p>
                                            </div>
                                            <div class="space-y-1">
                                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.request_date') }}</p>
                                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $request->resellDetails->request_date }}</p>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-8 pt-6 border-t border-gray-50 dark:border-gray-700/50">
                                            <div class="space-y-1">
                                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.old_serial') }}</p>
                                                <p class="text-xs font-bold text-gray-800 dark:text-gray-200 font-mono tracking-wider">{{ $request->resellDetails->old_serial ?? '-' }}</p>
                                            </div>
                                            <div class="space-y-1">
                                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.new_serial') }}</p>
                                                <p class="text-xs font-bold text-gray-800 dark:text-gray-200 font-mono tracking-wider">{{ $request->resellDetails->new_serial ?? '-' }}</p>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-8 pt-6 border-t border-gray-50 dark:border-gray-700/50">
                                            <div class="space-y-1">
                                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.buy_price') ?? 'Buy Price' }}</p>
                                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200 font-mono">{{ number_format($request->resellDetails->buy_price, 2) }}</p>
                                            </div>
                                            <div class="space-y-1">
                                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.sale_price') ?? 'Sale Price' }}</p>
                                                <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400 font-mono">{{ number_format($request->resellDetails->sale_price, 2) }}</p>
                                            </div>
                                        </div>

                                        @if($request->resellDetails->resell_type === 'branch')
                                        <div class="grid grid-cols-2 gap-8 pt-6 border-t border-gray-50 dark:border-gray-700/50">
                                            <div class="space-y-1">
                                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.full_name_label') }}</p>
                                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $request->resellDetails->full_name ?? '-' }}</p>
                                            </div>
                                            <div class="space-y-1">
                                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.national_id_label') }}</p>
                                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200 font-mono">{{ $request->resellDetails->national_id ?? '-' }}</p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                @elseif ($request->request_type === 'change_chip' && $request->changeChip)
                                    <div class="space-y-8 animate-fade-in">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center text-indigo-600 text-xl">🔁</div>
                                            <h4 class="font-black text-gray-900 dark:text-white uppercase tracking-widest text-sm">{{ __('messages.request_type_change_chip') }}</h4>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-8">
                                            <div class="space-y-1">
                                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.change_type') }}</p>
                                                <p class="text-xs font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">{{ $request->changeChip->change_type === 'chip' ? __('messages.on_chip') : __('messages.at_branch') }}</p>
                                            </div>
                                            <div class="space-y-1">
                                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.request_date') }}</p>
                                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $request->changeChip->request_date }}</p>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-8 pt-6 border-t border-gray-50 dark:border-gray-700/50">
                                            <div class="space-y-1">
                                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.old_serial') }}</p>
                                                <p class="text-xs font-bold text-gray-800 dark:text-gray-200 font-mono tracking-wider">{{ $request->changeChip->old_serial ?? '-' }}</p>
                                            </div>
                                            <div class="space-y-1">
                                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.new_serial') }}</p>
                                                <p class="text-xs font-bold text-gray-800 dark:text-gray-200 font-mono tracking-wider">{{ $request->changeChip->new_serial ?? '-' }}</p>
                                            </div>
                                        </div>

                                        @if($request->changeChip->change_type === 'branch')
                                        <div class="grid grid-cols-2 gap-8 pt-6 border-t border-gray-50 dark:border-gray-700/50">
                                            <div class="space-y-1">
                                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.full_name_label') }}</p>
                                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $request->changeChip->full_name ?? '-' }}</p>
                                            </div>
                                            <div class="space-y-1">
                                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.national_id_label') }}</p>
                                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200 font-mono">{{ $request->changeChip->national_id ?? '-' }}</p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                @elseif ($request->request_type === 'pause' && $request->pause)
                                    <div class="space-y-10 animate-fade-in">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-rose-50 dark:bg-rose-900/30 rounded-2xl flex items-center justify-center text-rose-600 text-xl">⏸️</div>
                                            <h4 class="font-black text-gray-900 dark:text-white uppercase tracking-widest text-sm">{{ __('messages.request_type_pause') }}</h4>
                                        </div>
                                        <div class="p-6 rounded-3xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700">
                                            <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.reason') }}</p>
                                            <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $request->pause->reason ?? '-' }}</p>
                                        </div>
                                    </div>

                                @elseif ($request->request_type === 'resume' && $request->resume)
                                    <div class="space-y-10 animate-fade-in">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center text-emerald-600 text-xl">▶️</div>
                                            <h4 class="font-black text-gray-900 dark:text-white uppercase tracking-widest text-sm">{{ __('messages.request_type_resume') }}</h4>
                                        </div>
                                        <div class="p-6 rounded-3xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700">
                                            <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.reason') }}</p>
                                            <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $request->resume->reason ?? '-' }}</p>
                                        </div>
                                    </div>

                                @elseif ($request->request_type === 'change_plan' && $request->changePlan)
                                    <div class="space-y-8 animate-fade-in">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center text-emerald-600 text-xl">🔄</div>
                                            <h4 class="font-black text-gray-900 dark:text-white uppercase tracking-widest text-sm">{{ __('messages.request_type_change_plan') }}</h4>
                                        </div>
                                        <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-10 p-8 rounded-3xl bg-gray-50 dark:bg-gray-900 shadow-inner">
                                            <div class="flex-1 text-center">
                                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Previous</p>
                                                <p class="text-sm font-bold text-gray-500 dark:text-gray-400 line-through decoration-rose-500/50 decoration-2">
                                                    {{ $request->changePlan->old_plan_name ?? ($request->status !== 'done' ? ($request->line->plan->name ?? '-') : '-') }}
                                                </p>
                                            </div>
                                            <div class="text-gray-300 dark:text-gray-700 text-2xl hidden sm:block">→</div>
                                            <div class="flex-1 text-center">
                                                <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-2">New Plan</p>
                                                <p class="text-lg font-black text-emerald-600 dark:text-emerald-400">{{ $request->changePlan->newPlan->name ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                @elseif ($request->request_type === 'change_distributor' && $request->changeDistributor)
                                    <div class="space-y-8 animate-fade-in">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-amber-50 dark:bg-amber-900/30 rounded-2xl flex items-center justify-center text-amber-600 text-xl">🏬</div>
                                            <h4 class="font-black text-gray-900 dark:text-white uppercase tracking-widest text-sm">{{ __('messages.request_type_change_distributor') }}</h4>
                                        </div>
                                        <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-10 p-8 rounded-3xl bg-gray-50 dark:bg-gray-900 shadow-inner">
                                            <div class="flex-1 text-center">
                                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Original</p>
                                                <p class="text-sm font-bold text-gray-500 dark:text-gray-400">{{ $request->changeDistributor->oldDistributor ? $request->changeDistributor->oldDistributor->name : 'لا يوجد موزع' }}</p>
                                            </div>
                                            <div class="text-gray-300 dark:text-gray-700 text-2xl hidden sm:block">→</div>
                                            <div class="flex-1 text-center">
                                                <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest mb-2">New Distributor</p>
                                                <p class="text-lg font-black text-amber-600 dark:text-amber-400">{{ $request->changeDistributor->newDistributor ? $request->changeDistributor->newDistributor->name : 'لا يوجد موزع' }}</p>
                                            </div>
                                        </div>
                                        @if($request->changeDistributor->reason)
                                        <div class="p-6 rounded-3xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700">
                                            <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.reason') }}</p>
                                            <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $request->changeDistributor->reason }}</p>
                                        </div>
                                        @endif
                                    </div>

                                @elseif ($request->request_type === 'change_date' && $request->changeDate)
                                    <div class="space-y-8 animate-fade-in">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-amber-50 dark:bg-amber-900/30 rounded-2xl flex items-center justify-center text-amber-600 text-xl">📅</div>
                                            <h4 class="font-black text-gray-900 dark:text-white uppercase tracking-widest text-sm">{{ __('messages.request_type_change_date') }}</h4>
                                        </div>
                                        <div class="grid grid-cols-2 gap-8">
                                            <div class="space-y-1">
                                                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.current_date') }}</p>
                                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $request->changeDate->current_date ?? '-' }}</p>
                                            </div>
                                            <div class="space-y-1">
                                                <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest">{{ __('messages.new_date_label') }}</p>
                                                <p class="text-base font-black text-amber-600 dark:text-amber-400">{{ $request->changeDate->new_date ?? '-' }}</p>
                                            </div>
                                        </div>
                                        @if($request->changeDate->reason)
                                        <div class="p-6 rounded-3xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700">
                                            <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.reason') }}</p>
                                            <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $request->changeDate->reason }}</p>
                                        </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="py-24 text-center">
                                        <div class="w-16 h-16 bg-red-50 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">❗</div>
                                        <p class="text-xs font-black text-red-500 uppercase tracking-widest">{{ __('messages.no_details_found') ?? 'No Details Found' }}</p>
                                    </div>
                                @endif
                                
                                {{-- Notes/Comments if available for ANY type --}}
                                @php 
                                    $comment = $request->stopDetails->comment ?? 
                                               $request->resellDetails->comment ?? 
                                               $request->changeChip->comment ?? 
                                               $request->pause->comment ?? 
                                               $request->resume->comment ?? 
                                               $request->changePlan->comment ?? 
                                               $request->changeDistributor->comment ?? 
                                               $request->changeDate->comment ?? null;
                                @endphp
                                @if($comment)
                                <div class="mt-auto pt-10">
                                    <div class="p-6 rounded-3xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700">
                                        <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.notes') }}</p>
                                        <p class="text-sm text-gray-700 dark:text-gray-300 font-medium leading-relaxed italic">{{ $comment }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="px-8 py-6 bg-gray-50/50 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700/50 flex justify-center">
                        <a href="{{ url()->previous() }}" class="text-[10px] font-black text-gray-400 dark:text-gray-500 hover:text-indigo-600 uppercase tracking-widest transition-colors flex items-center gap-2">
                             {{ __('messages.back') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
