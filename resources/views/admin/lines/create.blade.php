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
                <input type="text" name="phone_number" value="{{ old('phone_number') }}"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500" required>
            </div>

            {{-- Distributor --}}
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">الموزع</label>
                <input type="text" name="distributor" value="{{ old('distributor', $line->distributor ?? '') }}"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Provider --}}
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">مزود الخدمة</label>
                <select name="provider" id="provider-select" onchange="filterPlans()"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 focus:ring-2 focus:ring-blue-500" required>
                    <option value="">{{ __('messages.select_provider') }}</option>
                    @foreach(['Vodafone', 'Etisalat', 'Orange', 'WE'] as $provider)
                        <option value="{{ $provider }}" {{ old('provider') == $provider ? 'selected' : '' }}>{{ $provider }}</option>
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
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">تاريخ الدفع</label>
                <input type="date" name="last_invoice_date" value="{{ old('last_invoice_date') }}"
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
                <input type="text" id="search-nid" placeholder="أدخل الرقم القومي"
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
                    if (data) {
                        document.getElementById('full_name').value = data.full_name || '';
                        document.getElementById('email').value = data.email || '';
                        document.getElementById('birth_date').value = data.birth_date || '';
                        document.getElementById('address').value = data.address || '';
                        document.getElementById('existing_customer_id').value = data.id;
                        document.getElementById('customer-data-fields').classList.remove('hidden');
                    } else {
                        alert('{{ __("messages.customer_not_found") }}');
                    }
                })
                .catch(() => alert('{{ __("messages.error_occurred") }}'));
        }

        function filterPlans() {
            const selectedProvider = document.getElementById('provider-select').value;
            const planSelect = document.getElementById('plan-select');
            const options = planSelect.options;

            for (let i = 0; i < options.length; i++) {
                const opt = options[i];
                const planProvider = opt.getAttribute('data-provider');
                opt.style.display = (!planProvider || planProvider === selectedProvider || opt.value === '') ? 'block' : 'none';
            }

            planSelect.value = '';
        }
    </script>
    @endpush
</x-app-layout>
