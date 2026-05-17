<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-2xl">⛔</span>
            <h2 class="text-xl font-black text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.request_type_stop') }} - {{ $line->phone_number }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 px-4">
        <div class="max-w-xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl shadow-rose-500/10 border border-gray-100 dark:border-gray-700 overflow-hidden text-center sm:text-start">
                <!-- Decorative Header -->
                <div class="h-24 bg-gradient-to-r from-rose-600 to-red-700 relative overflow-hidden">
                    <div class="absolute inset-0 opacity-20">
                        <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <path d="M0 0 L 100 100 L 100 0 Z" fill="white"></path>
                        </svg>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white text-2xl animate-pulse">
                            ⛔
                        </div>
                    </div>
                </div>

                <div class="p-8 sm:p-10">
                    <!-- Line Summary -->
                    <div class="mb-8 p-6 rounded-2xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700/50 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.phone_number') }}</span>
                            <span class="text-sm font-black text-gray-800 dark:text-gray-200 font-mono tracking-tighter">{{ $line->phone_number }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.customer_name') }}</span>
                            <span class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $line->customer?->full_name ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.national_id') }}</span>
                            <span class="text-sm font-bold text-gray-500 dark:text-gray-400 font-mono tracking-tighter">{{ $line->customer?->national_id ?? '-' }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('requests.stop.store') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="line_id" value="{{ $line->id }}">
                        <input type="hidden" name="customer_id" value="{{ $line->customer_id }}">

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] px-1">
                                {{ __('messages.reason_optional') }}
                            </label>
                            <input type="text" 
                                   name="reason" 
                                   class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white font-bold px-5 py-4 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 transition-all font-sans placeholder-gray-300 dark:placeholder-gray-700" 
                                   required
                                   value="{{ old('reason') }}"
                                   placeholder="...">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] px-1">
                                {{ __('messages.notes_optional') }}
                            </label>
                            <textarea name="comment" 
                                      class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white font-bold px-5 py-4 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 transition-all font-sans placeholder-gray-300 dark:placeholder-gray-700" 
                                      rows="3"
                                      placeholder="...">{{ old('comment') }}</textarea>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full bg-gradient-to-r from-rose-600 to-red-700 hover:from-rose-700 hover:to-red-800 text-white font-black py-4 rounded-2xl shadow-xl shadow-rose-500/20 transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                                <span>⛔</span>
                                <span class="uppercase tracking-widest text-sm text-center">{{ __('messages.confirm_save_request') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
