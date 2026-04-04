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
                    <input type="text" value="{{ old('phone_number', $line->phone_number) }}" 
                        class="w-full bg-gray-100 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 cursor-not-allowed" disabled>
                    <input type="hidden" name="phone_number" value="{{ old('phone_number', $line->phone_number) }}">
                </div>
            </div>

            {{-- مزود الخدمة والموزع --}}
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">{{ __('messages.distributor') }}</label>
                    <input type="text" name="distributor" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2"
                        value="{{ old('distributor', $line->distributor) }}">
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">{{ __('messages.provider') }}</label>
                    <select name="provider" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2" required>
                        @foreach(['Vodafone', 'Etisalat', 'Orange', 'WE'] as $provider)
                            <option value="{{ $provider }}" {{ old('provider', $line->provider) == $provider ? 'selected' : '' }}>
                                {{ $provider }}
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
                    <input type="date" name="last_invoice_date" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2"
                        value="{{ old('last_invoice_date', \Carbon\Carbon::parse($line->last_invoice_date)->format('Y-m-d')) }}">
                </div>
            </div>

            {{-- الخطة والباقات --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">{{ __('messages.plan') }}</label>
                <select name="plan_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2">
                    <option value="">{{ __('messages.select_plan') }}</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('plan_id', $line->plan_id) == $plan->id ? 'selected' : '' }}>
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
                <input type="text" name="national_id" id="search-nid" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2"
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

    @push('scripts')
    <script>
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
    </script>
    @endpush
</x-app-layout>
