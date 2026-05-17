<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200">
                {{ __('messages.Customer Invoices') }}: {{ $customer->full_name }}
            </h2>
            <div class="mt-2 md:mt-0 text-green-700 font-bold text-xl">
                💰 {{ __('messages.Total Invoices') }}: {{ number_format($total, 2) }} {{ __('messages.EGP') }}
            </div>
        </div>
    </x-slot>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('invoices.index') }}" 
          class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 p-6 bg-white dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-lg max-w-7xl mx-auto border border-gray-200 dark:border-gray-700">
        
        {{-- Provider --}}
        <div>
            <label class="block font-semibold text-lg mb-2 text-gray-700 dark:text-gray-300">{{ __('messages.Provider') }}</label>
            <select name="provider[]" multiple class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white p-3 rounded-lg focus:ring focus:ring-blue-300 text-base">
                @foreach(['Vodafone', 'Etisalat', 'Orange', 'WE'] as $p)
                    <option value="{{ $p }}" {{ in_array($p, request('provider', [])) ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
            </select>
        </div>

        {{-- Line Type --}}
        <div>
            <label class="block font-semibold text-lg mb-2 text-gray-700 dark:text-gray-300">{{ __('messages.Line Type') }}</label>
            <select name="line_type[]" multiple class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white p-3 rounded-lg focus:ring focus:ring-blue-300 text-base">
                <option value="prepaid" {{ in_array('prepaid', request('line_type', [])) ? 'selected' : '' }}>{{ __('messages.Prepaid') }}</option>
                <option value="postpaid" {{ in_array('postpaid', request('line_type', [])) ? 'selected' : '' }}>{{ __('messages.Postpaid') }}</option>
            </select>
        </div>

        {{-- Plan --}}
        <div>
            <label class="block font-semibold text-lg mb-2 text-gray-700 dark:text-gray-300">{{ __('messages.Plan') }}</label>
            <select name="plan_id[]" multiple class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white p-3 rounded-lg focus:ring focus:ring-blue-300 text-base">
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ in_array($plan->id, request('plan_id', [])) ? 'selected' : '' }}>{{ $plan->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Payment Status --}}
        <div>
            <label class="block font-semibold text-lg mb-2 text-gray-700 dark:text-gray-300">{{ __('messages.Payment Status') }}</label>
            <select name="is_paid[]" multiple class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white p-3 rounded-lg focus:ring focus:ring-blue-300 text-base">
                <option value="1" {{ in_array('1', request('is_paid', [])) ? 'selected' : '' }}>{{ __('messages.Paid') }}</option>
                <option value="0" {{ in_array('0', request('is_paid', [])) ? 'selected' : '' }}>{{ __('messages.Unpaid') }}</option>
            </select>
        </div>

        {{-- Date From --}}
        <div>
            <label class="block font-semibold text-lg mb-2 text-gray-700 dark:text-gray-300">{{ __('messages.Date From') }}</label>
            <input type="date" name="from" value="{{ request('from') }}" 
                   class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white p-3 rounded-lg focus:ring focus:ring-blue-300 text-base">
        </div>

        {{-- Date To --}}
        <div>
            <label class="block font-semibold text-lg mb-2 text-gray-700 dark:text-gray-300">{{ __('messages.Date To') }}</label>
            <input type="date" name="to" value="{{ request('to') }}" 
                   class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white p-3 rounded-lg focus:ring focus:ring-blue-300 text-base">
        </div>

        <div class="md:col-span-3 flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 text-lg rounded-lg hover:bg-blue-700 transition shadow-md">
                🔍 {{ __('messages.Filter') }}
            </button>
        </div>
    </form>

    {{-- Invoices Table --}}
    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-lg overflow-x-auto border border-gray-200 dark:border-gray-700">
            <table class="min-w-full table-auto text-center text-gray-800 dark:text-gray-200 text-lg">
                <thead class="bg-gray-100 dark:bg-gray-900 font-bold text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-3">{{ __('messages.Month') }}</th>
                        <th class="px-6 py-3">{{ __('messages.Amount') }}</th>
                        <th class="px-6 py-3">{{ __('messages.Paid') }}</th>
                        <th class="px-6 py-3">{{ __('messages.Payment Date') }}</th>
                        <th class="px-6 py-3">{{ __('messages.Paid By') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                        <tr class="border-t hover:bg-gray-50 dark:bg-gray-700/50 transition">
                            <td class="px-6 py-3">{{ \Carbon\Carbon::parse($invoice->invoice_month)->translatedFormat('F Y') }}</td>
                            <td class="px-6 py-3">{{ $invoice->amount }} {{ __('messages.EGP') }}</td>
                            <td class="px-6 py-3">
                                @if($invoice->is_paid)
                                    <span class="text-green-600 font-semibold">{{ __('messages.Yes') }}</span>
                                @else
                                    <span class="text-red-600 font-semibold">{{ __('messages.No') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">{{ $invoice->payment_date ? \Carbon\Carbon::parse($invoice->payment_date)->format('Y-m-d H:i:s') : '-' }}</td>
                            <td class="px-6 py-3">{{ $invoice->user?->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-gray-500 dark:text-gray-400">{{ __('messages.No invoices found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $invoices->appends(request()->query())->links() }}
        </div>
    </div>
</x-app-layout>
