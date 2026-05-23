<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-2xl">🔁</span>
            <h2 class="text-xl font-black text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.request_type_resell') }} - {{ $line->phone_number }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 px-4">
        <div class="max-w-2xl mx-auto">
            @if ($errors->any())
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 p-5 rounded-3xl">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm text-red-600 dark:text-red-400 font-bold flex items-center gap-2">
                                <span>❌</span> {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800 p-5 rounded-3xl">
                    <div class="text-sm text-green-600 dark:text-green-400 font-bold flex items-center gap-2">
                        <span>✅</span>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl shadow-indigo-500/10 border border-gray-100 dark:border-gray-700 overflow-hidden">
                <!-- Decorative Header -->
                <div class="h-28 bg-gradient-to-r from-indigo-500 to-purple-600 relative overflow-hidden">
                    <div class="absolute inset-0 opacity-20">
                        <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <path d="M0 0 C 50 100 80 100 100 0 Z" fill="white"></path>
                        </svg>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white text-3xl animate-pulse">
                            🔁
                        </div>
                    </div>
                </div>

                <div class="p-8 sm:p-10">
                    <form action="{{ route('requests.resell.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="line_id" value="{{ $line->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- النوع -->
                            <div class="space-y-2">
                                <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-1">
                                    {{ __('messages.change_type') }}
                                </label>
                                <select name="resell_type" id="resell-type" required
                                        class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white font-bold px-5 py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                                    <option value="">-- {{ __('messages.choose_type') ?? 'Choose Type' }} --</option>
                                    <option value="chip" {{ old('resell_type') == 'chip' ? 'selected' : '' }}>
                                        {{ __('messages.on_chip') }}
                                    </option>
                                    <option value="branch" {{ old('resell_type') == 'branch' ? 'selected' : '' }}>
                                        {{ __('messages.at_branch') }}
                                    </option>
                                </select>
                            </div>

                            <!-- تاريخ الطلب -->
                            <div class="space-y-2">
                                <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-1">
                                    {{ __('messages.request_date') }}
                                </label>
                                <input type="date" name="request_date" value="{{ old('request_date', now()->toDateString()) }}" required
                                       class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white font-bold px-5 py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                            </div>
                        </div>

                        <!-- الاسم الكامل + الرقم القومي ظاهرين دائمًا -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-1">
                                    {{ __('messages.full_name_label') }}
                                </label>
                                <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" required
                                       class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white font-bold px-5 py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                            </div>

                            <div class="space-y-2">
                                <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-1">
                                    {{ __('messages.national_id_label') }}
                                </label>
                                <input type="text" name="national_id" id="national_id" value="{{ old('national_id') }}" maxlength="14" required
                                       class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white font-bold px-5 py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-mono">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- مسلسل قديم -->
                            <div class="space-y-2">
                                <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-1">
                                    {{ __('messages.old_serial') }}
                                </label>
                                <input type="text" id="old_serial" minlength="19" maxlength="19" name="old_serial" value="{{ old('old_serial', $line->serial_number) }}"
                                       class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white font-bold px-5 py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-mono tracking-wider">
                            </div>

                            <!-- مسلسل جديد -->
                            <div class="space-y-2">
                                <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-1">
                                    {{ __('messages.new_serial') }}
                                </label>
                                <input type="text" maxlength="19" name="new_serial" id="new_serial" value="{{ old('new_serial') }}"
                                       class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white font-bold px-5 py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-mono tracking-wider">
                            </div>
                        </div>

                        <!-- ملاحظات -->
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-1">
                                {{ __('messages.notes_optional') }}
                            </label>
                            <textarea name="comment" class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white font-bold px-5 py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all" rows="2">{{ old('comment') }}</textarea>
                        </div>

                        <!-- سعر البيع -->
                        <div class="space-y-2 pt-4 border-t border-gray-50 dark:border-gray-700/50">
                            <label class="block text-xs font-black text-emerald-500 uppercase tracking-widest px-1">
                                💰 {{ __('messages.sale_price_label') }}
                            </label>
                            <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price') }}" placeholder="0.00"
                                   class="w-full rounded-2xl border-emerald-100 dark:border-emerald-900/30 bg-emerald-50/30 dark:bg-emerald-900/10 text-emerald-700 dark:text-emerald-400 font-black px-5 py-4 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all text-xl font-mono">
                        </div>

                        <div class="pt-6">
                            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-[0.98] flex items-center justify-center gap-3 uppercase tracking-widest text-sm">
                                <span>💾</span>
                                <span>{{ __('messages.confirm_save_request') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
    const typeSelect = document.getElementById('resell-type');
    const newSerialInput = document.getElementById('new_serial');
    const fullNameInput = document.getElementById('full_name');
    const nationalIdInput = document.getElementById('national_id');
    
    function toggleFields() {
        const value = typeSelect.value;
        // new serial required only for chip type
        newSerialInput.required = (value === 'chip');
        // full name and national id are always required
        fullNameInput.required = true;
        nationalIdInput.required = true;
    }

    toggleFields();
    // Auto-fill customer data based on line ID
    const lineIdInput = document.querySelector('input[name="line_id"]');
    if (lineIdInput) {
        fetch(`/ajax/customer-by-line/${lineIdInput.value}`)
            .then(response => response.json())
            .then(data => {
                if (data.full_name) fullNameInput.value = data.full_name;
                if (data.national_id) nationalIdInput.value = data.national_id;
            })
            .catch(() => {
                // optional: handle fetch errors silently
            });
    }
    
    typeSelect.addEventListener('change', toggleFields);

            // Limit NID to 14 digits
            nationalIdInput?.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 14);
            });

            // National ID Validation on blur
            nationalIdInput?.addEventListener('blur', () => {
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
                    errEl.textContent = requiredMsg;
                    nationalIdInput.classList.add('border-red-500', 'focus:ring-red-500');
                } else if (value.length !== 14) {
                    errEl.textContent = lengthMsg;
                    nationalIdInput.classList.add('border-red-500', 'focus:ring-red-500');
                } else {
                    errEl.textContent = '';
                    nationalIdInput.classList.remove('border-red-500', 'focus:ring-red-500');
                }
            });

            // Serial inputs validation & numeric restriction
            const oldSerial = document.getElementById('old_serial');
            const newSerial = document.getElementById('new_serial');

            [oldSerial, newSerial].forEach(input => {
                if (!input) return;

                input.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, '');
                });

                input.addEventListener('blur', function() {
                    const val = this.value.trim();
                    let errEl = this.parentNode.querySelector('.serial-error-msg');

                    if (errEl) {
                        errEl.remove();
                    }

                    this.classList.remove('border-red-500', 'focus:ring-red-500');

                    const isRequired = this.hasAttribute('required') || this.required;

                    if (val === '') {
                        if (isRequired) {
                            const isAr = document.documentElement.lang === 'ar';
                            const requiredMsg = isAr ? 'هذا الحقل مطلوب' : 'This field is required';
                            showError(this, requiredMsg);
                        }
                        return;
                    }

                    if (val.length !== 19) {
                        const isAr = document.documentElement.lang === 'ar';
                        const lengthMsg = isAr ? 'يجب أن يتكون الرقم التسلسلي من 19 رقماً بالضبط' : 'Serial number must be exactly 19 digits';
                        showError(this, lengthMsg);
                    }
                });
            });

            function showError(input, msg) {
                const errEl = document.createElement('span');
                errEl.className = 'serial-error-msg text-red-500 text-sm mt-1 block font-bold';
                errEl.textContent = msg;
                input.parentNode.appendChild(errEl);
                input.classList.add('border-red-500', 'focus:ring-red-500');
            }
        });
    </script>
    @endpush
</x-app-layout>