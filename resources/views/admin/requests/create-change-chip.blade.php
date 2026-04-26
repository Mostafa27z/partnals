<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-2xl">📱</span>
            <h2 class="text-xl font-black text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.request_type_change_chip') }} - {{ $line->phone_number }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 px-4">
        <div class="max-w-2xl mx-auto">
            @if ($errors->any())
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 p-5 rounded-3xl animate-pulse">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm text-red-600 dark:text-red-400 font-bold flex items-center gap-2">
                                <span>❌</span> {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl shadow-indigo-500/10 border border-gray-100 dark:border-gray-700 overflow-hidden">
                <!-- Decorative Header -->
                <div class="h-28 bg-gradient-to-r from-indigo-500 to-blue-600 relative overflow-hidden">
                    <div class="absolute inset-0 opacity-20">
                        <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"></path>
                        </svg>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white text-3xl animate-pulse">
                            📱
                        </div>
                    </div>
                </div>

                <div class="p-8 sm:p-10">
                    <form action="{{ route('requests.change-chip.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="line_id" value="{{ $line->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- نوع التغيير -->
                            <div class="space-y-2">
                                <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-1">
                                    {{ __('messages.change_type') }}
                                </label>
                                <select name="change_type" id="change-type" required
                                        class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white font-bold px-5 py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                                    <option value="">-- {{ __('messages.choose_type') ?? 'Choose Type' }} --</option>
                                    <option value="chip" {{ old('change_type') == 'chip' ? 'selected' : '' }}>{{ __('messages.on_chip') }}</option>
                                    <option value="branch" {{ old('change_type') == 'branch' ? 'selected' : '' }}>{{ __('messages.at_branch') }}</option>
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- مسلسل قديم -->
                            <div class="space-y-2">
                                <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-1">
                                    {{ __('messages.old_serial') }}
                                </label>
                                <input type="text" minlength="19" maxlength="19" name="old_serial" value="{{ old('old_serial') }}"
                                       class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white font-bold px-5 py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-mono tracking-wider">
                            </div>

                            <!-- مسلسل جديد -->
                            <div id="new-serial-group" class="space-y-2">
                                <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-1">
                                    {{ __('messages.new_serial') }}
                                </label>
                                <input type="text" maxlength="19" name="new_serial" id="new_serial" value="{{ old('new_serial') }}"
                                       class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white font-bold px-5 py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-mono tracking-wider">
                            </div>
                        </div>

                        <div id="branch-fields" class="space-y-6 pt-4 border-t border-gray-50 dark:border-gray-700/50 hidden">
                            <p class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.3em]">{{ __('messages.at_branch') }} Details</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- الاسم الكامل -->
                                <div id="full-name-group" class="space-y-2">
                                    <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-1">
                                        {{ __('messages.full_name_label') }}
                                    </label>
                                    <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}"
                                           class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white font-bold px-5 py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                                </div>

                                <!-- الرقم القومي -->
                                <div id="national-id-group" class="space-y-2">
                                    <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-1">
                                        {{ __('messages.national_id_label') }}
                                    </label>
                                    <input type="text" name="national_id" id="national_id" value="{{ old('national_id') }}" maxlength="14"
                                           class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white font-bold px-5 py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-mono">
                                </div>
                            </div>
                        </div>

                        <!-- ملاحظات -->
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-1">
                                {{ __('messages.notes_optional') }}
                            </label>
                            <textarea name="comment" class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white font-bold px-5 py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all" rows="3">{{ old('comment') }}</textarea>
                        </div>

                        <div class="pt-6">
                            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-[0.98] flex items-center justify-center gap-3 uppercase tracking-widest text-sm">
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
            const typeSelect = document.getElementById('change-type');
            const branchFields = document.getElementById('branch-fields');
            const newSerialInput = document.getElementById('new_serial');
            const fullNameInput = document.getElementById('full_name');
            const nationalIdInput = document.getElementById('national_id');

            function toggleFields() {
                const value = typeSelect.value;
                if (value === 'chip') {
                    branchFields.classList.add('hidden');
                    newSerialInput.required = true;
                    fullNameInput.required = false;
                    nationalIdInput.required = false;
                } else if (value === 'branch') {
                    branchFields.classList.remove('hidden');
                    newSerialInput.required = false;
                    fullNameInput.required = true;
                    nationalIdInput.required = true;
                } else {
                    branchFields.classList.add('hidden');
                    newSerialInput.required = false;
                    fullNameInput.required = false;
                    nationalIdInput.required = false;
                }
            }

            toggleFields();
            typeSelect.addEventListener('change', toggleFields);
            
            // Limit NID to 14 digits
            nationalIdInput?.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 14);
            });
        });
    </script>
    @endpush
</x-app-layout>
