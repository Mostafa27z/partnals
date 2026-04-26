<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 md:gap-6">
            <div>
                <h2 class="text-3xl font-black text-gray-900 dark:text-white flex items-center gap-3">
                    <span class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white text-2xl shadow-lg">👥</span>
                    <span>{{ __('messages.hr_management') }}</span>
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 font-medium">{{ __('messages.hr_description', ['month' => $month, 'year' => $year]) }}</p>
            </div>
            <form method="GET" action="{{ route('hr.dashboard') }}" class="flex items-center gap-2 bg-white dark:bg-gray-800 p-2 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <input type="number" name="year" value="{{ $year }}" class="w-24 p-2.5 bg-gray-50 dark:bg-gray-900 border-none rounded-xl text-gray-900 dark:text-gray-100 text-sm font-bold" placeholder="{{ __('messages.year') }}" />
                <select name="month" class="w-32 p-2.5 bg-gray-50 dark:bg-gray-900 border-none rounded-xl text-gray-900 dark:text-gray-100 text-sm font-bold">
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>{{ __('messages.month_label') }} {{ $i }}</option>
                    @endfor
                </select>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl shadow-lg shadow-indigo-100 dark:shadow-none transition-all font-black text-sm active:scale-95">
                    {{ __('messages.report_filter') }}
                </button>
            </form>
        </div>
    </x-slot>

    <div class="space-y-10" dir="rtl">
        @if(session('success'))
            <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 rounded-2xl flex items-center gap-3 animate-fade-in-down">
                <span class="text-xl">✅</span>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800 text-rose-700 dark:text-rose-400 rounded-2xl flex items-center gap-3 animate-shake">
                <span class="text-xl">⚠️</span>
                <span class="font-bold">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Employee Payroll Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 dark:border-gray-700 flex justify-between items-center">
                <h3 class="font-black text-lg text-gray-800 dark:text-white uppercase tracking-tight">{{ __('messages.payroll_dues') }}</h3>
                <div class="flex gap-2">
                    <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full text-[10px] font-black uppercase">{{ __('messages.num_employees', ['count' => count($users)]) }}</span>
                </div>
            </div>
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-center">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-900/50 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                            <th class="px-6 py-5">{{ __('messages.employee') }}</th>
                            <th class="px-6 py-5">{{ __('messages.base_salary') }}</th>
                            <th class="px-6 py-5">{{ __('messages.total_advances') }}</th>
                            <th class="px-6 py-5">{{ __('messages.bonuses') }}</th>
                            <th class="px-6 py-5">{{ __('messages.net_due') }}</th>
                            <th class="px-6 py-5">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse($users as $user)
                        @php $netSalary = $user->base_salary + $user->target_bonus_total + $user->bonuses_total - $user->total_advances; @endphp
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-750/50 transition-colors">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3 justify-center">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-xs">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="font-bold text-gray-900 dark:text-white">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 font-bold text-gray-900 dark:text-white">
                                {{ number_format($user->base_salary, 2) }}
                            </td>
                            <td class="px-6 py-5">
                                <span class="text-rose-600 font-bold">-{{ number_format($user->total_advances, 2) }}</span>
                            </td>
                            <td class="px-6 py-5 text-emerald-600 font-bold">
                                +{{ number_format($user->target_bonus_total + $user->bonuses_total, 2) }}
                            </td>
                            <td class="px-6 py-5">
                                <span class="text-lg font-black text-indigo-600 dark:text-indigo-400">{{ number_format($netSalary, 2) }}</span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col gap-1">
                                    @if($user->is_paid)
                                        <div class="px-4 py-2 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-xl text-[10px] font-black border border-emerald-100 dark:border-emerald-800">{{ __('messages.paid_success') }}</div>
                                    @else
                                        <form method="POST" action="{{ route('hr.salary.pay') }}">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <input type="hidden" name="month" value="{{ $month }}">
                                            <input type="hidden" name="year" value="{{ $year }}">
                                            <input type="hidden" name="amount" value="{{ $netSalary }}">
                                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl shadow-md font-black text-[10px] transition-all active:scale-95">{{ __('messages.pay_salary') }}</button>
                                        </form>
                                    @endif
                                    
                                    <div class="flex gap-1 mt-1 justify-center">
                                        <button onclick="openAdvanceModal({{ $user->id }}, '{{ $user->name }}', {{ $user->base_salary }})" class="bg-rose-50 dark:bg-rose-900/30 text-rose-600 px-3 py-1.5 rounded-lg text-[10px] font-black border border-rose-100 dark:border-rose-800 transition-all hover:bg-rose-600 hover:text-white">{{ __('messages.payout_advance') }}</button>
                                        <button onclick="openBonusModal({{ $user->id }}, '{{ $user->name }}')" class="bg-amber-50 dark:bg-amber-900/30 text-amber-600 px-3 py-1.5 rounded-lg text-[10px] font-black border border-amber-100 dark:border-amber-800 transition-all hover:bg-amber-500 hover:text-white">{{ __('messages.bonus') }}</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500 py-6">{{ __('messages.no_employees_found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Performance Tracking Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mt-10">
        <div class="px-8 py-6 border-b border-gray-50 dark:border-gray-700 flex justify-between items-center">
            <h3 class="font-black text-lg text-gray-800 dark:text-white uppercase tracking-tight">{{ __('messages.assign_target') }}</h3>
            <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full text-[10px] font-black uppercase">{{ __('messages.sales') }}</span>
        </div>
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-center">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-900/50 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        <th class="px-6 py-5">{{ __('messages.employee') }}</th>
                        <th class="px-6 py-5">{{ __('messages.sales') }}</th>
                        <th class="px-6 py-5">{{ __('messages.target_status') }}</th>
                        <th class="px-6 py-5">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-750/50 transition-colors">
                        <td class="px-6 py-5 text-right">
                            <span class="font-bold text-gray-900 dark:text-white">{{ $user->name }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg text-sm font-black">{{ $user->monthly_sales }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-wrap gap-2 justify-center max-w-md mx-auto">
                                @foreach($user->targets_progress as $tp)
                                    <div class="bg-gray-50 dark:bg-gray-900/40 p-3 rounded-2xl border border-gray-100 dark:border-gray-800 w-full sm:w-[220px]">
                                        <div class="flex justify-between text-[10px] font-black uppercase mb-1.5">
                                            <span class="truncate pr-2">{{ $tp->target->name }}</span>
                                            <span class="{{ $tp->is_achieved ? 'text-emerald-500' : 'text-gray-400' }}">{{ $tp->current_progress }}/{{ $tp->threshold }}</span>
                                        </div>
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-indigo-600 h-1.5 rounded-full transition-all duration-700 ease-out" style="width: {{ min(($tp->current_progress / $tp->threshold) * 100, 100) }}%"></div>
                                        </div>
                                        <div class="mt-2 text-center">
                                            @if($tp->can_release)
                                                <form method="POST" action="{{ route('hr.target.release') }}">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                    <input type="hidden" name="target_id" value="{{ $tp->target->id }}">
                                                    <input type="hidden" name="month" value="{{ $month }}">
                                                    <input type="hidden" name="year" value="{{ $year }}">
                                                    <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white py-1.5 rounded-xl text-[10px] font-black shadow-lg shadow-emerald-100 dark:shadow-none transition-all active:scale-95">{{ __('messages.payout_bonus', ['amount' => number_format($tp->target->reward, 0)]) }}</button>
                                                </form>
                                            @elseif($tp->is_released)
                                                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-lg text-[9px] font-black border border-emerald-100 dark:border-emerald-800">
                                                    <span>✅</span>
                                                    <span>{{ __('messages.paid_out') }}</span>
                                                </div>
                                            @else
                                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest pt-1 block opacity-50">{{ __('messages.target_threshold') }}: {{ $tp->target->threshold }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <button onclick="openTargetModal({{ $user->id }}, '{{ $user->name }}')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl shadow-lg shadow-indigo-100 transition-all font-black text-xs active:scale-95">
                                {{ __('messages.assign_target') }}
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
        {{-- Target Management Section --}}
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mt-10">
            <div class="px-8 py-6 border-b border-gray-50 dark:border-gray-700 flex justify-between items-center">
                <h3 class="font-black text-lg text-gray-800 dark:text-white uppercase tracking-tight">{{ __('messages.target_management') }}</h3>
                <button onclick="document.getElementById('create_target_modal').showModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl shadow-lg shadow-indigo-100 dark:shadow-none transition-all font-black text-xs active:scale-95">
                    + {{ __('messages.add_new_target') }}
                </button>
            </div>
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-center">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-900/50 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                            <th class="px-6 py-5">{{ __('messages.target_name') }}</th>
                            <th class="px-6 py-5">{{ __('messages.target_scope') }}</th>
                            <th class="px-6 py-5">{{ __('messages.target_type') }}</th>
                            <th class="px-6 py-5">{{ __('messages.target_threshold') }}</th>
                            <th class="px-6 py-5">{{ __('messages.target_reward') }}</th>
                            <th class="px-6 py-5">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @foreach($allTargets as $target)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-750/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white text-right">{{ $target->name }}</td>
                            <td class="px-6 py-4 lowercase tracking-tighter">
                                <span class="px-2 py-1 bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 rounded-lg text-[10px] font-black">
                                    {{ __('messages.scope_' . ($target->scope ?: 'requests')) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 uppercase tracking-tighter">
                                <span class="px-2 py-1 {{ $target->type === 'general' ? 'bg-indigo-50 text-indigo-600' : 'bg-amber-50 text-amber-600' }} rounded-lg text-[10px] font-black">
                                    {{ __('messages.target_' . $target->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-black text-gray-600 dark:text-gray-400">{{ $target->threshold }}</td>
                            <td class="px-6 py-4 font-black text-emerald-600">{{ number_format($target->reward, 2) }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick='openEditTargetModal(@json($target))' class="p-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    <form action="{{ route('hr.target.destroy', $target) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_delete_target') }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 bg-rose-50 dark:bg-rose-900/30 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modals -->
    {{-- مودال إضافة مستهدف جديد --}}
    <div id="create_target_modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-0">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('create_target_modal').close()"></div>
        <div class="relative z-10 w-11/12 max-w-xl bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-[2.5rem] p-0 overflow-hidden shadow-2xl" dir="rtl">
            <div class="h-2.5 bg-indigo-600 w-full animate-pulse-slow"></div>
            <div class="p-6 sm:p-8">
                <h3 class="font-black text-xl sm:text-2xl mb-6 sm:mb-8 text-indigo-600 flex items-center gap-4">
                    <span class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center text-2xl shadow-inner shadow-indigo-100">🎯</span>
                    {{ __('messages.add_new_target') }}
                </h3>
                <form method="POST" action="{{ route('hr.target.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block mb-1 text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('messages.target_name') }}</label>
                        <input type="text" name="name" required class="w-full p-4 bg-gray-50 dark:bg-gray-900/50 border-2 border-gray-100 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white focus:border-indigo-500 outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1 text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('messages.target_type') }}</label>
                            <select name="type" required class="w-full p-4 bg-gray-50 dark:bg-gray-900/50 border-2 border-gray-100 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white focus:border-indigo-500 outline-none">
                                <option value="general">{{ __('messages.target_general') }}</option>
                                <option value="specific">{{ __('messages.target_specific') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('messages.target_scope') }}</label>
                            <select name="scope" required class="w-full p-4 bg-gray-50 dark:bg-gray-900/50 border-2 border-gray-100 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white focus:border-indigo-500 outline-none">
                                <option value="requests">{{ __('messages.scope_requests') }}</option>
                                <option value="invoices">{{ __('messages.scope_invoices') }}</option>
                                <option value="both">{{ __('messages.scope_both') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1 text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('messages.target_threshold') }}</label>
                            <input type="number" name="threshold" required min="1" class="w-full p-4 bg-gray-50 dark:bg-gray-900/50 border-2 border-gray-100 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white focus:border-indigo-500 outline-none">
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('messages.target_reward') }}</label>
                            <input type="number" step="0.01" name="reward" required min="0" class="w-full p-4 bg-gray-50 dark:bg-gray-900/50 border-2 border-gray-100 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white focus:border-indigo-500 outline-none">
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 text-gray-600 dark:text-gray-200 py-4 rounded-2xl font-black" onclick="document.getElementById('create_target_modal').close()">{{ __('messages.cancel') }}</button>
                        <button type="submit" class="flex-[2] bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-2xl font-black shadow-lg">{{ __('messages.add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- مودال تعديل تارجت --}}
    <div id="edit_target_modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-0">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('edit_target_modal').close()"></div>
        <div class="relative z-10 w-11/12 max-w-3xl bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-[2.5rem] p-0 overflow-hidden max-h-[90vh] shadow-2xl" dir="rtl">
            <div class="h-2.5 bg-blue-600 w-full animate-pulse-slow"></div>
            <div class="p-4 sm:p-8 overflow-y-auto max-h-[85vh]">
                {{-- Header --}}
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h3 class="font-bold text-lg sm:text-2xl text-blue-600 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-8 sm:w-8 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ __('messages.edit_target') }}
                    </h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" onclick="document.getElementById('edit_target_modal').close()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" id="edit_target_form" class="space-y-4 sm:space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Main grid: stacks on mobile, side-by-side on md+ --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                        {{-- Left column: target details --}}
                        <div class="space-y-3 sm:space-y-4">
                            <div>
                                <label class="block mb-1 text-xs sm:text-sm font-bold text-gray-600 dark:text-gray-400">{{ __('messages.target_name') }}</label>
                                <input type="text" name="name" id="edit_target_name" required class="w-full border-2 border-gray-200 dark:border-gray-700 p-2.5 sm:p-3 rounded-lg text-gray-900 text-sm sm:text-base focus:border-blue-500 transition-all outline-none" placeholder="{{ __('messages.target_name') }}" />
                            </div>

                            <div>
                                <label class="block mb-1 text-xs sm:text-sm font-bold text-gray-600 dark:text-gray-400">{{ __('messages.target_type') }}</label>
                                <select name="type" id="edit_target_type" required class="w-full border-2 border-gray-200 dark:border-gray-700 p-2.5 sm:p-3 rounded-lg text-gray-900 text-sm sm:text-base focus:border-blue-500 outline-none">
                                    <option value="general">{{ __('messages.target_general') }}</option>
                                    <option value="specific">{{ __('messages.target_specific') }}</option>
                                </select>
                            </div>

                            <div>
                                <label class="block mb-1 text-xs sm:text-sm font-bold text-gray-600 dark:text-gray-400">{{ __('messages.target_scope') }}</label>
                                <select name="scope" id="edit_target_scope" required class="w-full border-2 border-gray-200 dark:border-gray-700 p-2.5 sm:p-3 rounded-lg text-gray-900 text-sm sm:text-base focus:border-blue-500 outline-none">
                                    <option value="requests">{{ __('messages.scope_requests') }}</option>
                                    <option value="invoices">{{ __('messages.scope_invoices') }}</option>
                                    <option value="both">{{ __('messages.scope_both') }}</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-1 text-xs sm:text-sm font-bold text-gray-600 dark:text-gray-400">{{ __('messages.start_date') }}</label>
                                    <input type="date" name="start_date" id="edit_target_start_date" class="w-full border-2 border-gray-200 dark:border-gray-700 p-2.5 rounded-lg text-gray-900 text-sm focus:border-blue-500 outline-none" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs sm:text-sm font-bold text-gray-600 dark:text-gray-400">{{ __('messages.end_date') }}</label>
                                    <input type="date" name="end_date" id="edit_target_end_date" class="w-full border-2 border-gray-200 dark:border-gray-700 p-2.5 rounded-lg text-gray-900 text-sm focus:border-blue-500 outline-none" />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 sm:gap-4">
                                <div>
                                    <label class="block mb-1 text-xs sm:text-sm font-bold text-gray-600 dark:text-gray-400">{{ __('messages.target_threshold') }}</label>
                                    <input type="number" name="threshold" id="edit_target_threshold" required min="1" class="w-full border-2 border-gray-200 dark:border-gray-700 p-2.5 sm:p-3 rounded-lg text-gray-900 text-sm sm:text-base focus:border-blue-500 outline-none" />
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs sm:text-sm font-bold text-gray-600 dark:text-gray-400">{{ __('messages.target_reward') }}</label>
                                    <input type="number" step="0.01" name="reward" id="edit_target_reward" required min="0" class="w-full border-2 border-gray-200 dark:border-gray-700 p-2.5 sm:p-3 rounded-lg text-gray-900 text-sm sm:text-base focus:border-blue-500 outline-none" />
                                </div>
                            </div>
                        </div>

                        {{-- Right column: employee list --}}
                        <div class="flex flex-col">
                            <label class="block mb-2 text-xs sm:text-sm font-bold text-gray-600 dark:text-gray-400">{{ __('messages.manage_assigned_employees') }}</label>
                            <div class="border-2 border-gray-200 dark:border-gray-700 rounded-lg p-2 sm:p-3 min-h-[120px] max-h-[160px] sm:max-h-[200px] overflow-y-auto bg-gray-50 dark:bg-gray-900">
                                @foreach($users as $u)
                                    <div class="flex items-center gap-2 sm:gap-3 mb-1 p-1.5 sm:p-2 hover:bg-white dark:hover:bg-gray-800 rounded transition-colors border border-transparent hover:border-blue-200">
                                        <input type="checkbox" name="user_ids[]" value="{{ $u->id }}" id="edit_user_{{ $u->id }}" class="w-4 h-4 text-blue-600 rounded cursor-pointer shrink-0">
                                        <label for="edit_user_{{ $u->id }}" class="text-xs sm:text-sm cursor-pointer font-medium truncate">{{ $u->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex justify-between mt-1.5 sm:mt-2">
                                <button type="button" onclick="toggleEditUsers(true)" class="text-[10px] sm:text-xs text-blue-600 font-bold hover:underline">{{ __('messages.select_all') }}</button>
                                <button type="button" onclick="toggleEditUsers(false)" class="text-[10px] sm:text-xs text-red-500 font-bold hover:underline">{{ __('messages.deselect_all') }}</button>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col-reverse sm:flex-row gap-2 sm:gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-xl shadow-lg shadow-blue-200 transition-all font-bold text-sm sm:text-base">
                            {{ __('messages.update_data') }}
                        </button>
                        <button type="button" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 sm:px-6 py-2.5 sm:py-3 rounded-xl transition-all font-bold text-sm sm:text-base" onclick="document.getElementById('edit_target_modal').close()">
                            {{ __('messages.cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- مودال إضافة سلفة --}}
    <div id="advance_modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-0">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('advance_modal').close()"></div>
        <div class="relative z-10 w-11/12 max-w-xl bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-[2.5rem] p-0 overflow-hidden shadow-2xl" dir="rtl">
            <div class="h-2.5 bg-rose-500 w-full animate-pulse-slow"></div>
            <div class="p-6 sm:p-8">
                <h3 class="font-black text-xl sm:text-2xl mb-6 sm:mb-8 text-rose-600 flex items-start sm:items-center gap-4">
                    <span class="w-12 h-12 shrink-0 bg-rose-50 dark:bg-rose-900/30 rounded-2xl flex items-center justify-center text-2xl shadow-inner shadow-rose-100">🔻</span>
                    <div class="flex flex-col flex-1">
                        <span class="text-xs text-rose-400 dark:text-rose-500/50 uppercase tracking-widest font-black mb-1 opacity-70">{{ __('messages.advance') }}</span>
                        <span class="leading-snug sm:leading-tight">{{ __('messages.record_advance_for') }} <span id="adv_user_name" class="text-gray-900 dark:text-white pb-1 border-b-2 border-rose-300 inline-block mt-1 sm:mt-0"></span></span>
                    </div>
                </h3>
                <form method="POST" action="{{ route('hr.advance.store') }}" id="advance_form" class="space-y-6">
                    @csrf
                    <input type="hidden" name="user_id" id="adv_user_id">
                    <input type="hidden" id="adv_base_salary">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider px-1">{{ __('messages.amount_label') }}</label>
                            <div class="relative group">
                                <input type="number" name="amount" id="adv_amount" min="1" required class="w-full p-4 bg-gray-50 dark:bg-gray-900/50 border-2 border-gray-100 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 transition-all font-black text-lg outline-none group-hover:border-rose-200" onkeyup="checkAdvanceLimit()">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">EGP</span>
                            </div>
                            <div id="advance_warning" class="text-rose-500 text-xs font-bold mt-2 hidden py-3 px-4 bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800/50 rounded-xl leading-relaxed animate-pulse"></div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider px-1">{{ __('messages.date_label') }}</label>
                            <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full p-4 bg-gray-50 dark:bg-gray-900/50 border-2 border-gray-100 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 transition-all font-black outline-none hover:border-rose-200">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider px-1">{{ __('messages.notes_label') }}</label>
                        <textarea name="notes" rows="2" class="w-full p-4 bg-gray-50 dark:bg-gray-900/50 border-2 border-gray-100 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 transition-all font-medium outline-none hover:border-rose-200" placeholder="..."></textarea>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row gap-4 pt-6 mt-8">
                        <button type="button" class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-200 py-4 rounded-2xl font-black transition-all transform active:scale-95" onclick="document.getElementById('advance_modal').close()">{{ __('messages.cancel') }}</button>
                        <button type="submit" class="flex-[2] bg-rose-600 hover:bg-rose-700 text-white py-4 rounded-2xl shadow-xl shadow-rose-500/20 font-black transition-all transform active:scale-95 flex items-center justify-center gap-2">
                            <span>{{ __('messages.save_advance') }}</span>
                            <span class="text-lg">💾</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- مودال إضافة مكافأة --}}
    <div id="bonus_modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-0">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('bonus_modal').close()"></div>
        <div class="relative z-10 w-11/12 max-w-xl bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-[2.5rem] p-0 overflow-hidden shadow-2xl" dir="rtl">
            <div class="h-2.5 bg-amber-500 w-full animate-pulse-slow"></div>
            <div class="p-6 sm:p-8">
                <h3 class="font-black text-xl sm:text-2xl mb-6 sm:mb-8 text-amber-600 flex items-start sm:items-center gap-4">
                    <span class="w-12 h-12 shrink-0 bg-amber-50 dark:bg-amber-900/30 rounded-2xl flex items-center justify-center text-2xl shadow-inner shadow-amber-100">🌟</span>
                    <div class="flex flex-col flex-1">
                        <span class="text-xs text-amber-400 dark:text-amber-500/50 uppercase tracking-widest font-black mb-1 opacity-70">{{ __('messages.bonus') }}</span>
                        <span class="leading-snug sm:leading-tight">{{ __('messages.record_bonus_for') }} <span id="bonus_user_name" class="text-gray-900 dark:text-white pb-1 border-b-2 border-amber-300 inline-block mt-1 sm:mt-0"></span></span>
                    </div>
                </h3>
                <form method="POST" action="{{ route('hr.bonus.store') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="user_id" id="bonus_user_id">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider px-1">{{ __('messages.amount_label') }}</label>
                            <div class="relative group">
                                <input type="number" name="amount" min="1" required class="w-full p-4 bg-gray-50 dark:bg-gray-900/50 border-2 border-gray-100 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all font-black text-lg outline-none group-hover:border-amber-200">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">EGP</span>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider px-1">{{ __('messages.date_label') }}</label>
                            <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full p-4 bg-gray-50 dark:bg-gray-900/50 border-2 border-gray-100 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all font-black outline-none hover:border-amber-200">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider px-1">{{ __('messages.reason') }}</label>
                        <input type="text" name="reason" required class="w-full p-4 bg-gray-50 dark:bg-gray-900/50 border-2 border-gray-100 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all font-medium outline-none hover:border-amber-200" placeholder="...">
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row gap-4 pt-6 mt-8">
                        <button type="button" class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-200 py-4 rounded-2xl font-black transition-all transform active:scale-95" onclick="document.getElementById('bonus_modal').close()">{{ __('messages.cancel') }}</button>
                        <button type="submit" class="flex-[2] bg-amber-500 hover:bg-amber-600 text-white py-4 rounded-2xl shadow-xl shadow-amber-500/20 font-black transition-all transform active:scale-95 flex items-center justify-center gap-2">
                            <span>{{ __('messages.save_bonus') }}</span>
                            <span class="text-lg">🌟</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- مودال ربط تارجت --}}
    <div id="target_modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-0">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('target_modal').close()"></div>
        <div class="relative z-10 w-11/12 max-w-xl bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-[2.5rem] p-0 overflow-hidden shadow-2xl" dir="rtl">
            <div class="h-2.5 bg-indigo-500 w-full animate-pulse-slow"></div>
            <div class="p-6 sm:p-8">
                <h3 class="font-black text-xl sm:text-2xl mb-6 sm:mb-8 text-indigo-600 flex items-start sm:items-center gap-4">
                    <span class="w-12 h-12 shrink-0 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center text-2xl shadow-inner shadow-indigo-100">🎯</span>
                    <div class="flex flex-col flex-1">
                        <span class="text-xs text-indigo-400 dark:text-indigo-500/50 uppercase tracking-widest font-black mb-1 opacity-70">{{ __('messages.assign_target') }}</span>
                        <span class="leading-snug sm:leading-tight">{{ __('messages.assign_target_for') }} <span id="target_user_name" class="text-gray-900 dark:text-white pb-1 border-b-2 border-indigo-300 inline-block mt-1 sm:mt-0"></span></span>
                    </div>
                </h3>
                <form method="POST" action="{{ route('hr.target.assign') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="user_ids[]" id="target_user_id">
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider px-1">{{ __('messages.choose_target') }}</label>
                        <select name="target_id" required class="w-full p-4 bg-gray-50 dark:bg-gray-900/50 border-2 border-gray-100 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-black outline-none hover:border-indigo-200 appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem_1.25rem] bg-[right_1rem_center] bg-no-repeat">
                            @foreach($allTargets->where('type', 'specific') as $specTarget)
                                <option value="{{ $specTarget->id }}">{{ $specTarget->name }} ({{ __('messages.target_threshold') }}: {{ $specTarget->threshold }} - {{ __('messages.target_reward') }}: {{ $specTarget->reward }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row gap-4 pt-6 mt-8">
                        <button type="button" class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-200 py-4 rounded-2xl font-black transition-all transform active:scale-95" onclick="document.getElementById('target_modal').close()">{{ __('messages.cancel') }}</button>
                        <button type="submit" class="flex-[2] bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-2xl shadow-xl shadow-indigo-500/20 font-black transition-all transform active:scale-95 flex items-center justify-center gap-2">
                            <span>{{ __('messages.confirm_assign') }}</span>
                            <span class="text-lg">🎯</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalIds = ['edit_target_modal', 'create_target_modal', 'advance_modal', 'bonus_modal', 'target_modal'];
            modalIds.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.showModal = function() { 
                        this.classList.remove('hidden'); 
                        this.classList.add('flex');
                        document.body.style.overflow = 'hidden'; 
                    };
                    el.close = function() { 
                        this.classList.add('hidden'); 
                        this.classList.remove('flex');
                        document.body.style.overflow = 'auto';
                    };
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    modalIds.forEach(id => {
                        const el = document.getElementById(id);
                        if (el && !el.classList.contains('hidden')) {
                            el.close();
                        }
                    });
                }
            });
        });

        function openAdvanceModal(userId, userName, baseSalary) {
            document.getElementById('adv_user_id').value = userId;
            document.getElementById('adv_user_name').innerText = userName;
            document.getElementById('adv_base_salary').value = baseSalary;
            document.getElementById('advance_warning').classList.add('hidden');
            document.getElementById('advance_modal').showModal();
        }

        function openBonusModal(userId, userName) {
            document.getElementById('bonus_user_id').value = userId;
            document.getElementById('bonus_user_name').innerText = userName;
            document.getElementById('bonus_modal').showModal();
        }

        function openTargetModal(userId, userName) {
            document.getElementById('target_user_id').value = userId;
            document.getElementById('target_user_name').innerText = userName;
            document.getElementById('target_modal').showModal();
        }

        function selectAllUsers(status) {
            const checkboxes = document.querySelectorAll('input[name="user_ids[]"]');
            checkboxes.forEach(cb => cb.checked = status);
        }

        function openEditTargetModal(target) {
            document.getElementById('edit_target_form').action = `/admin/hr/target/${target.id}`;
            document.getElementById('edit_target_name').value = target.name;
            document.getElementById('edit_target_type').value = target.type;
            document.getElementById('edit_target_scope').value = target.scope || 'requests';
            document.getElementById('edit_target_threshold').value = target.threshold;
            document.getElementById('edit_target_reward').value = target.reward;
            document.getElementById('edit_target_start_date').value = target.start_date || '';
            document.getElementById('edit_target_end_date').value = target.end_date || '';
            
            // إعادة ضبط وتحديد الموظفين
            const targetUserIds = target.users ? target.users.map(u => u.id) : [];
            const checkboxes = document.querySelectorAll('#edit_target_form input[name="user_ids[]"]');
            checkboxes.forEach(cb => {
                cb.checked = targetUserIds.includes(parseInt(cb.value));
            });

            document.getElementById('edit_target_modal').showModal();
        }

        function toggleEditUsers(status) {
            const checkboxes = document.querySelectorAll('#edit_target_form input[name="user_ids[]"]');
            checkboxes.forEach(cb => cb.checked = status);
        }

        // Live Validation for Advances Limit
        let typingTimer;
        function checkAdvanceLimit() {
            clearTimeout(typingTimer);
            let amount = document.getElementById('adv_amount').value;
            let userId = document.getElementById('adv_user_id').value;
            let warningText = document.getElementById('advance_warning');
            
            if (amount && amount > 0) {
                typingTimer = setTimeout(() => {
                    fetch(`/admin/hr/advance-check?user_id=${userId}&amount=${amount}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.exceeds) {
                                warningText.innerText = data.message;
                                warningText.classList.remove('hidden');
                            } else {
                                warningText.classList.add('hidden');
                            }
                        })
                        .catch(err => console.error(err));
                }, 500);
            } else {
                warningText.classList.add('hidden');
            }
        }
    </script>
    @endpush

</x-app-layout>
