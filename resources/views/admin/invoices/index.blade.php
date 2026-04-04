<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                📄 {{ __('messages.All Invoices') }}
            </h2>
            <div class="mt-2 md:mt-0 text-green-700 font-bold text-xl">
                💰 {{ __('messages.Total') }}: {{ number_format($total, 2) }} {{ __('messages.EGP') }}
            </div>
        </div>
    </x-slot>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('invoices.index') }}"
          class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-100"
          dir="rtl">

        {{-- Provider --}}
        <div>
            <label class="block text-base font-semibold text-gray-700 dark:text-gray-300">{{ __('messages.Provider') }}</label>
            <select name="provider[]" multiple
                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @foreach(['Vodafone', 'Etisalat', 'Orange', 'WE'] as $p)
                    <option value="{{ $p }}" {{ in_array($p, request('provider', [])) ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
            </select>
        </div>

        {{-- Line Type --}}
        <div>
            <label class="block text-base font-semibold text-gray-700 dark:text-gray-300">{{ __('messages.Line Type') }}</label>
            <select name="line_type[]" multiple
                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
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
            <label class="block text-base font-semibold text-gray-700 dark:text-gray-300">{{ __('messages.Plan') }}</label>
            <select name="plan_id[]" multiple
                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ in_array($plan->id, request('plan_id', [])) ? 'selected' : '' }}>
                        {{ $plan->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Payment Status --}}
        <div>
            <label class="block text-base font-semibold text-gray-700 dark:text-gray-300">{{ __('messages.Payment Status') }}</label>
            <select name="is_paid[]" multiple
                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="1" {{ in_array('1', request('is_paid', [])) ? 'selected' : '' }}>
                    {{ __('messages.Paid') }}
                </option>
                <option value="0" {{ in_array('0', request('is_paid', [])) ? 'selected' : '' }}>
                    {{ __('messages.Unpaid') }}
                </option>
            </select>
        </div>

        {{-- Date From --}}
        <div>
            <label class="block text-base font-semibold text-gray-700 dark:text-gray-300">{{ __('messages.From Date') }}</label>
            <input type="date" name="from" value="{{ request('from') }}"
                   class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        {{-- Date To --}}
        <div>
            <label class="block text-base font-semibold text-gray-700 dark:text-gray-300">{{ __('messages.To Date') }}</label>
            <input type="date" name="to" value="{{ request('to') }}"
                   class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        {{-- Filter Button --}}
        <div class="md:col-span-3 flex justify-end mt-4">
            <button class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                🔍 {{ __('messages.Filter') }}
            </button>
        </div>
    </form>

    {{-- Invoices Table --}}
    <div class="py-6" dir="rtl">
        <div class="max-w-7xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-100 overflow-x-auto">
            <table class="min-w-full text-base text-center border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <thead class="bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-3 border">{{ __('messages.Customer') }}</th>
                        <th class="px-4 py-3 border">{{ __('messages.Month') }}</th>
                        <th class="px-4 py-3 border">{{ __('messages.Amount') }}</th>
                        <th class="px-4 py-3 border">{{ __('messages.Paid') }}</th>
                        <th class="px-4 py-3 border">{{ __('messages.Payment Date') }}</th>
                        <th class="px-4 py-3 border">{{ __('messages.Paid By') }}</th>
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
