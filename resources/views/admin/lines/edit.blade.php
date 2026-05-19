<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 leading-tight">
            ✏️ {{ __('messages.edit_line') }}
        </h2>
    </x-slot>

    {{-- رسالة نجاح --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg shadow">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
        {{-- رسائل الأخطاء --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg shadow">
                <strong class="block mb-2">⚠️ {{ __('messages.validation_errors') }}</strong>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('lines.update', $line) }}" method="POST" class="space-y-6 bg-white dark:bg-gray-800 p-8 rounded-xl shadow-md">
            @csrf
            @method('PUT')
            <input type="hidden" name="transfer_invoices" id="transfer_invoices" value="0">

            {{-- رقم الخط --}}
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">{{ __('messages.gcode') }}</label>
                    <select name="gcode" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm px-3 py-2 focus:ring focus:ring-blue-200" required>
                        @foreach(['010', '011', '012', '015'] as $code)
                            <option value="{{ $code }}" {{ old('gcode', $line->gcode) == $code ? 'selected' : '' }}>{{ $code }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">{{ __('messages.phone_number') }}</label>
                    <input type="text" maxlength="11" value="{{ old('phone_number', $line->phone_number) }}" 
                        class="w-full bg-gray-100 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 cursor-not-allowed" disabled>
                    <input type="hidden" name="phone_number" value="{{ old('phone_number', $line->phone_number) }}">
                </div>
            </div>

            {{-- Serial Number --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">{{ __('messages.serial_number') }}</label>
                <input type="text" name="serial_number" id="serial_number" maxlength="19" value="{{ old('serial_number', $line->serial_number) }}"
                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 font-mono tracking-wider"
                       placeholder="مثال: 8920012345678901234">
            </div>

            {{-- مزود الخدمة والموزع --}}
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">{{ __('messages.distributor') }}</label>
                    @php $isAdmin = auth()->user()->role && auth()->user()->role->name === 'admin'; @endphp
                    <select name="distributor_id" class="w-full {{ $isAdmin ? 'border-gray-300 dark:border-gray-600' : 'bg-gray-100 dark:bg-gray-900 cursor-not-allowed' }} dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2" {{ $isAdmin ? '' : 'disabled' }}>
                        <option value="">-- {{ __('messages.select_distributor') ?? 'اختر الموزع' }} --</option>
                        @foreach($distributors as $distributor)
                            <option value="{{ $distributor->id }}" {{ old('distributor_id', $line->distributor_id) == $distributor->id ? 'selected' : '' }}>
                                {{ $distributor->name }}
                            </option>
                        @endforeach
                    </select>
                    @if(!$isAdmin)
                        <input type="hidden" name="distributor_id" value="{{ $line->distributor_id }}">
                    @endif
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">{{ __('messages.provider') }}</label>
                    <select name="provider" id="provider-select" onchange="onProviderChange()" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2" required>
                        @foreach($providers as $provider)
                            <option value="{{ $provider->name }}" {{ old('provider', $line->provider) == $provider->name ? 'selected' : '' }}>
                                {{ $provider->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- نوع الخط وتاريخ آخر فاتورة --}}
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">{{ __('messages.line_type') }}</label>
                    <select name="line_type" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2" required>
                        <option value="prepaid" {{ old('line_type', $line->line_type) == 'prepaid' ? 'selected' : '' }}>مدفوع مسبقاً</option>
                        <option value="postpaid" {{ old('line_type', $line->line_type) == 'postpaid' ? 'selected' : '' }}>فاتورة</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">{{ __('messages.last_invoice_date') }}</label>
                    <input type="date" name="last_invoice_date" id="last_invoice_date" 
                        class="w-full {{ $isAdmin ? 'border-gray-300 dark:border-gray-600' : 'bg-gray-100 dark:bg-gray-900 cursor-not-allowed' }} dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2" 
                        {{ $isAdmin ? '' : 'disabled' }}
                        value="{{ old('last_invoice_date', $line->last_invoice_date ? \Carbon\Carbon::parse($line->last_invoice_date)->format('Y-m-d') : '') }}">
                    @if(!$isAdmin)
                        <input type="hidden" name="last_invoice_date" value="{{ $line->last_invoice_date ? \Carbon\Carbon::parse($line->last_invoice_date)->format('Y-m-d') : '' }}">
                    @endif
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">{{ __('messages.buy_price') }}</label>
                    <input type="number" step="0.01" name="buy_price" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2"
                        value="{{ old('buy_price', $line->buy_price) }}">
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">{{ __('messages.sale_price') }}</label>
                    <input type="number" step="0.01" name="sale_price" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2"
                        value="{{ old('sale_price', $line->sale_price) }}">
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">{{ __('messages.payment_date') }}</label>
                    <input type="date" name="payment_date" id="payment_date" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2"
                        value="{{ old('payment_date', $line->payment_date ? \Carbon\Carbon::parse($line->payment_date)->format('Y-m-d') : '') }}">
                </div>
            </div>

            {{-- الخطة والباقات --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">{{ __('messages.plan') }}</label>
                <select name="plan_id" id="plan-select" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2">
                    <option value="">{{ __('messages.select_plan') }}</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" 
                                data-provider="{{ $plan->provider }}"
                                {{ old('plan_id', $line->plan_id) == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">{{ __('messages.package') }}</label>
                <input type="text" name="package" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2"
                    value="{{ old('package', $line->package) }}">
            </div>

            {{-- بيانات العميل --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">{{ __('messages.national_id') }}</label>
                <input type="text" name="national_id" id="search-nid" maxlength="14" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2"
                    value="{{ old('national_id', $line->customer?->national_id) }}"
                    placeholder="{{ __('messages.enter_national_id') }}" pattern="\d{14}">
                <button type="button" onclick="loadCustomerData()" class="mt-2 bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700">
                    {{ __('messages.load_data') }}
                </button>
            </div>

            <div id="customer-data-fields" class="grid grid-cols-2 gap-6 mt-4">
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">{{ __('messages.customer_name') }}</label>
                    <input type="text" name="full_name" id="full_name" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2"
                        value="{{ old('full_name', $line->customer?->full_name) }}">
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">{{ __('messages.email') }}</label>
                    <input type="email" name="email" id="email" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2"
                        value="{{ old('email', $line->customer?->email) }}">
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">{{ __('messages.birth_date') }}</label>
                    <input type="date" name="birth_date" id="birth_date" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2"
                        value="{{ old('birth_date', $line->customer?->birth_date) }}">
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">{{ __('messages.address') }}</label>
                    <input type="text" name="address" id="address" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2"
                        value="{{ old('address', $line->customer?->address) }}">
                </div>

                <input type="hidden" name="existing_customer_id" id="existing_customer_id" value="{{ $line->customer_id }}" />

                <div class="col-span-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" id="update_customer_data" name="update_customer_data" checked class="mr-2">
                        {{ __('messages.update_customer_data') }}
                    </label>
                </div>
            </div>

            {{-- الملاحظات --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">{{ __('messages.notes') }}</label>
                <textarea name="notes" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2">{{ old('notes', $line->notes) }}</textarea>
            </div>

            {{-- الأزرار --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('lines.all') }}" class="bg-gray-500 text-white px-5 py-2 rounded-lg shadow hover:bg-gray-600">
                    ❌ {{ __('messages.cancel') }}
                </a>
                <button type="submit" class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-lg shadow hover:bg-blue-700">
                     {{ __('messages.save_changes') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Invoice Transfer Confirmation Modal -->
    <div id="invoice-transfer-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- Center modal contents -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 border border-gray-100 dark:border-gray-700" dir="rtl">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/30 sm:mx-0 sm:h-10 sm:w-10">
                        <span class="text-xl">📊</span>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:mr-4 sm:text-right">
                        <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white" id="modal-title">
                            هل ترغب في نقل الفواتير السابقة؟
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                لقد قمت بتغيير عميل هذا الخط. هل ترغب في نقل جميع الفواتير السابقة المرتبطة بهذا الخط إلى العميل الجديد، أم الاحتفاظ بها تحت اسم العميل القديم؟
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex flex-col sm:flex-row-reverse gap-3">
                    <button type="button" id="confirm-transfer-yes" class="w-full inline-flex justify-center rounded-2xl border border-transparent shadow-sm px-4 py-2.5 bg-blue-600 text-base font-bold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm transition-all">
                        نعم، انقل الفواتير للعميل الجديد
                    </button>
                    <button type="button" id="confirm-transfer-no" class="w-full inline-flex justify-center rounded-2xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2.5 bg-white dark:bg-gray-700 text-base font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm transition-all">
                        لا، ابق الفواتير مع العميل القديم
                    </button>
                    <button type="button" id="confirm-transfer-cancel" class="w-full inline-flex justify-center rounded-2xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2.5 bg-gray-100 dark:bg-gray-800 text-base font-bold text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm transition-all">
                        إلغاء التعديل
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector('form[action*="lines.update"]');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const originalCustomerId = "{{ $line->customer_id ?? '' }}";
                    const originalNid = "{{ $line->customer?->national_id ?? '' }}";
                    const originalFullName = "{{ $line->customer?->full_name ?? '' }}";

                    const currentCustomerId = document.getElementById('existing_customer_id')?.value || '';
                    const currentNid = document.getElementById('search-nid')?.value || '';
                    const currentFullName = document.getElementById('full_name')?.value || '';

                    const isCustomerChanged = (currentCustomerId !== originalCustomerId) || 
                                              (currentNid !== originalNid) || 
                                              (currentFullName !== originalFullName);

                    if (isCustomerChanged) {
                        e.preventDefault(); // Stop normal submission

                        // Show the custom confirmation modal
                        const modal = document.getElementById('invoice-transfer-modal');
                        modal.classList.remove('hidden');

                        // Handle YES click
                        document.getElementById('confirm-transfer-yes').onclick = function() {
                            document.getElementById('transfer_invoices').value = '1';
                            modal.classList.add('hidden');
                            form.submit();
                        };

                        // Handle NO click
                        document.getElementById('confirm-transfer-no').onclick = function() {
                            document.getElementById('transfer_invoices').value = '0';
                            modal.classList.add('hidden');
                            form.submit();
                        };

                        // Handle CANCEL click
                        document.getElementById('confirm-transfer-cancel').onclick = function() {
                            modal.classList.add('hidden');
                        };
                    }
                });
            }
        });
        function onProviderChange() {
            syncProviderDay();
            filterPlans();
        }

        function filterPlans() {
            const selectedProviderName = document.getElementById('provider-select').value;
            const planSelect = document.getElementById('plan-select');
            const options = planSelect.getElementsByTagName('option');

            for (let i = 0; i < options.length; i++) {
                const opt = options[i];
                const planProvider = opt.getAttribute('data-provider');
                
                // Show option if it matches provider, or if it's the "Select Plan" option
                if (!planProvider || planProvider === selectedProviderName || opt.value === '') {
                    opt.style.display = 'block';
                    opt.disabled = false;
                } else {
                    opt.style.display = 'none';
                    opt.disabled = true;
                }
            }

            // If current selected plan doesn't match new provider, reset it
            const currentSelected = planSelect.options[planSelect.selectedIndex];
            if (currentSelected && currentSelected.disabled) {
                planSelect.value = '';
            }
        }

        function syncProviderDay() {
            const providers = @json($providers);
            const selectedProviderName = document.getElementById('provider-select').value;
            const selectedProvider = providers.find(p => p.name === selectedProviderName);
            
            if (selectedProvider) {
                const day = selectedProvider.invoice_day.toString().padStart(2, '0');
                const lastInvoiceInput = document.getElementById('last_invoice_date');
                const paymentInput = document.getElementById('payment_date');
                
                // Get current month/year from input or today
                let currentVal = lastInvoiceInput.value || new Date().toISOString().split('T')[0];
                let [yyyy, mm, dd] = currentVal.split('-');
                
                const newDateStr = `${yyyy}-${mm}-${day}`;
                lastInvoiceInput.value = newDateStr;
                paymentInput.value = newDateStr;
            }
        }
        
        // Initial filter on load
        window.addEventListener('DOMContentLoaded', filterPlans);

        // Enforce provider day on manual date change
        function enforceProviderDay(input) {
            const providers = @json($providers);
            const selectedProviderName = document.getElementById('provider-select').value;
            const selectedProvider = providers.find(p => p.name === selectedProviderName);
            
            if (selectedProvider && input.value) {
                const day = selectedProvider.invoice_day.toString().padStart(2, '0');
                let [yyyy, mm, dd] = input.value.split('-');
                if (dd !== day) {
                    input.value = `${yyyy}-${mm}-${day}`;
                }
            }
        }

        document.getElementById('last_invoice_date').addEventListener('change', function() { enforceProviderDay(this); });
        document.getElementById('payment_date').addEventListener('change', function() { enforceProviderDay(this); });

        function loadCustomerData() {
            let nid = document.getElementById('search-nid').value.trim();
            if (!/^\d{14}$/.test(nid)) {
                alert('{{ __("messages.nid_invalid") }}');
                return;
            }
            let btn = document.querySelector('[onclick="loadCustomerData()"]');
            btn.innerHTML = '⏳ جاري التحميل...';
            btn.disabled = true;
            fetch(`/admin/ajax/customer-by-nid?q=${nid}`)
                .then(res => res.ok ? res.json() : Promise.reject('Network error'))
                .then(data => {
                    if (data.error) throw new Error(data.error);
                    document.getElementById('full_name').value = data.full_name || '';
                    document.getElementById('email').value = data.email || '';
                    document.getElementById('birth_date').value = data.birth_date || '';
                    document.getElementById('address').value = data.address || '';
                    document.getElementById('existing_customer_id').value = data.id;
                })
                .catch(err => {
                    alert('{{ __("messages.no_customer_or_error") }} ' + err.message);
                    document.getElementById('existing_customer_id').value = '';
                })
                .finally(() => {
                    btn.innerHTML = '{{ __("messages.load_data") }}';
                    btn.disabled = false;
                });
        }

        const searchNidInput = document.getElementById('search-nid');
        if (searchNidInput) {
            searchNidInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 14);
            });

            searchNidInput.addEventListener('blur', function() {
                const value = searchNidInput.value.trim();
                let errEl = searchNidInput.nextElementSibling;
                if (!errEl || !errEl.classList.contains('nid-error-msg')) {
                    errEl = document.createElement('span');
                    errEl.className = 'nid-error-msg text-red-500 text-sm mt-1 block font-bold';
                    searchNidInput.parentNode.insertBefore(errEl, searchNidInput.nextSibling);
                }

                const isAr = document.documentElement.lang === 'ar';
                const lengthMsg = isAr ? 'الرقم القومي يجب أن يتكون من 14 رقماً' : 'National ID must be 14 digits';

                if (value !== '' && value.length !== 14) {
                    errEl.textContent = lengthMsg;
                    searchNidInput.classList.add('border-red-500', 'focus:ring-red-500');
                } else {
                    errEl.textContent = '';
                    searchNidInput.classList.remove('border-red-500', 'focus:ring-red-500');
                }
            });
        }

        // Serial number validation & numeric restriction
        const serialInput = document.getElementById('serial_number');
        if (serialInput) {
            serialInput.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '');
            });

            serialInput.addEventListener('blur', function() {
                const val = this.value.trim();
                let errEl = this.nextElementSibling;
                if (errEl && errEl.classList.contains('serial-error-msg')) {
                    errEl.remove();
                }
                this.classList.remove('border-red-500', 'focus:ring-red-500');

                if (val !== '' && val.length !== 19) {
                    const isAr = document.documentElement.lang === 'ar';
                    const lengthMsg = isAr ? 'يجب أن يتكون الرقم التسلسلي من 19 رقماً بالضبط' : 'Serial number must be exactly 19 digits';
                    
                    errEl = document.createElement('span');
                    errEl.className = 'serial-error-msg text-red-500 text-sm mt-1 block font-bold';
                    errEl.textContent = lengthMsg;
                    this.parentNode.insertBefore(errEl, this.nextSibling);
                    this.classList.add('border-red-500', 'focus:ring-red-500');
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
