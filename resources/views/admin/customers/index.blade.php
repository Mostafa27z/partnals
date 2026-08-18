<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h2 class="text-3xl font-black text-gray-900 dark:text-white flex items-center gap-3">
                    <span class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white text-2xl shadow-lg">👥</span>
                    <span>{{ __('messages.Customers List') }}</span>
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 font-medium">{{ __('messages.total_registered_customers', ['count' => $lines->total()]) }}</p>
            </div>
            <div class="flex items-center gap-3">
                @if(auth()->user()->hasPermission('delete customer'))
                <a href="{{ route('customers.trashed') }}" class="group px-5 py-2.5 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 rounded-xl text-sm font-black border border-rose-100 dark:border-rose-800 transition-all active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    <span>{{ __('messages.Deleted Customers') }}</span>
                </a>
                @endif
                <a href="{{ route('customers.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-black shadow-lg shadow-indigo-100 dark:shadow-none transition-all active:scale-95 flex items-center gap-2">
                    <span>+</span>
                    <span>{{ __('messages.New Customer') }}</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        {{-- Search & Export Card --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700">
            <form method="GET" action="{{ route('customers.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block mb-1.5 text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">الاسم الكامل</label>
                        <input type="text" name="name" value="{{ request('name') }}" 
                               placeholder="{{ __('messages.Full Name') ?? 'الاسم الكامل' }}" 
                               class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-3.5 text-sm font-bold focus:ring-2 focus:ring-indigo-500" />
                    </div>
                    <div>
                        <label class="block mb-1.5 text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">رقم الهاتف</label>
                        <input type="text" name="phone_number" value="{{ request('phone_number') }}" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               placeholder="{{ __('messages.Phone Number') }}" 
                               class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-3.5 text-sm font-bold focus:ring-2 focus:ring-indigo-500" />
                    </div>
                    <div>
                        <label class="block mb-1.5 text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">الرقم القومي</label>
                        <input type="text" name="national_id" id="filter_nid" value="{{ request('national_id') }}" maxlength="14"
                               placeholder="{{ __('messages.National ID') }}" 
                               class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-3.5 text-sm font-bold focus:ring-2 focus:ring-indigo-500" />
                    </div>
                </div>
                <div class="flex gap-2 w-full md:w-auto mt-4 md:mt-0">
                    <button class="flex-1 md:flex-initial bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3.5 rounded-2xl shadow-lg shadow-indigo-100 dark:shadow-none transition-all font-black text-sm active:scale-95">
                        {{ __('messages.Search') }}
                    </button>
                    <a href="{{ route('customers.export', request()->query()) }}" class="bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 px-6 py-3.5 rounded-2xl border border-emerald-100 dark:border-emerald-800 transition-all font-black text-sm active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        {{ __('messages.Export to Excel') }}
                    </a>
                </div>
            </form>
        </div>

        {{-- Customers Table Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-center" dir="rtl">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-900/50 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                            <th class="px-8 py-6">{{ __('messages.Full Name') }}</th>
                            <th class="px-8 py-6">{{ __('messages.National ID') }}</th>
                            <th class="px-8 py-6">رقم الهاتف</th>
                            <th class="px-8 py-6">العرض</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @foreach ($lines as $line)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-750/50 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3 justify-center">
                                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-sm group-hover:scale-110 transition-transform">
                                            {{ mb_substr($line->customer->full_name ?? '-', 0, 1) }}
                                        </div>
                                        <span class="font-black text-gray-900 dark:text-white">{{ $line->customer->full_name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 rounded-lg text-xs font-bold">{{ $line->customer->national_id ?? '-' }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-lg text-xs font-mono font-bold">{{ $line->phone_number }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <a href="{{ route('lines.show', $line->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl hover:scale-105 transition-all font-black text-sm active:scale-95 shadow-md shadow-indigo-600/10">
                                        عرض
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            @if ($lines->hasPages())
                <div class="px-8 py-6 bg-gray-50/50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700">
                    {{ $lines->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById('filter_nid')?.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 14);
        });
    });
</script>
@endpush
