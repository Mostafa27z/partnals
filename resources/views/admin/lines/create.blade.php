<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-200">{{ __('messages.add_new_line') }}</h2>
    </x-slot>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg shadow max-w-4xl mx-auto">
            {{ session('success') ?? __('messages.success_message') }}
        </div>
    @endif

    <div class="py-8 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" dir="rtl">
        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg shadow max-w-4xl mx-auto">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="font-medium">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('lines.store') }}" method="POST" 
              class="space-y-6 bg-white dark:bg-gray-800 p-8 rounded-xl shadow-md max-w-4xl mx-auto">
            @csrf

            {{-- GCode --}}
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">مقدمة الرقم (GCode)</label>
                <select name="gcode" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500" required>
                    @foreach(['010', '011', '012', '015'] as $code)
                        <option value="{{ $code }}" {{ old('gcode') == $code ? 'selected' : '' }}>{{ $code }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Phone Number --}}
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">رقم الهاتف</label>
                <input type="text" name="phone_number" maxlength="11" value="{{ old('phone_number') }}"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500" required>
            </div>

            {{-- Serial Number --}}
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">{{ __('messages.serial_number') }}</label>
                <input type="text" name="serial_number" id="serial_number" maxlength="19" value="{{ old('serial_number') }}"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500 font-mono tracking-wider"
                       placeholder="مثال: 8920012345678901234">
            </div>

            {{-- Distributor --}}
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">{{ __('messages.distributor') }}</label>
                @php $isAdmin = auth()->user()->role && auth()->user()->role->name === 'admin'; @endphp
                <select name="distributor_id" class="w-full rounded-lg {{ $isAdmin ? 'border-gray-300 dark:border-gray-600' : 'bg-gray-100 dark:bg-gray-900 cursor-not-allowed' }} dark:bg-gray-700 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500" {{ $isAdmin ? '' : 'disabled' }}>
                    <option value="">-- {{ __('messages.select_distributor') ?? 'اختر الموزع' }} --</option>
                    @foreach($distributors as $distributor)
                        <option value="{{ $distributor->id }}" {{ old('distributor_id', (!$isAdmin && auth()->id() == $distributor->id) ? auth()->id() : '') == $distributor->id ? 'selected' : '' }}>
                            {{ $distributor->name }}
                        </option>
                    @endforeach
                </select>
                @if(!$isAdmin)
                    <input type="hidden" name="distributor_id" value="{{ auth()->id() }}">
                @endif
            </div>

            {{-- Provider --}}
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">مزود الخدمة</label>
                <select name="provider" id="provider-select" onchange="filterPlans()"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500" required>
                    <option value="">{{ __('messages.select_provider') }}</option>
                    @foreach($providers as $provider)
                        <option value="{{ $provider->name }}" {{ old('provider') == $provider->name ? 'selected' : '' }}>{{ $provider->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Line Type --}}
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">نوع الخط</label>
                <select name="line_type"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500" required>
                    <option value="prepaid" {{ old('line_type') == 'prepaid' ? 'selected' : '' }}>مدفوع مسبقاً</option>
                    <option value="postpaid" {{ old('line_type') == 'postpaid' ? 'selected' : '' }}>فاتورة</option>
                </select>
            </div>

            {{-- Plan --}}
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">النظام</label>
                <select name="plan_id" id="plan-select"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500">
                    <option value="">{{ __('messages.select_plan') }}</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" data-provider="{{ $plan->provider }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }} ({{ $plan->provider }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Package --}}
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">الباقة</label>
                <input type="text" name="package" value="{{ old('package') }}"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Last Invoice Date --}}
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">{{ __('messages.last_invoice_date') ?? 'تاريخ آخر فاتورة' }}</label>
                <input type="date" name="last_invoice_date" id="last_invoice_date" value="{{ old('last_invoice_date') }}"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {{-- Buy Price --}}
                <div>
                    <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">{{ __('messages.buy_price') ?? 'سعر الشراء' }}</label>
                    <input type="number" step="0.01" name="buy_price" value="{{ old('buy_price') }}"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Sale Price --}}
                <div>
                    <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">{{ __('messages.sale_price') ?? 'سعر البيع' }}</label>
                    <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price') }}"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            {{-- Payment Date --}}
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">{{ __('messages.payment_date') ?? 'تاريخ الدفع' }}</label>
                <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date') }}"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Notes --}}
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">ملاحظات</label>
                <textarea name="notes" rows="3"
                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
            </div>

            <hr class="my-6 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">

            {{-- National ID --}}
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">الرقم القومي</label>
                <input type="text" id="search-nid" name="national_id" placeholder="أدخل الرقم القومي" maxlength="14"
                       oninput="document.getElementById('customer-data-fields').classList.remove('hidden')"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500" />
                <button type="button" onclick="loadCustomerData()"
                        class="mt-3 bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                    {{ __('messages.load_data') }}
                </button>
            </div>

            {{-- Customer Data --}}
            <div id="customer-data-fields" class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4 hidden">
                <div>
                    <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">اسم العميل</label>
                    <input type="text" name="full_name" id="full_name"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">البريد الإلكتروني</label>
                    <input type="email" name="email" id="email"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">تاريخ الميلاد</label>
                    <input type="date" name="birth_date" id="birth_date"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">العنوان</label>
                    <input type="text" name="address" id="address"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>

                <input type="hidden" name="existing_customer_id" id="existing_customer_id" />

                <div class="col-span-1 sm:col-span-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="update_customer_data" class="h-5 w-5 text-blue-600 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500">
                        <span class="font-medium">{{ __('messages.update_customer_data') }}</span>
                    </label>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex flex-col sm:flex-row justify-end gap-4 mt-8">
                <button type="submit" name="save_and_add_more" value="1"
                        class="bg-gray-500 text-white font-semibold px-6 py-3 rounded-lg hover:bg-gray-600 transition w-full sm:w-auto">
                    💾 {{ __('messages.save_and_add_more') }}
                </button>
                <button type="submit"
                        class="bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg hover:bg-blue-700 transition w-full sm:w-auto">
                    ➕ {{ __('messages.add_line') }}
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function loadCustomerData() {
            let nid = document.getElementById('search-nid').value.trim();
            if (!nid || nid.length !== 14) {
                alert('{{ __("messages.enter_valid_nid") }}');
                return;
            }
            fetch(`/admin/ajax/customer-by-nid?q=${nid}`)
                .then(res => res.json())
                .then(data => {
                    if (data && data.id) {
                        document.getElementById('full_name').value = data.full_name || '';
                        document.getElementById('email').value = data.email || '';
                        document.getElementById('birth_date').value = data.birth_date || '';
                        document.getElementById('address').value = data.address || '';
                        document.getElementById('existing_customer_id').value = data.id;
                        document.getElementById('customer-data-fields').classList.remove('hidden');
                    } else {
                        alert('{{ __("messages.customer_not_found") }}');
                        document.getElementById('existing_customer_id').value = '';
                    }
                })
                .catch(() => {
                    alert('{{ __("messages.error_occurred") }}');
                    document.getElementById('existing_customer_id').value = '';
                });
        }

        // Clear existing_customer_id if NID changes and sanitize input
        const searchNidInput = document.getElementById('search-nid');
        searchNidInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 14);
            document.getElementById('existing_customer_id').value = '';
        });

        // National ID Validation on exit
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

        function filterPlans() {
            const selectedProviderName = document.getElementById('provider-select').value;
            const planSelect = document.getElementById('plan-select');
            const options = planSelect.options;

            // Sync dates with provider day
            const providers = @json($providers);
            const selectedProvider = providers.find(p => p.name === selectedProviderName);
            
            if (selectedProvider) {
                const day = selectedProvider.invoice_day.toString().padStart(2, '0');
                const lastInvoiceInput = document.getElementById('last_invoice_date');
                const paymentInput = document.getElementById('payment_date');
                const today = new Date();
                const yyyy = today.getFullYear();
                const mm = (today.getMonth() + 1).toString().padStart(2, '0');
                
                const dateStr = `${yyyy}-${mm}-${day}`;
                lastInvoiceInput.value = dateStr;
                paymentInput.value = dateStr;
            }

            for (let i = 0; i < options.length; i++) {
                const opt = options[i];
                const planProvider = opt.getAttribute('data-provider');
                opt.style.display = (!planProvider || planProvider === selectedProviderName || opt.value === '') ? 'block' : 'none';
            }

            planSelect.value = '';
        }

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
