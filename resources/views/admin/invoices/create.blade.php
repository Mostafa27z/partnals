<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
            💳 دفع فواتير - {{ $line->customer->full_name ?? 'غير مربوط بعميل' }}
        </h2>
    </x-slot>

    <!-- Notifications Wrapper -->
    <div class="max-w-lg mx-auto mt-6">
        @if(session('success'))
            <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-300 rounded-xl border border-emerald-100 dark:border-emerald-900/30 shadow-sm flex items-center gap-3 font-bold">
                <span class="text-xl">✅</span>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-rose-50 dark:bg-rose-950/30 text-rose-800 dark:text-rose-300 rounded-xl border border-rose-100 dark:border-rose-900/30 shadow-sm flex items-center gap-3 font-bold">
                <span class="text-xl">❌</span>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 bg-rose-50 dark:bg-rose-950/30 text-rose-800 dark:text-rose-300 rounded-xl border border-rose-100 dark:border-rose-900/30 shadow-sm space-y-1">
                <div class="flex items-center gap-3 font-bold mb-1">
                    <span class="text-xl">⚠️</span>
                    <div>يرجى تصحيح الأخطاء التالية:</div>
                </div>
                <ul class="list-disc list-inside text-xs font-semibold space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

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

        {{-- خيارات الدفع --}}
        <div class="space-y-3">
            <label class="block text-lg font-semibold text-gray-700 dark:text-gray-300">
                {{ __('messages.Payment Option') }}
            </label>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <!-- Option 1: Default -->
                <label class="relative flex flex-col p-4 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl cursor-pointer hover:border-blue-500 dark:hover:border-blue-500 transition-all select-none shadow-sm">
                    <input type="radio" name="payment_option" value="default" class="sr-only" checked>
                    <span class="font-bold text-gray-900 dark:text-white mb-1">{{ __('messages.Default Plan Price') }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">دفع السعر الأساسي للباقة</span>
                    <div class="absolute inset-0 border-2 border-transparent rounded-xl pointer-events-none active-border"></div>
                </label>

                <!-- Option 2: Custom per month -->
                <label class="relative flex flex-col p-4 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl cursor-pointer hover:border-blue-500 dark:hover:border-blue-500 transition-all select-none shadow-sm">
                    <input type="radio" name="payment_option" value="custom_per_month" class="sr-only">
                    <span class="font-bold text-gray-900 dark:text-white mb-1">{{ __('messages.Custom Price Per Month') }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">تحديد أسعار مختلفة لكل شهر</span>
                    <div class="absolute inset-0 border-2 border-transparent rounded-xl pointer-events-none active-border"></div>
                </label>

                <!-- Option 3: Total divided -->
                <label class="relative flex flex-col p-4 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl cursor-pointer hover:border-blue-500 dark:hover:border-blue-500 transition-all select-none shadow-sm">
                    <input type="radio" name="payment_option" value="total_divided" class="sr-only">
                    <span class="font-bold text-gray-900 dark:text-white mb-1">{{ __('messages.Total Divided Evenly') }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">تحديد مبلغ كلي وتقسيمه بالتساوي</span>
                    <div class="absolute inset-0 border-2 border-transparent rounded-xl pointer-events-none active-border"></div>
                </label>
            </div>
        </div>

        {{-- عدد الأشهر --}}
        <div>
            <label for="months-count" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                {{ __('messages.Months to Pay') }}
            </label>
            <input type="number" name="months_count" id="months-count" min="1" required
                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                value="{{ old('months_count', 1) }}">
        </div>

        {{-- إجمالي المبلغ المراد دفعه لتقسيمه بالتساوي (يظهر فقط لخيار total_divided) --}}
        <div id="total-divided-wrapper" class="hidden">
            <label for="total-amount-input" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                {{ __('messages.Total Amount to Pay') }}
            </label>
            <input type="number" step="0.01" name="total_amount" id="total-amount-input"
                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                value="{{ old('total_amount') }}">
        </div>

        {{-- تخصيص المبالغ لكل شهر (يظهر فقط لخيار custom_per_month) --}}
        <div id="custom-months-wrapper" class="hidden space-y-4">
            <label class="block text-lg font-semibold text-gray-700 dark:text-gray-300">
                تحديد أسعار الأشهر
            </label>
            <div id="custom-months-container" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- سيتم توليده بالـ JavaScript --}}
            </div>
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

        // New elements
        const radioOptions = document.querySelectorAll('input[name="payment_option"]');
        const totalDividedWrapper = document.getElementById('total-divided-wrapper');
        const totalAmountInput = document.getElementById('total-amount-input');
        const customMonthsWrapper = document.getElementById('custom-months-wrapper');
        const customMonthsContainer = document.getElementById('custom-months-container');

        // Toggle radio card active border styles
        function updateRadioStyles() {
            radioOptions.forEach(radio => {
                const card = radio.closest('label');
                const border = card.querySelector('.active-border');
                if (radio.checked) {
                    card.classList.add('border-blue-500', 'bg-blue-50/30', 'dark:bg-blue-900/20');
                    card.classList.remove('border-gray-200', 'dark:border-gray-600');
                    border.classList.remove('border-transparent');
                    border.classList.add('border-blue-500');
                } else {
                    card.classList.remove('border-blue-500', 'bg-blue-50/30', 'dark:bg-blue-900/20');
                    card.classList.add('border-gray-200', 'dark:border-gray-600');
                    border.classList.add('border-transparent');
                    border.classList.remove('border-blue-500');
                }
            });
        }

        // Get selected option
        function getSelectedOption() {
            let val = 'default';
            radioOptions.forEach(radio => {
                if (radio.checked) val = radio.value;
            });
            return val;
        }

        // Main recalculate function
        function recalculate() {
            const months = parseInt(monthsInput.value) || 0;
            const option = getSelectedOption();

            // Toggle wrapper visibility
            if (option === 'default') {
                totalDividedWrapper.classList.add('hidden');
                customMonthsWrapper.classList.add('hidden');
                totalPrice.value = (months * monthlyPrice).toFixed(2);
                totalAmountInput.removeAttribute('required');
            } else if (option === 'total_divided') {
                totalDividedWrapper.classList.remove('hidden');
                customMonthsWrapper.classList.add('hidden');
                totalAmountInput.setAttribute('required', 'required');
                
                const enteredTotal = parseFloat(totalAmountInput.value) || 0;
                totalPrice.value = enteredTotal.toFixed(2);
            } else if (option === 'custom_per_month') {
                totalDividedWrapper.classList.add('hidden');
                customMonthsWrapper.classList.remove('hidden');
                totalAmountInput.removeAttribute('required');

                // Re-generate custom month inputs dynamically
                syncCustomInputs(months);
                
                // Calculate custom sum
                let sum = 0;
                customMonthsContainer.querySelectorAll('.custom-month-input').forEach(input => {
                    sum += parseFloat(input.value) || 0;
                });
                totalPrice.value = sum.toFixed(2);
            }

            // Calculate Period Range
            if (months > 0) {
                const start = new Date(startDateInput);
                const end = new Date(start);
                end.setMonth(start.getMonth() + months - 1);

                const options = { year: 'numeric', month: 'long' };
                const locale = document.documentElement.lang === 'en' ? 'en-US' : 'ar-EG';
                const from = start.toLocaleDateString(locale, options);
                const to = end.toLocaleDateString(locale, options);

                periodRange.value = document.documentElement.lang === 'en' 
                    ? `From ${from} to ${to}` 
                    : `من ${from} إلى ${to}`;
            } else {
                periodRange.value = '';
            }
        }

        function syncCustomInputs(months) {
            const currentInputs = customMonthsContainer.querySelectorAll('.custom-month-input');
            const currentCount = currentInputs.length;

            if (currentCount === months) return;

            // Preserve existing custom values
            const existingValues = [];
            currentInputs.forEach(input => {
                existingValues.push(input.value);
            });

            customMonthsContainer.innerHTML = '';

            for (let i = 0; i < months; i++) {
                const val = existingValues[i] !== undefined ? existingValues[i] : monthlyPrice.toFixed(2);
                
                const start = new Date(startDateInput);
                start.setMonth(start.getMonth() + i);
                const locale = document.documentElement.lang === 'en' ? 'en-US' : 'ar-EG';
                const monthName = start.toLocaleDateString(locale, { month: 'long', year: 'numeric' });

                const labelText = document.documentElement.lang === 'en' 
                    ? `Month ${i + 1} (${monthName})` 
                    : `الشهر ${i + 1} (${monthName})`;

                const wrapper = document.createElement('div');
                wrapper.className = 'bg-gray-50 dark:bg-gray-900 p-4 rounded-xl border border-gray-200 dark:border-gray-700 space-y-2';
                wrapper.innerHTML = `
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">
                        ${labelText}
                    </label>
                    <input type="number" step="0.01" name="amounts[]" value="${val}" required
                        class="custom-month-input w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                `;
                
                wrapper.querySelector('.custom-month-input').addEventListener('input', recalculate);
                
                customMonthsContainer.appendChild(wrapper);
            }
        }

        // Attach listeners
        monthsInput.addEventListener('input', recalculate);
        totalAmountInput.addEventListener('input', recalculate);
        radioOptions.forEach(radio => {
            radio.addEventListener('change', () => {
                updateRadioStyles();
                recalculate();
            });
        });

        // Initialize state
        updateRadioStyles();
        recalculate();
    });
    </script>
    @endpush
</x-app-layout>
