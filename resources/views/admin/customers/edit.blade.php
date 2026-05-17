<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.Edit Customer') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto px-6 lg:px-8">
        <!-- بيانات العميل -->
        <div class="bg-white dark:bg-gray-800/80 backdrop-blur-sm p-8 rounded-xl shadow-lg">
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg shadow">
                    <ul class="list-disc list-inside text-base space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('customers.update', $customer) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-medium text-gray-700 dark:text-gray-300 text-base">{{ __('messages.Full Name') }}</label>
                        <input type="text" name="full_name" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('full_name', $customer->full_name) }}" required>
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 dark:text-gray-300 text-base">{{ __('messages.National ID') }}</label>
                        <input type="text" name="national_id" id="national_id" maxlength="14" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('national_id', $customer->national_id) }}" required>
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 dark:text-gray-300 text-base">{{ __('messages.Birth Date') }}</label>
                        <input type="date" name="birth_date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('birth_date', $customer->birth_date) }}">
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 dark:text-gray-300 text-base">{{ __('messages.Email') }}</label>
                        <input type="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('email', $customer->email) }}">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block font-medium text-gray-700 dark:text-gray-300 text-base">{{ __('messages.Address') }}</label>
                        <input type="text" name="address" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('address', $customer->address) }}">
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 dark:text-gray-300 text-base">رقم التواصل</label>
                        <input type="text" name="contact_number" maxlength="11" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('contact_number', $customer->contact_number) }}">
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 dark:text-gray-300 text-base">رقم الواتساب</label>
                        <input type="text" name="whatsapp_number" maxlength="11" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('whatsapp_number', $customer->whatsapp_number) }}">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button class="px-8 py-3 bg-blue-600 text-white text-lg rounded-lg hover:bg-blue-700 transition">
                        {{ __('messages.Update') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- خطوط العميل -->
        <div class="bg-white dark:bg-gray-800/80 backdrop-blur-sm mt-10 p-8 rounded-xl shadow-lg">
            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-6">{{ __('messages.Customer Lines') }}</h3>

            @if($customer->lines->count())
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border border-gray-200 dark:border-gray-700 text-base">
                        <thead class="bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300">
                            <tr class="text-center">
                                <th class="px-4 py-3 border">{{ __('messages.Phone Number') }}</th>
                                <th class="px-4 py-3 border">{{ __('messages.Line Type') }}</th>
                                <th class="px-4 py-3 border">{{ __('messages.Provider') }}</th>
                                <th class="px-4 py-3 border">{{ __('messages.Plan') }}</th>
                                <th class="px-4 py-3 border">{{ __('messages.Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100">
                            @foreach($customer->lines as $line)
                                <tr class="text-center hover:bg-gray-50 dark:bg-gray-700/50">
                                    <td class="px-4 py-3 border">{{ $line->phone_number }}</td>
                                    <td class="px-4 py-3 border">{{ $line->line_type == 'prepaid' ? __('messages.Prepaid') : __('messages.Postpaid') }}</td>
                                    <td class="px-4 py-3 border">{{ $line->provider }}</td>
                                    <td class="px-4 py-3 border">{{ $line->plan->name ?? '-' }}</td>
                                    <td class="px-4 py-3 border space-x-2">
                                        <a href="{{ route('customers.lines.edit', [$customer, $line]) }}" class="text-blue-600 hover:underline">{{ __('messages.Edit') }}</a>
                                        @if(auth()->user()->hasPermission('delete line'))
                                        <form action="{{ route('customers.lines.destroy', [$customer, $line]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('{{ __('messages.Are you sure to delete this line?') }}')" class="text-red-600 hover:underline">{{ __('messages.Delete') }}</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600 dark:text-gray-400 text-lg">{{ __('messages.No lines found for this customer.') }}</p>
            @endif

            <div class="mt-6">
                <a href="{{ route('customers.lines.create', $customer) }}" class="bg-green-600 text-white px-6 py-3 text-lg rounded-lg hover:bg-green-700 transition">
                    + {{ __('messages.Add New Line') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
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
