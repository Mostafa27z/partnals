<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.Add New Customer') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg shadow">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-base">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('customers.store') }}" method="POST" class="space-y-8 bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg">
            @csrf

            <!-- Basic Info -->
            {{-- <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 border-b dark:border-gray-700 pb-2">{{ __('messages.Basic Information') }}</h3> --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-medium text-gray-700 dark:text-gray-300 text-base">{{ __('messages.Full Name') }}</label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 dark:text-gray-300 text-base">{{ __('messages.National ID') }}</label>
                    <input type="text" name="national_id" id="national_id" value="{{ old('national_id') }}" maxlength="14" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 dark:text-gray-300 text-base">{{ __('messages.Birth Date') }}</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block font-medium text-gray-700 dark:text-gray-300 text-base">{{ __('messages.Email') }}</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block font-medium text-gray-700 dark:text-gray-300 text-base">{{ __('messages.Address') }}</label>
                    <input type="text" name="address" value="{{ old('address') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block font-medium text-gray-700 dark:text-gray-300 text-base">رقم التواصل</label>
                    <input type="text" name="contact_number" maxlength="11" value="{{ old('contact_number') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block font-medium text-gray-700 dark:text-gray-300 text-base">رقم الواتساب</label>
                    <input type="text" name="whatsapp_number" maxlength="11" value="{{ old('whatsapp_number') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- First Line Info -->
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 border-b dark:border-gray-700 pb-2">{{ __('messages.First Line Info') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-medium text-gray-700 dark:text-gray-300 text-base">{{ __('messages.Phone Number') }}</label>
                    <input type="text" name="phone_number" maxlength="11" value="{{ old('phone_number') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 dark:text-gray-300 text-base">{{ __('messages.Provider') }}</label>
                    <select name="provider" id="provider" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('messages.Choose Provider') }}</option>
                        <option value="vodafone" {{ old('provider') == 'vodafone' ? 'selected' : '' }}>Vodafone</option>
                        <option value="orange" {{ old('provider') == 'orange' ? 'selected' : '' }}>Orange</option>
                        <option value="etisalat" {{ old('provider') == 'etisalat' ? 'selected' : '' }}>Etisalat</option>
                        <option value="we" {{ old('provider') == 'we' ? 'selected' : '' }}>WE</option>
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 dark:text-gray-300 text-base">{{ __('messages.Plan') }}</label>
                    <select name="plan_id" id="plan_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">{{ __('messages.Choose Plan') }}</option>
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 dark:text-gray-300 text-base">{{ __('messages.Line Type') }}</label>
                    <select name="line_type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="prepaid" {{ old('line_type') == 'prepaid' ? 'selected' : '' }}>{{ __('messages.Prepaid') }}</option>
                        <option value="postpaid" {{ old('line_type') == 'postpaid' ? 'selected' : '' }}>{{ __('messages.Postpaid') }}</option>
                    </select>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end">
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white text-lg rounded-lg hover:bg-blue-700 transition">
                    {{ __('messages.Save') }}
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
   <script>
    document.addEventListener("DOMContentLoaded", function () {
        const providerSelect = document.getElementById('provider');
        const planSelect = document.getElementById('plan_id');
        const choosePlanText = @json(__('messages.Choose Plan')); // هنا عملناها JSON آمن

        function loadPlans(provider, selectedPlanId = null) {
            if (!provider) {
                planSelect.innerHTML = `<option value="">${choosePlanText}</option>`;
                return;
            }

            fetch(`/ajax/plans/by-provider?q=${provider}`)
                .then(response => {
                    if (!response.ok) throw new Error("HTTP error " + response.status);
                    return response.json();
                })
                .then(data => {
                    planSelect.innerHTML = `<option value="">${choosePlanText}</option>`;
                    data.forEach(plan => {
                        const option = document.createElement('option');
                        option.value = plan.id;
                        option.text = plan.name;
                        if (selectedPlanId && plan.id == selectedPlanId) {
                            option.selected = true;
                        }
                        planSelect.appendChild(option);
                    });
                })
                .catch(err => console.error('Error fetching plans:', err));
        }

        providerSelect.addEventListener('change', function () {
            loadPlans(this.value);
        });

        // Trigger load on old provider exist
        const oldProvider = providerSelect.value;
        const oldPlanId = @json(old('plan_id'));
        if (oldProvider) {
            loadPlans(oldProvider, oldPlanId);
        }

        // National ID Validation
        const nationalIdInput = document.getElementById('national_id');
        if (nationalIdInput) {
            function validateNationalId() {
                const value = nationalIdInput.value.trim();
                let errEl = nationalIdInput.nextElementSibling;
                if (!errEl || !errEl.classList.contains('nid-error-msg')) {
                    errEl = document.createElement('span');
                    errEl.className = 'nid-error-msg text-red-500 text-sm mt-1 block font-bold';
                    nationalIdInput.parentNode.insertBefore(errEl, nationalIdInput.nextSibling);
                }

                const isAr = document.documentElement.lang === 'ar';
                const requiredMsg = isAr ? 'هذا الحقل مطلوب' : 'This field is required';
                const lengthMsg = isAr ? 'الرقم القومي يجب أن يتكون من 14 رقماً' : 'National ID must be 14 digits';

                if (value === '') {
                    if (nationalIdInput.required) {
                        errEl.textContent = requiredMsg;
                        nationalIdInput.classList.add('border-red-500', 'focus:ring-red-500');
                    } else {
                        errEl.textContent = '';
                        nationalIdInput.classList.remove('border-red-500', 'focus:ring-red-500');
                    }
                } else if (value.length !== 14) {
                    errEl.textContent = lengthMsg;
                    nationalIdInput.classList.add('border-red-500', 'focus:ring-red-500');
                } else {
                    errEl.textContent = '';
                    nationalIdInput.classList.remove('border-red-500', 'focus:ring-red-500');
                }
            }

            nationalIdInput.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 14);
            });

            nationalIdInput.addEventListener('blur', validateNationalId);
        }
    });
</script>

    @endpush
</x-app-layout>
