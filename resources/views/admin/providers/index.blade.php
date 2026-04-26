<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 flex items-center gap-3">
                <span class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-violet-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none">
                    <span class="text-white text-lg">📡</span>
                </span>
                {{ __('messages.providers') ?? 'المزودين' }}
            </h2>
            <a href="{{ route('providers.create') }}" 
               class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/25">
                <span>➕</span>
                {{ __('messages.add_provider') ?? 'إضافة مزود' }}
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in-down">
                <span class="w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center text-sm shadow-sm">✓</span>
                <p class="font-bold">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden" dir="rtl">
            <div class="overflow-x-auto">
                <table class="min-w-full text-center text-sm">
                    <thead>
                        <tr class="bg-gray-50/80 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                            <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">#</th>
                            <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.name') ?? 'الاسم' }}</th>
                            <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.invoice_start_day') ?? 'يوم بدابة الفاتورة' }}</th>
                            <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.actions') ?? 'العمليات' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($providers as $provider)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors duration-200">
                                <td class="px-6 py-4 font-bold text-gray-400">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-black text-gray-800 dark:text-gray-200">{{ $provider->name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 font-black text-xs border border-indigo-100 dark:border-indigo-800/30">
                                        {{ $provider->invoice_day }} {{ __('messages.day') ?? 'يوم' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('providers.edit', $provider) }}" 
                                           class="w-9 h-9 flex items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/40 transition-all border border-amber-100 dark:border-amber-800/30"
                                           title="تعديل">
                                            <span>✏️</span>
                                        </a>
                                        <form action="{{ route('providers.destroy', $provider) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('messages.delete_confirm') ?? 'هل أنت متأكد من الحذف؟' }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-9 h-9 flex items-center justify-center rounded-xl bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/40 transition-all border border-rose-100 dark:border-rose-800/30"
                                                    title="حذف">
                                                <span>🗑️</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <span class="text-4xl">📭</span>
                                        <p class="text-gray-500 font-bold font-black text-lg">{{ __('messages.no_records_found') ?? 'لا يوجد بيانات' }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
