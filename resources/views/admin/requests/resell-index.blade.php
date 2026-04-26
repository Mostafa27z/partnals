<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-2xl">🔁</span>
                <h2 class="text-xl font-black text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('messages.request_type_resell') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12 px-4 max-w-7xl mx-auto">
        <!-- Filters Card -->
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700/50 p-8 mb-10">
            <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-1">{{ __('messages.customer_name') }}</label>
                    <input type="text" name="name" value="{{ request('name') }}" 
                           class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white font-bold px-5 py-3 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all" 
                           placeholder="...">
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-1">{{ __('messages.national_id') }}</label>
                    <input type="text" name="nid" id="filter_nid" value="{{ request('nid') }}" maxlength="14"
                           class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white font-bold px-5 py-3 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all" 
                           placeholder="...">
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-1">{{ __('messages.change_type') }}</label>
                    <select name="resell_type" class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white font-bold px-5 py-3 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                        <option value="">-- {{ __('messages.all') }} --</option>
                        <option value="chip" {{ request('resell_type') == 'chip' ? 'selected' : '' }}>{{ __('messages.on_chip') }}</option>
                        <option value="branch" {{ request('resell_type') == 'branch' ? 'selected' : '' }}>{{ __('messages.at_branch') }}</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest px-1">{{ __('messages.status') }}</label>
                    <select name="status" class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white font-bold px-5 py-3 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                        <option value="">-- {{ __('messages.all') }} --</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('messages.status_pending') }}</option>
                        <option value="inprogress" {{ request('status') == 'inprogress' ? 'selected' : '' }}>{{ __('messages.status_inprogress') }}</option>
                        <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>{{ __('messages.status_done') }}</option>
                    </select>
                </div>

                <div class="lg:col-span-4 flex justify-end gap-3 mt-2">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-8 py-3 rounded-2xl shadow-lg shadow-indigo-500/20 transition-all active:scale-[0.98] flex items-center gap-2">
                        <span>🔍</span> {{ __('messages.search') }}
                    </button>
                    <a href="{{ route('requests.resell.index') }}" class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 font-black px-8 py-3 rounded-2xl transition-all active:scale-[0.98]">
                        {{ __('messages.reset') }}
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700/50 overflow-hidden">
            @if($requests->count())
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 dark:bg-gray-900/50">
                                <th class="px-6 py-5 text-{{ $direction == 'rtl' ? 'right' : 'left' }} text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700/50">{{ __('messages.phone_number') }}</th>
                                <th class="px-6 py-5 text-{{ $direction == 'rtl' ? 'right' : 'left' }} text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700/50">{{ __('messages.customer') }}</th>
                                <th class="px-6 py-5 text-{{ $direction == 'rtl' ? 'right' : 'left' }} text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700/50">{{ __('messages.change_type') }}</th>
                                <th class="px-6 py-5 text-{{ $direction == 'rtl' ? 'right' : 'left' }} text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700/50">{{ __('messages.request_date') }}</th>
                                <th class="px-6 py-5 text-center text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700/50">{{ __('messages.status') }}</th>
                                <th class="px-6 py-5 text-center text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700/50">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                            @foreach ($requests as $request)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/20 transition-colors">
                                    <td class="px-6 py-5">
                                        <span class="font-mono font-black text-gray-900 dark:text-white tracking-widest text-sm">{{ $request->line->phone_number }}</span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col">
                                            <span class="font-black text-gray-800 dark:text-gray-200 text-sm">{{ $request->line->customer->full_name ?? '-' }}</span>
                                            <span class="text-[10px] text-gray-400 dark:text-gray-500 font-bold uppercase tracking-tighter">{{ $request->line->provider }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $request->resellDetails->resell_type == 'chip' ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' : 'bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400' }}">
                                            {{ $request->resellDetails->resell_type == 'chip' ? __('messages.on_chip') : __('messages.at_branch') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="text-xs font-bold text-gray-600 dark:text-gray-400">{{ $request->resellDetails->request_date }}</span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <form action="{{ route('requests.update-status', $request->id) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('{{ __('messages.confirm_update_status') }}')">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="old_status" value="{{ $request->status }}">
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                                                    'inprogress' => 'bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 border-indigo-200 dark:border-indigo-800',
                                                    'done' => 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                                                ];
                                            @endphp
                                            <select name="status" onchange="this.form.submit()" 
                                                    class="appearance-none rounded-xl text-[10px] font-black uppercase tracking-widest px-4 py-1.5 border-2 transition-all cursor-pointer focus:ring-4 focus:ring-opacity-20 {{ $statusColors[$request->status] ?? '' }}">
                                                <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>{{ __('messages.status_pending') }}</option>
                                                <option value="inprogress" {{ $request->status == 'inprogress' ? 'selected' : '' }}>{{ __('messages.status_inprogress') }}</option>
                                                <option value="done" {{ $request->status == 'done' ? 'selected' : '' }}>{{ __('messages.status_done') }}</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <a href="{{ route('requests.resell.details', $request->id) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-gray-50 dark:bg-gray-900 text-gray-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-all"
                                           title="{{ __('messages.view_details') }}">
                                            📄
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-8 py-6 border-t border-gray-50 dark:border-gray-700/50">
                    {{ $requests->appends(request()->query())->links() }}
                </div>
            @else
                <div class="py-24 text-center">
                    <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-[2rem] flex items-center justify-center mx-auto mb-4 text-3xl">📭</div>
                    <p class="text-gray-400 dark:text-gray-500 font-black uppercase tracking-widest text-xs">{{ __('messages.no_requests_found') }}</p>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('filter_nid')?.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 14);
        });
    </script>
    @endpush
</x-app-layout>
