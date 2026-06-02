<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                {{ __('messages.Line Invoices') }}: 
                <span class="text-blue-600">{{ $line->phone_number }}</span>
            </h2>
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg shadow-sm font-semibold">
                💰 {{ __('messages.Total') }}: {{ number_format($total, 2) }} {{ __('messages.EGP') }}
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Filter Form --}}
        <form method="GET" action="{{ route('invoices.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 bg-white dark:bg-gray-800 p-6 mb-8 rounded-lg shadow-md border border-gray-100">
            {{-- Provider --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.Provider') }}</label>
                <select name="provider[]" multiple class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2">
                    @foreach(['Vodafone', 'Etisalat', 'Orange', 'WE'] as $p)
                        <option value="{{ $p }}" {{ in_array($p, request('provider', [])) ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Line Type --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.Line Type') }}</label>
                <select name="line_type[]" multiple class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2">
                    <option value="prepaid" {{ in_array('prepaid', request('line_type', [])) ? 'selected' : '' }}>{{ __('messages.Prepaid') }}</option>
                    <option value="postpaid" {{ in_array('postpaid', request('line_type', [])) ? 'selected' : '' }}>{{ __('messages.Postpaid') }}</option>
                </select>
            </div>

            {{-- Plan --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.Plan') }}</label>
                <select name="plan_id[]" multiple class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2">
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ in_array($plan->id, request('plan_id', [])) ? 'selected' : '' }}>{{ $plan->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Payment Status --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.Payment Status') }}</label>
                <select name="is_paid[]" multiple class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2">
                    <option value="1" {{ in_array('1', request('is_paid', [])) ? 'selected' : '' }}>{{ __('messages.Paid') }}</option>
                    <option value="0" {{ in_array('0', request('is_paid', [])) ? 'selected' : '' }}>{{ __('messages.Unpaid') }}</option>
                </select>
            </div>

            {{-- Date From --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.From Date') }}</label>
                <input type="date" name="from" value="{{ request('from') }}" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2">
            </div>

            {{-- Date To --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.To Date') }}</label>
                <input type="date" name="to" value="{{ request('to') }}" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2">
            </div>

            {{-- Filter Button --}}
            <div class="md:col-span-2 lg:col-span-3 flex justify-end mt-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md shadow hover:bg-blue-700 transition flex items-center gap-2">
                    🔍 {{ __('messages.Filter') }}
                </button>
            </div>
        </form>

        {{-- Invoices Table --}}
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 border border-gray-100">
            @if($invoices->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-center text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 uppercase tracking-wide text-xs">
                            <tr>
                                <th class="px-4 py-3">{{ __('messages.Amount') }}</th>
                                <th class="px-4 py-3">{{ __('messages.Created At') }}</th>
                                <th class="px-4 py-3">{{ __('messages.Notes') }}</th>
                                <th class="px-4 py-3">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100">
                            @foreach ($invoices as $invoice)
                                <tr class="hover:bg-gray-50 dark:bg-gray-700/50 transition">
                                    <td class="px-4 py-3 font-semibold text-gray-800 dark:text-gray-200">{{ $invoice->amount }} {{ __('messages.EGP') }}</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $invoice->created_at->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400 italic">{{ $invoice->notes ?: '—' }}</td>
                                    <td class="px-4 py-3">
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
                </div>

                <div class="mt-6">
                    {{ $invoices->appends(request()->query())->links() }}
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-center mt-4">{{ __('messages.No invoices found for this line.') }}</p>
            @endif
        </div>
    </div>
</x-app-layout>
