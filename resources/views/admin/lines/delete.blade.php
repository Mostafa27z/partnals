<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 md:gap-6">
            <h2 class="text-2xl font-black text-rose-600 dark:text-rose-400 flex items-center gap-3">
                <span class="w-10 h-10 bg-gradient-to-tr from-rose-600 to-pink-600 rounded-xl flex items-center justify-center shadow-lg shadow-rose-200 dark:shadow-none">
                    <span class="text-white text-lg">🗑️</span>
                </span>
                {{ __('messages.delete_lines') ?? 'حذف الخطوط' }}
            </h2>

            <div class="flex gap-3">
                <a href="{{ route('lines.all') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold text-sm border border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all shadow-sm">
                    📱 {{ __('messages.all_lines') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 text-sm sm:text-base" dir="rtl">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/30 text-emerald-700 dark:text-emerald-300 rounded-2xl shadow-sm flex items-center gap-3 font-bold">
                <span class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg flex items-center justify-center text-lg shrink-0">✅</span>
                {{ session('success') }}
            </div>
        @endif

        {{-- Filter Form --}}
        <div class="mb-6 p-5 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50">
            <form method="GET" action="{{ route('lines.delete-index') }}" class="flex flex-wrap gap-3 items-end">
                <input type="text" name="phone" value="{{ request('phone') }}" placeholder="{{ __('messages.phone_number') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 w-full sm:w-40 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition placeholder:text-gray-400 text-sm" />
                <input type="text" name="nid" value="{{ request('nid') }}" placeholder="{{ __('messages.national_id') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 w-full sm:w-40 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition placeholder:text-gray-400 text-sm" />
                <input type="text" name="provider" value="{{ request('provider') }}" placeholder="{{ __('messages.provider') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 w-full sm:w-40 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition placeholder:text-gray-400 text-sm" />
                @if(auth()->user()->role->name !== 'موزع')
                <select name="distributor_id" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 w-full sm:w-40 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition text-sm">
                    <option value="">-- {{ __('messages.distributor') }} --</option>
                    @foreach($distributors as $distributor)
                        <option value="{{ $distributor->id }}" {{ request('distributor_id') == $distributor->id ? 'selected' : '' }}>
                            {{ $distributor->name }}
                        </option>
                    @endforeach
                </select>
                @endif
                <select name="plan_id" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 w-full sm:w-40 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition text-sm">
                    <option value="">-- {{ __('messages.plan') }} --</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }}
                        </option>
                    @endforeach
                </select>
                <button class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-rose-600 text-white font-bold text-sm hover:bg-rose-700 transition-all shadow-lg shadow-rose-500/25">
                    🔍 {{ __('messages.search') }}
                </button>
            </form>
        </div>

        {{-- Results --}}
        @if(!$hasSearch)
            <div class="p-12 text-center bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50">
                <div class="w-20 h-20 bg-rose-50 dark:bg-rose-900/20 rounded-2xl flex items-center justify-center mx-auto mb-5">
                    <span class="text-4xl">🔍</span>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-lg font-bold">{{ __('messages.search_to_view_lines_for_deletion') ?? 'ابحث لعرض الخطوط المراد حذفها' }}</p>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
                <form method="POST" id="bulk-action-form">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-center text-sm">
                            <thead>
                                <tr class="bg-gray-50/80 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                                    <th class="px-4 py-4 text-center">
                                        <input type="checkbox" id="select-all" class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-rose-600 focus:ring-rose-500 cursor-pointer">
                                    </th>
                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.phone_number') }}</th>
                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.national_id') }}</th>
                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.customer_name') }}</th>
                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.status') }}</th>
                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                                @foreach($lines as $line)
                                    <tr class="hover:bg-rose-50/30 dark:hover:bg-rose-900/10 transition-colors duration-200">
                                        <td class="px-4 py-3.5">
                                            <input type="checkbox" name="selected_lines[]" value="{{ $line->id }}" class="line-checkbox w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-rose-600 focus:ring-rose-500 cursor-pointer">
                                        </td>
                                        <td class="px-4 py-3.5 font-mono font-bold text-gray-800 dark:text-gray-200">{{ $line->phone_number }}</td>
                                        <td class="px-4 py-3.5 text-gray-600 dark:text-gray-400">{{ $line->customer->national_id ?? '-' }}</td>
                                        <td class="px-4 py-3.5 font-medium text-gray-700 dark:text-gray-300">{{ $line->customer->full_name ?? '-' }}</td>
                                        <td class="px-4 py-3.5">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black {{ $line->status === 'active' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' }}">
                                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $line->status === 'active' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                                {{ $line->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5 whitespace-nowrap">
                                            <button type="button"
                                                    class="inline-flex items-center gap-1 px-4 py-2 rounded-xl bg-rose-50 dark:bg-rose-900/20 text-rose-700 dark:text-rose-400 font-black text-xs hover:bg-rose-100 dark:hover:bg-rose-900/40 transition-all border border-rose-100 dark:border-rose-800/30"
                                                    onclick="confirmDelete({{ $line->id }})">
                                                🗑 {{ __('messages.delete') }}
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Bulk Actions --}}
                    <div class="p-4 bg-gray-50/80 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                        <button type="button" onclick="confirmBulkDelete()" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-rose-600 text-white font-black text-sm hover:bg-rose-700 transition-all shadow-lg shadow-rose-500/25">
                            🗑️ {{ __('messages.delete_selected') }}
                        </button>
                    </div>
                </form>

                {{-- Pagination --}}
                <div class="p-5 border-t border-gray-100 dark:border-gray-700">
                    {{ $lines->appends(request()->query())->links() }}
                </div>
            </div>
        @endif

        {{-- Hidden Delete Form --}}
        <form method="POST" id="delete-form" style="display: none;">
            @csrf
            @method('DELETE')
        </form>

        @push('scripts')
            <script>
                document.getElementById('select-all')?.addEventListener('change', function () {
                    document.querySelectorAll('.line-checkbox').forEach(cb => cb.checked = this.checked);
                });

                function confirmDelete(lineId) {
                    if (confirm('{{ __('messages.delete_line_confirmation') }}')) {
                        const form = document.getElementById('delete-form');
                        form.action = `/admin/lines/${lineId}`;
                        form.submit();
                    }
                }

                function confirmBulkDelete() {
                    const selected = document.querySelectorAll('.line-checkbox:checked').length;
                    if (selected === 0) {
                        alert('{{ __('messages.select_lines_first') ?? "يرجى تحديد خطوط أولاً" }}');
                        return;
                    }
                    if (confirm('{{ __('messages.delete_selected_confirmation') }}')) {
                        const form = document.getElementById('bulk-action-form');
                        form.action = "{{ route('lines.bulk-delete') }}";
                        form.submit();
                    }
                }
            </script>
        @endpush
    </div>
</x-app-layout>
