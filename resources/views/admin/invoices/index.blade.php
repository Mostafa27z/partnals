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
    @if(session('success'))
    <div class="mb-4 p-4 rounded-lg bg-green-100 text-green-800 border border-green-300">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-4 rounded-lg bg-red-100 text-red-800 border border-red-300">
        {{ session('error') }}
    </div>
@endif
    {{-- Import Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8" dir="rtl">
        {{-- Combined Import (Old Logic) --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50">
            <h3 class="text-md font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                📦 إستيراد مجمع (الوضع القديم)
            </h3>
            <form method="POST" action="{{ route('invoices.import') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" required
                       class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/40 dark:file:text-indigo-300 transition">
                <div class="flex flex-col sm:flex-row gap-2">
                    <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white font-bold text-xs hover:bg-indigo-700 transition-all">
                        🚀 رفع مجمع
                    </button>
                    <a href="{{ route('invoices.sample', ['type' => 'bulk']) }}" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold text-xs hover:bg-gray-200 dark:hover:bg-gray-600 transition-all border border-gray-200 dark:border-gray-600 text-center">
                        📄 نموذج
                    </a>
                </div>
            </form>
        </div>

        {{-- Operator Price Import --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 border-t-4 border-t-amber-500">
            <h3 class="text-md font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                📡 إستيراد سعر المشغل (Operator)
            </h3>
            <form method="POST" action="{{ route('invoices.import-operator') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" required
                       class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 dark:file:bg-amber-900/40 dark:file:text-amber-300 transition">
                <div class="flex flex-col sm:flex-row gap-2">
                    <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 rounded-lg bg-amber-600 text-white font-bold text-xs hover:bg-amber-700 transition-all">
                        🚀 رفع المشغل
                    </button>
                    <a href="{{ route('invoices.sample', ['type' => 'operator']) }}" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold text-xs hover:bg-gray-200 dark:hover:bg-gray-600 transition-all border border-gray-200 dark:border-gray-600 text-center">
                        📄 نموذج
                    </a>
                </div>
            </form>
        </div>

        {{-- Customer Price Import --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 border-t-4 border-t-emerald-500">
            <h3 class="text-md font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                👤 إستيراد سعر العميل (Customer)
            </h3>
            <form method="POST" action="{{ route('invoices.import-customer') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" required
                       class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-emerald-900/40 dark:file:text-emerald-300 transition">
                <div class="flex flex-col sm:flex-row gap-2">
                    <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 transition-all">
                        🚀 رفع العميل
                    </button>
                    <a href="{{ route('invoices.sample', ['type' => 'customer']) }}" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold text-xs hover:bg-gray-200 dark:hover:bg-gray-600 transition-all border border-gray-200 dark:border-gray-600 text-center">
                        📄 نموذج
                    </a>
                </div>
            </form>
        </div>
    </div>
{{-- Filter Form --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
    <div class="text-sm font-bold text-gray-700 dark:text-gray-200">
        {{ __('messages.invoices_count', ['count' => $invoices->total()]) }}
    </div>
    <button type="button" onclick="toggleFilters('invoice-filters-panel')" class="inline-flex items-center gap-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
        {{ __('messages.filter_toggle') }}
    </button>
</div>

<div id="invoice-filters-panel" class="hidden bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 mb-8" dir="rtl">
    <form method="GET" action="{{ route('invoices.index') }}" class="w-full">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            {{-- Provider --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.Provider') }}</label>
                <select name="provider[]" multiple
                        class="block w-full px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm font-medium">
                    @foreach(['Vodafone', 'Etisalat', 'Orange', 'WE'] as $p)
                        <option value="{{ $p }}" {{ in_array($p, request('provider', [])) ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Line Type --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.Line Type') }}</label>
                <select name="line_type[]" multiple
                        class="block w-full px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm font-medium">
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
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.Plan') }}</label>
                <select name="plan_id[]" multiple
                        class="block w-full px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm font-medium">
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ in_array($plan->id, request('plan_id', [])) ? 'selected' : '' }}>
                            {{ $plan->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Payment Status --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.Payment Status') }}</label>
                <select name="is_paid[]" multiple
                        class="block w-full px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm font-medium">
                    <option value="1" {{ in_array('1', request('is_paid', [])) ? 'selected' : '' }}>
                        {{ __('messages.Paid') }}
                    </option>
                    <option value="0" {{ in_array('0', request('is_paid', [])) ? 'selected' : '' }}>
                        {{ __('messages.Unpaid') }}
                    </option>
                </select>
            </div>

            {{-- Paid By --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.Paid By') }}</label>
                <select name="paid_by[]" multiple
                        class="block w-full px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm font-medium">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ in_array($user->id, request('paid_by', [])) ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Customer --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.Customer') }}</label>
                <select name="customer_id[]" multiple
                        class="block w-full px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm font-medium">
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ in_array($customer->id, request('customer_id', [])) ? 'selected' : '' }}>
                            {{ $customer->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Date From --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.From Date') }}</label>
                <input type="date" name="from" value="{{ request('from') }}"
                       class="block w-full px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm font-medium">
            </div>

            {{-- Date To --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.To Date') }}</label>
                <input type="date" name="to" value="{{ request('to') }}"
                       class="block w-full px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm font-medium">
            </div>
        </div>

        {{-- Filter Button --}}
        <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm transition-all shadow-lg shadow-indigo-500/25 active:scale-95">
                🔍 {{ __('messages.Filter') }}
            </button>
        </div>
    </form>
</div>

    {{-- Invoices Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden" dir="rtl">
        <div class="overflow-x-auto">
            <table class="min-w-full text-center text-sm">
                <thead>
                    <tr class="bg-gray-50/80 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                        <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.Customer') }}</th>
                        <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.Phone') }}</th>
                        <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.Month') }}</th>
                        <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">👤 سعر العميل</th>
                        <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">📡 سعر المشغل</th>
                        <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">📈 الربح</th>
                        <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.Paid') }}</th>
                        <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.Payment Date') }}</th>
                        <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.Paid By') }}</th>
                        <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($invoices as $invoice)
                        <tr class="hover:bg-gray-50 dark:bg-gray-700/50 transition">
                            <td class="px-4 py-3 border">{{ $invoice->customer->full_name ?? ($invoice->line->customer->full_name ?? '-') }}</td>
                            <td class="px-4 py-3 border">{{ $invoice->line->phone_number ?? '-' }}</td>
                            <td class="px-4 py-3 border">{{ \Carbon\Carbon::parse($invoice->invoice_month)->translatedFormat('F Y') }}</td>
                            <td class="px-4 py-3 border text-green-600 font-bold">{{ number_format($invoice->amount, 2) }}</td>
                            <td class="px-4 py-3 border text-amber-600 font-bold">{{ number_format($invoice->operator_price, 2) }}</td>
                            <td class="px-4 py-3 border {{ $invoice->calculated_profit >= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-bold">{{ number_format($invoice->calculated_profit, 2) }}</td>
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
                            <td class="px-4 py-3 border">{{ $invoice->payment_date ? \Carbon\Carbon::parse($invoice->payment_date)->format('Y-m-d H:i:s') : '-' }}</td>
                            <td class="px-4 py-3 border">{{ $invoice->user?->name ?? '-' }}</td>
                            <td class="px-4 py-3 border">
                                <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف الفاتورة؟ سيتم ترحيل الفواتير التالية شهراً للوراء وتحديث تاريخ الفاتورة الأخير للخط.')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                        🗑️ {{ __('messages.delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-6">
                {{ $invoices->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleFilters(id) {
            const panel = document.getElementById(id);
            if (panel) {
                panel.classList.toggle('hidden');
            }
        }
    </script>
    @endpush
</x-app-layout>
