<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 flex items-center gap-3">
                <a href="{{ route('providers.index') }}" class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center hover:bg-gray-200 transition-all">
                    <span class="text-gray-600 dark:text-gray-300">⬅</span>
                </a>
                {{ __('messages.add_provider') ?? 'إضافة مزود جديد' }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700/50 overflow-hidden">
            <div class="p-8" dir="rtl">
                <form action="{{ route('providers.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    {{-- Provider Name --}}
                    <div>
                        <label class="block text-sm font-black text-gray-700 dark:text-gray-300 mb-2">
                             {{ __('messages.provider_name') ?? 'اسم المزود' }}
                        </label>
                        <div class="relative group">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-500 transition-colors">🏷️</span>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="block w-full pr-12 pl-4 py-3.5 rounded-2xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold placeholder-gray-400"
                                   placeholder="مثال: Vodafone">
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-pink-600 font-bold flex items-center gap-1">
                                <span>⚠️</span> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Invoice Start Day --}}
                    <div>
                        <label class="block text-sm font-black text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('messages.invoice_start_day') ?? 'يوم بداية الفاتورة' }}
                        </label>
                        <div class="relative group">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-500 transition-colors">📅</span>
                            <input type="number" name="invoice_day" value="{{ old('invoice_day', 1) }}" min="1" max="31" required
                                   class="block w-full pr-12 pl-4 py-3.5 rounded-2xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold"
                                   placeholder="1-31">
                        </div>
                        <p class="mt-2 text-xs text-gray-500 font-bold italic opacity-75">
                            * هذا اليوم يحدد متى يبدأ احتساب الفاتورة الشهرية لهذا المزود.
                        </p>
                        @error('invoice_day')
                            <p class="mt-2 text-sm text-pink-600 font-bold flex items-center gap-1">
                                <span>⚠️</span> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="pt-6">
                        <button type="submit" 
                                class="w-full flex items-center justify-center gap-3 px-8 py-4 rounded-2xl bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-black text-lg hover:from-indigo-700 hover:to-violet-700 transition-all shadow-xl shadow-indigo-500/25 active:scale-[0.98]">
                            <span>💾</span>
                            {{ __('messages.save') ?? 'حفظ البيانات' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
