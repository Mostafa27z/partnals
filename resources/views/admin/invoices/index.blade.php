<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 flex items-center gap-3">
                <span class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-violet-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none">
                    <span class="text-white text-lg">📄</span>
                </span>
                {{ __('messages.All Invoices') }}
            </h2>
            <div class="inline-flex items-center gap-3 px-5 py-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 font-black text-lg border border-emerald-100 dark:border-emerald-800/30 shadow-sm">
                💰 {{ __('messages.Total') }}: {{ number_format($total, 2) }} {{ __('messages.EGP') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
    
    {{-- Import Excel Form --}}
    <div class="mb-8 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50" dir="rtl">
        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
            📥 {{ __('messages.Import Invoices from Excel') ?? 'إستيراد الفواتير من إكسيل' }}
        </h3>
        <form method="POST" action="{{ route('invoices.import') }}" enctype="multipart/form-data" class="flex flex-col md:flex-row items-center gap-4">
            @csrf
            <div class="w-full md:w-1/2">
                <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" required
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/40 dark:file:text-indigo-300 transition">
            </div>
            <div class="flex gap-3 mt-4 md:mt-0">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/25">
                    🚀 تحميل البيانات
                </button>
                <a href="{{ route('invoices.sample') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold text-sm hover:bg-gray-200 dark:hover:bg-gray-600 transition-all border border-gray-200 dark:border-gray-600">
                    📄 تنزيل نموذج فارغ
                </a>
            </div>
        </form>
    </div>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('invoices.index') }}"
          class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50"
          dir="rtl">

        {{-- Provider --}}
        <div>
            <label class="block text-sm font-black text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.Provider') }}</label>
            <select name="provider[]" multiple
                    class="block w-full px-4 py-2.5 rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
                @foreach(['Vodafone', 'Etisalat', 'Orange', 'WE'] as $p)
                    <option value="{{ $p }}" {{ in_array($p, request('provider', [])) ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
            </select>
        </div>

        {{-- Line Type --}}
        <div>
            <label class="block text-sm font-black text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.Line Type') }}</label>
            <select name="line_type[]" multiple
                    class="block w-full px-4 py-2.5 rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
                <option value="prepaid" {{ in_array('prepaid', request('line_type', [])) ? 'selected' : '' }}>
                    {{ __('messages.Prepaid') }}
                </option>
                <option value="postpaid" {{ in_array('postpaid', request('line_type', [])) ? 'selected' : '' }}>
                    {{ __('messages.Postpaid') }}
                </option>
            </select>
        </div>

        {{-- Plan --}}
        <div>
            <label class="block text-sm font-black text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.Plan') }}</label>
            <select name="plan_id[]" multiple
                    class="block w-full px-4 py-2.5 rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ in_array($plan->id, request('plan_id', [])) ? 'selected' : '' }}>
                        {{ $plan->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Payment Status --}}
        <div>
            <label class="block text-sm font-black text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.Payment Status') }}</label>
            <select name="is_paid[]" multiple
                    class="block w-full px-4 py-2.5 rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
                <option value="1" {{ in_array('1', request('is_paid', [])) ? 'selected' : '' }}>
                    {{ __('messages.Paid') }}
                </option>
                <option value="0" {{ in_array('0', request('is_paid', [])) ? 'selected' : '' }}>
                    {{ __('messages.Unpaid') }}
                </option>
            </select>
        </div>

        {{-- Billed By --}}
        <div>
            <label class="block text-sm font-black text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.Paid By') }}</label>
            <select name="paid_by[]" multiple
                    class="block w-full px-4 py-2.5 rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ in_array($user->id, request('paid_by', [])) ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Date From --}}
        <div>
            <label class="block text-sm font-black text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.From Date') }}</label>
            <input type="date" name="from" value="{{ request('from') }}"
                   class="block w-full px-4 py-2.5 rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
        </div>

        {{-- Date To --}}
        <div>
            <label class="block text-sm font-black text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.To Date') }}</label>
            <input type="date" name="to" value="{{ request('to') }}"
                   class="block w-full px-4 py-2.5 rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
        </div>

        {{-- Filter Button --}}
        <div class="md:col-span-4 flex justify-end mt-4">
            <button class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/25">
                🔍 {{ __('messages.Filter') }}
            </button>
        </div>
    </form>

    {{-- Invoices Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden" dir="rtl">
        <div class="overflow-x-auto">
            <table class="min-w-full text-center text-sm">
                <thead>
                    <tr class="bg-gray-50/80 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                        <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.Customer') }}</th>
                        <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.Month') }}</th>
                        <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.Amount') }}</th>
                        <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.Paid') }}</th>
                        <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.Payment Date') }}</th>
                        <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.Paid By') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($invoices as $invoice)
                        <tr class="hover:bg-gray-50 dark:bg-gray-700/50 transition">
                            <td class="px-4 py-3 border">{{ $invoice->customer->full_name ?? '-' }}</td>
                            <td class="px-4 py-3 border">{{ \Carbon\Carbon::parse($invoice->invoice_month)->translatedFormat('F Y') }}</td>
                            <td class="px-4 py-3 border text-green-600 font-bold">{{ $invoice->amount }} {{ __('messages.EGP') }}</td>
                            <td class="px-4 py-3 border">
                                @if($invoice->is_paid)
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                                        ✔ {{ __('messages.Yes') }}
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">
                                        ✖ {{ __('messages.No') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 border">{{ $invoice->payment_date ? \Carbon\Carbon::parse($invoice->payment_date)->format('Y-m-d') : '-' }}</td>
                            <td class="px-4 py-3 border">{{ $invoice->user?->name ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-6">
                {{ $invoices->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
