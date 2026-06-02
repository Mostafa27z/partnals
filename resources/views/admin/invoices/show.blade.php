<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 flex items-center gap-3">
                <span class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-violet-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none">
                    <span class="text-white text-lg">📄</span>
                </span>
                {{ __('messages.Invoice Details') }}
            </h2>
            <a href="{{ route('invoices.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold text-sm hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                ← {{ __('messages.Back to list') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50">
            <table class="w-full text-left text-sm">
                <tbody>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300 w-1/4">{{ __('messages.Customer') }}</th>
                        <td class="px-4 py-3">{{ $invoice->customer->full_name ?? $invoice->line->customer->full_name ?? '-' }}</td>
                    </tr>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">{{ __('messages.Phone') }}</th>
                        <td class="px-4 py-3">{{ $invoice->line->phone_number ?? '-' }}</td>
                    </tr>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">{{ __('messages.Month') }}</th>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($invoice->invoice_month)->translatedFormat('F Y') }}</td>
                    </tr>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">👤 {{ __('messages.Customer Price') }}</th>
                        <td class="px-4 py-3 text-green-600 font-bold">{{ number_format($invoice->amount, 2) }}</td>
                    </tr>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">📡 {{ __('messages.Operator Price') }}</th>
                        <td class="px-4 py-3 text-amber-600 font-bold">{{ number_format($invoice->operator_price, 2) }}</td>
                    </tr>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-3">📈 {{ __('messages.Profit') }}</th>
                        <td class="px-4 py-3 {{ $invoice->calculated_profit >= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-bold">{{ number_format($invoice->calculated_profit, 2) }}</td>
                    </tr>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">{{ __('messages.Paid') }}</th>
                        <td class="px-4 py-3">
                            @if($invoice->is_paid)
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">✔ {{ __('messages.Yes') }}</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">✖ {{ __('messages.No') }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">{{ __('messages.Payment Date') }}</th>
                        <td class="px-4 py-3">{{ $invoice->payment_date ? \Carbon\Carbon::parse($invoice->payment_date)->format('Y-m-d H:i:s') : '-' }}</td>
                    </tr>
                    <tr>
                        <th class="px-4 py-3 font-medium text-gray-600 dark:text-gray-300">{{ __('messages.Paid By') }}</th>
                        <td class="px-4 py-3">{{ $invoice->user->name ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
