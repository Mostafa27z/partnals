<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-2xl">🏬</span>
            <h2 class="text-xl font-black text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.request_type_change_distributor') }} - {{ $line->phone_number }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 px-4">
        <div class="max-w-xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl shadow-indigo-500/10 border border-gray-100 dark:border-gray-700 overflow-hidden">
                <!-- Decorative Header -->
                <div class="h-24 bg-gradient-to-r from-indigo-500 to-blue-600 relative overflow-hidden">
                    <div class="absolute inset-0 opacity-20">
                        <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <path d="M0 100 C 50 0 80 0 100 100 Z" fill="white"></path>
                        </svg>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white text-2xl animate-pulse">
                            🏢
                        </div>
                    </div>
                </div>

                <div class="p-8 sm:p-10">
                    <form method="POST" action="{{ route('requests.change-distributor.store') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="line_id" value="{{ $line->id }}">

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] px-1">
                                {{ __('messages.current_distributor') }}
                            </label>
                            <input type="text" 
                                   class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 text-gray-500 dark:text-gray-400 font-black px-5 py-4 cursor-not-allowed" 
                                   disabled 
                                   value="{{ $line->distributor ? $line->distributor->name : 'لا يوجد موزع' }}">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] px-1">
                                {{ __('messages.new_distributor') }}
                            </label>
                            <select name="new_distributor_id" 
                                    class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white font-bold px-5 py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all" 
                                    required>
                                <option value="" disabled selected>-- اختر الموزع الجديد --</option>
                                @foreach($distributors as $distributor)
                                    <option value="{{ $distributor->id }}">{{ $distributor->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] px-1">
                                {{ __('messages.reason_optional') }}
                            </label>
                            <textarea name="reason" 
                                      class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white font-bold px-5 py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all" 
                                      rows="3">{{ old('reason') }}</textarea>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                                <span>💾</span>
                                <span>{{ __('messages.confirm_save_request') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
