<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
            💳 دفع فواتير - {{ $line->customer->full_name ?? 'غير مربوط بعميل' }}
        </h2>
    </x-slot>

    <form action="{{ route('invoices.store', $line) }}" method="POST"
        class="max-w-lg mx-auto bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg mt-8 space-y-6">
        @csrf

        @php
            $plan = $line->plan;
            $monthlyPrice = $plan?->price ?? 0;
        @endphp

        {{-- رقم الهاتف --}}
        <div>
            <label for="phone_number" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                {{ __('messages.Phone Number') }}
            </label>
            <input type="text" id="phone_number" disabled
                class="w-full bg-gray-100 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-3 text-gray-800 dark:text-gray-200"
                value="{{ $line->phone_number }}">
        </div>

        {{-- الخطة --}}
        <div>
            <label for="plan_name" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                {{ __('messages.Line Plan') }}
            </label>
            <input type="text" id="plan_name" disabled
                class="w-full bg-gray-100 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-3 text-gray-800 dark:text-gray-200"
                value="{{ $plan?->name ?? __('messages.No Plan') }}">
        </div>

        {{-- السعر الشهري --}}
        <div>
            <label for="monthly-price" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                {{ __('messages.Monthly Price') }}
            </label>
            <input type="text" id="monthly-price" disabled
                class="w-full bg-gray-100 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-3 font-mono text-gray-800 dark:text-gray-200"
                value="{{ $monthlyPrice }}">
        </div>

        {{-- عدد الأشهر --}}
        <div>
            <label for="months-count" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                {{ __('messages.Months to Pay') }}
            </label>
            <input type="number" name="months_count" id="months-count" min="1" required
                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- الإجمالي --}}
        <div>
            <label for="total-price" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                {{ __('messages.Total Price') }}
            </label>
            <input type="text" id="total-price" readonly
                class="w-full bg-gray-100 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-3 font-mono text-gray-800 dark:text-gray-200 cursor-not-allowed">
        </div>

        {{-- الفترة --}}
        <div>
            <label for="period-range" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                {{ __('messages.Payment Period') }}
            </label>
            <input type="text" id="period-range" readonly
                class="w-full bg-gray-100 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-3 text-gray-800 dark:text-gray-200 cursor-not-allowed">
        </div>

        {{-- الملاحظات --}}
        <div>
            <label for="notes" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                {{ __('messages.Notes') }}
            </label>
            <textarea name="notes" id="notes" rows="3"
                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
        </div>

        {{-- زر الدفع --}}
        <div class="text-end">
            <button type="submit"
                class="bg-blue-600 text-white font-semibold text-lg px-8 py-3 rounded-lg hover:bg-blue-700 transition duration-200">
                {{ __('messages.Pay') }}
            </button>
        </div>
    </form>

    <input type="hidden" id="start-date" value="{{ $startDate->format('Y-m-d') }}">

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const monthlyPrice = parseFloat(document.getElementById('monthly-price').value) || 0;
        const monthsInput = document.getElementById('months-count');
        const totalPrice = document.getElementById('total-price');
        const periodRange = document.getElementById('period-range');
        const startDateInput = document.getElementById('start-date').value;

        monthsInput.addEventListener('input', function() {
            const months = parseInt(monthsInput.value) || 0;
            totalPrice.value = (months * monthlyPrice).toFixed(2);

            if (months > 0) {
                const start = new Date(startDateInput);
                const end = new Date(start);
                end.setMonth(start.getMonth() + months - 1);

                const options = { year: 'numeric', month: 'long' };
                const from = start.toLocaleDateString('ar-EG', options);
                const to = end.toLocaleDateString('ar-EG', options);

                periodRange.value = `من ${from} إلى ${to}`;
            } else {
                periodRange.value = '';
            }
        });
    });
    </script>
    @endpush
</x-app-layout>
