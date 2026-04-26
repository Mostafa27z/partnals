<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h2 class="text-3xl font-black text-gray-900 dark:text-white flex items-center gap-3">
                    <span class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white text-2xl shadow-lg">👥</span>
                    <span>{{ __('messages.Customers List') }}</span>
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 font-medium">{{ __('messages.total_registered_customers', ['count' => $customers->total()]) }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('customers.trashed') }}" class="group px-5 py-2.5 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 rounded-xl text-sm font-black border border-rose-100 dark:border-rose-800 transition-all active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    <span>{{ __('messages.Deleted Customers') }}</span>
                </a>
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
                <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1.5 text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">رقم الهاتف</label>
                        <input type="text" name="phone_number" value="{{ request('phone_number') }}" 
                               placeholder="{{ __('messages.Phone Number') }}" 
                               class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-3.5 text-sm font-bold focus:ring-2 focus:ring-indigo-500" />
                    </div>
                    <div>
                        <label class="block mb-1.5 text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">الرقم القومي</label>
                        <input type="text" name="national_id" value="{{ request('national_id') }}" 
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
                            <th class="px-8 py-6">{{ __('messages.Invoices') }}</th>
                            <th class="px-8 py-6">{{ __('messages.Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @foreach ($customers as $customer)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-750/50 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3 justify-center">
                                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-sm group-hover:scale-110 transition-transform">
                                            {{ substr($customer->full_name, 0, 1) }}
                                        </div>
                                        <span class="font-black text-gray-900 dark:text-white">{{ $customer->full_name }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 rounded-lg text-xs font-bold">{{ $customer->national_id }}</span>
                                </td>
                                <td class="px-8 py-6 text-emerald-600">
                                    <a href="{{ route('customers.invoices', $customer) }}" class="flex items-center justify-center gap-2 font-black text-sm hover:underline">
                                        <span>📜</span>
                                        {{ __('messages.View Invoices') }}
                                    </a>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('customers.show', $customer) }}" class="p-2.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl hover:bg-blue-100 transition-all" title="{{ __('messages.View') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer) }}" class="p-2.5 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-xl hover:bg-amber-100 transition-all" title="{{ __('messages.Edit') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>
                                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button onclick="return confirm('{{ __('messages.Are you sure?') }}')" class="p-2.5 bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-xl hover:bg-rose-100 transition-all" title="{{ __('messages.Delete') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            @if ($customers->hasPages())
                <div class="px-8 py-6 bg-gray-50/50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
