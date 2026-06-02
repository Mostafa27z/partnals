<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2" dir="rtl">
            📜 {{ __('messages.requests_history') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8" dir="rtl">
        <!-- Filter Form -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div class="text-sm font-bold text-gray-700 dark:text-gray-200">{{ __('messages.requests_count', ['count' => $requests->total()]) }}</div>
            <button type="button" onclick="toggleFilters('filters-panel-history')" class="inline-flex items-center gap-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                {{ __('messages.filter_toggle') }}
            </button>
        </div>
        <div id="filters-panel-history" class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 mb-6">
            <form method="GET" action="{{ route('requests.history') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-black text-gray-400 dark:text-gray-500 mb-2 uppercase tracking-widest">{{ __('messages.phone_number') }}</label>
                    <input type="text" name="phone" value="{{ request('phone') }}" placeholder="01xxxxxxxxx" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-mono" />
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-400 dark:text-gray-500 mb-2 uppercase tracking-widest">{{ __('messages.national_id') }}</label>
                    <input type="text" name="nid" id="filter_nid" value="{{ request('nid') }}" placeholder="29xxxxxxxxxxxx" maxlength="14"
                           class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-mono" />
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-400 dark:text-gray-500 mb-2 uppercase tracking-widest">{{ __('messages.type') }}</label>
                    <select name="type" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold">
                        <option value="">{{ __('messages.select_type') }}</option>
                        @foreach(['resell', 'change_plan', 'change_chip', 'pause', 'resume', 'change_date', 'change_distributor', 'stop'] as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                {{ __('messages.request_type_' . $type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-400 dark:text-gray-500 mb-2 uppercase tracking-widest">{{ __('messages.provider') }}</label>
                    <input type="text" name="provider" value="{{ request('provider') }}" placeholder="Vodafone, WE..."
                           class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all" />
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-400 dark:text-gray-500 mb-2 uppercase tracking-widest">{{ __('messages.status') }}</label>
                    <select name="status" class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold">
                        <option value="">{{ __('messages.all') }}</option>
                        <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>{{ __('messages.status_done') }}</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('messages.status_cancelled') }}</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-400 dark:text-gray-500 mb-2 uppercase tracking-widest">{{ __('messages.from_date') }}</label>
                    <input type="date" name="from" value="{{ request('from') }}"
                           class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all" />
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-400 dark:text-gray-500 mb-2 uppercase tracking-widest">{{ __('messages.to_date') }}</label>
                    <input type="date" name="to" value="{{ request('to') }}"
                           class="w-full rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all" />
                </div>

                <div class="sm:col-span-2 lg:col-span-2 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-3 rounded-xl shadow-lg shadow-blue-500/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <span>🔍 {{ __('messages.search') }}</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Requests Table -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-center whitespace-nowrap">
                    <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-500 dark:text-gray-400">
                        <tr class="text-xs uppercase tracking-wider font-black">
                            <th class="px-6 py-4">{{ __('messages.phone_number') }}</th>
                            <th class="px-6 py-4">{{ __('messages.name') }}</th>
                            <th class="px-6 py-4">{{ __('messages.national_id') }}</th>
                            <th class="px-6 py-4">{{ __('messages.request_type') }}</th>
                            <th class="px-6 py-4">{{ __('messages.status') }}</th>
                            <th class="px-6 py-4">{{ __('messages.request_created') }}</th>
                            <th class="px-6 py-4">{{ __('messages.request_done') }}</th>
                            <th class="px-6 py-4">{{ __('messages.date') }}</th>
                            <th class="px-6 py-4">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 font-medium text-sm">
                        @forelse ($requests as $request)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/40 transition-all">
                                <td class="px-6 py-4 text-blue-600 dark:text-blue-400 font-black font-mono tracking-tighter">{{ $request->line?->phone_number ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-900 dark:text-gray-200 font-bold">{{ $request->line?->customer?->full_name ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400 font-mono text-xs">{{ $request->line?->customer?->national_id ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-lg text-xs font-black text-gray-600 dark:text-gray-300">
                                        {{ __('messages.request_type_' . $request->request_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($request->status === 'done')
                                        <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 rounded-lg text-xs font-black text-green-600 dark:text-green-400">
                                            {{ __('messages.status_done') }}
                                        </span>
                                    @elseif($request->status === 'cancelled')
                                        <span class="px-3 py-1 bg-red-100 dark:bg-red-900/30 rounded-lg text-xs font-black text-red-600 dark:text-red-400">
                                            {{ __('messages.status_cancelled') }}
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-lg text-xs font-black text-gray-600 dark:text-gray-300">
                                            {{ __('messages.status_' . $request->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                    <div class="flex items-center justify-center gap-1.5 font-bold">
                                        <span class="w-6 h-6 rounded-full bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-[10px]">👤</span>
                                        {{ $request->requestedBy?->name ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                    <div class="flex items-center justify-center gap-1.5 font-bold">
                                        <span class="w-6 h-6 rounded-full bg-green-50 dark:bg-green-900/30 flex items-center justify-center text-[10px]">✅</span>
                                        {{ $request->doneBy?->name ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-500 font-mono text-xs">
                                    {{ $request->created_at->format('Y-m-d') }}
                                    <span class="block text-[10px] opacity-50">{{ $request->created_at->format('H:i') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- View Details Icon --}}
                                        <a href="{{ route('requests.show', $request->id) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-all border border-blue-100 dark:border-blue-800/50 shadow-sm"
                                           title="{{ __('messages.view') }}">
                                            👁️
                                        </a>

                                        @if($request->request_type === 'resell' && $request->resellDetails && $request->status === 'done')
                                             <span class="inline-flex items-center gap-1 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 px-3 py-1.5 rounded-lg font-black text-xs border border-emerald-100 dark:border-emerald-800/50">
                                                ✅ {{ number_format($request->resellDetails->sale_price, 2) }} {{ __('messages.currency') }}
                                            </span>
                                        @elseif($request->request_type === 'resell' && $request->resellDetails && $request->status === 'cancelled')
                                            <span class="text-gray-400 dark:text-gray-500">-</span>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">-</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-12 bg-gray-50/50 dark:bg-gray-900/30">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-2xl flex items-center justify-center text-3xl">📭</div>
                                        <p class="text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest text-sm">{{ __('messages.no_requests_found') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50">
                {{ $requests->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById('filter_nid')?.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 14);
            });
        });

        function toggleFilters(id) {
            const panel = document.getElementById(id);
            if (panel) {
                panel.classList.toggle('hidden');
            }
        }
    </script>
    @endpush
</x-app-layout>
