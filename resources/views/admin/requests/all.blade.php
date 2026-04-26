<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200" dir="rtl">
            📋 {{ __('messages.all_requests') }}
        </h2>
    </x-slot>

    <!-- Filter Form -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6" dir="rtl">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <input type="text" name="phone" value="{{ request('phone') }}" placeholder="{{ __('messages.phone_number') }}" class="p-2 border border-gray-300 dark:border-gray-600 rounded-lg w-full bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring focus:ring-blue-200" />
            <input type="text" name="nid" value="{{ request('nid') }}" placeholder="{{ __('messages.national_id') }}" class="p-2 border border-gray-300 dark:border-gray-600 rounded-lg w-full bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring focus:ring-blue-200" />
            
            <select name="type" class="p-2 border border-gray-300 dark:border-gray-600 rounded-lg w-full bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring focus:ring-blue-200">
                <option value="">{{ __('messages.select_request_type') }}</option>
                <option value="stop" {{ request('type') == 'stop' ? 'selected' : '' }}>{{ __('messages.type_stop') }}</option>
                <option value="resell" {{ request('type') == 'resell' ? 'selected' : '' }}>{{ __('messages.type_resell') }}</option>
                <option value="change_plan" {{ request('type') == 'change_plan' ? 'selected' : '' }}>{{ __('messages.type_change_plan') }}</option>
                <option value="resume" {{ request('type') == 'resume' ? 'selected' : '' }}>{{ __('messages.type_resume') }}</option>
                <option value="pause" {{ request('type') == 'pause' ? 'selected' : '' }}>{{ __('messages.type_pause') }}</option>
                <option value="change_chip" {{ request('type') == 'change_chip' ? 'selected' : '' }}>{{ __('messages.type_change_chip') }}</option>
            </select>
            
            <input type="date" name="from" value="{{ request('from') }}" class="p-2 border border-gray-300 dark:border-gray-600 rounded-lg w-full bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring focus:ring-blue-200" />
            <input type="date" name="to" value="{{ request('to') }}" class="p-2 border border-gray-300 dark:border-gray-600 rounded-lg w-full bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring focus:ring-blue-200" />
            <input type="text" name="provider" value="{{ request('provider') }}" placeholder="{{ __('messages.provider_placeholder') }}" class="p-2 border border-gray-300 dark:border-gray-600 rounded-lg w-full bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring focus:ring-blue-200" />

            <div class="col-span-full flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                    🔍 {{ __('messages.search') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6" dir="rtl">
        <form id="bulk-action-form" method="POST" action="{{ route('requests.bulk-action') }}">
            @csrf
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <select name="new_status" class="border border-gray-300 dark:border-gray-600 p-2 rounded-lg max-w-xs bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring focus:ring-yellow-200" required>
                    <option value="">{{ __('messages.select_new_status') }}</option>
                    <option value="pending">{{ __('messages.status_pending') }}</option>
                    <option value="inprogress">{{ __('messages.status_inprogress') }}</option>
                    <option value="done">{{ __('messages.status_done') }}</option>
                    <option value="cancelled">{{ __('messages.status_cancelled') }}</option>
                </select>

                <div class="flex flex-wrap gap-3">
                    <button type="submit" name="action" value="change_status" class="bg-yellow-500 text-white px-4 py-2 rounded-lg shadow hover:bg-yellow-600 transition">
                        ✅ {{ __('messages.change_status') }}
                    </button>
                    <button type="submit" name="action" value="export" class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 transition">
                        📁 {{ __('messages.export_selected') }}
                    </button>
                    <button type="submit" name="action" value="change_and_export" class="bg-blue-700 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-800 transition">
                        🛠 {{ __('messages.change_and_export') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Requests Table -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6" dir="rtl">
        <div class="overflow-x-auto">
                <table class="min-w-full text-center border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm">
                    <thead class="bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 text-sm font-semibold">
                        <tr>
                            <th class="p-3"><input type="checkbox" onclick="toggleAll(this)"></th>
                            <th class="p-3">{{ __('messages.number') }}</th>
                            <th class="p-3">{{ __('messages.type') }}</th>
                            <th class="p-3">{{ __('messages.provider') }}</th>
                            <th class="p-3">{{ __('messages.status') }}</th>
                            <th class="p-3">{{ __('messages.request_date') }}</th>
                            <th class="p-3">{{ __('messages.details') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($requests as $req)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors border-b border-gray-100 dark:border-gray-700/50">
                                <td class="p-3"><input type="checkbox" name="selected_requests[]" value="{{ $req->id }}" form="bulk-action-form" class="rounded dark:bg-gray-900 dark:border-gray-700"></td>
                                <td class="p-3 font-mono text-gray-800 dark:text-gray-200">{{ $req->line->phone_number ?? '-' }}</td>
                                <td class="p-3">{{ __('messages.request_type_'.$req->request_type) ?? $req->request_type }}</td>
                                <td class="p-3">{{ $req->line->provider ?? '-' }}</td>
                                <td class="p-3">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold 
                                        @if($req->status == 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                                        @elseif($req->status == 'inprogress') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                        @elseif($req->status == 'done') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                        @elseif($req->status == 'cancelled') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                        @endif">
                                        {{ __('messages.status_'.$req->status) ?? $req->status }}
                                    </span>
                                </td>
                                <td class="p-3 text-gray-600 dark:text-gray-400 font-medium">{{ $req->created_at->format('Y-m-d') }}</td>
                                <td class="p-3 flex items-center justify-center gap-2">
                                    <button type="button" onclick="toggleEdit({{ $req->id }})" class="text-blue-600 dark:text-blue-400 font-bold hover:underline transition-all">
                                        {{ __('messages.edit') }}
                                    </button>
                                    <a href="{{ route('requests.show', $req->id) }}" class="text-indigo-600 dark:text-indigo-400 font-bold hover:underline transition-all">
                                        {{ __('messages.view') }}
                                    </a>
                                </td>
                            </tr>
                            {{-- Expandable Edit Row --}}
                            <tr id="edit-row-{{ $req->id }}" class="hidden bg-gray-50/50 dark:bg-gray-900/20">
                                <td colspan="7" class="p-6">
                                    <form action="{{ route('requests.update-details', $req->id) }}" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PUT')
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            @if($req->request_type === 'stop')
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">{{ __('messages.reason') }}</label>
                                                    <input type="text" name="reason" value="{{ $req->stopDetails->reason ?? '' }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-sm">
                                                </div>
                                            @elseif($req->request_type === 'resell')
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">{{ __('messages.new_serial') }}</label>
                                                    <input type="text" name="new_serial" value="{{ $req->resellDetails->new_serial ?? '' }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">{{ __('messages.sale_price') }}</label>
                                                    <input type="number" step="0.01" name="sale_price" value="{{ $req->resellDetails->sale_price ?? '' }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-sm">
                                                </div>
                                            @elseif($req->request_type === 'change_plan')
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">{{ __('messages.plan') }}</label>
                                                    <select name="new_plan_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-sm">
                                                        @foreach($plans->where('provider', $req->line->provider) as $plan)
                                                            <option value="{{ $plan->id }}" {{ ($req->changePlan->new_plan_id ?? '') == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @elseif($req->request_type === 'change_chip')
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">{{ __('messages.new_serial') }}</label>
                                                    <input type="text" name="new_serial" value="{{ $req->changeChip->new_serial ?? '' }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-sm">
                                                </div>
                                            @elseif($req->request_type === 'change_distributor')
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">{{ __('messages.distributor') }}</label>
                                                    <select name="new_distributor_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-sm">
                                                        @foreach($distributors as $dist)
                                                            <option value="{{ $dist->id }}" {{ ($req->changeDistributor->new_distributor_id ?? '') == $dist->id ? 'selected' : '' }}>{{ $dist->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @elseif($req->request_type === 'change_date')
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">{{ __('messages.new_date') }}</label>
                                                    <input type="date" name="new_date" value="{{ $req->changeDate->new_date ?? '' }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-sm">
                                                </div>
                                            @endif
                                            
                                            {{-- Common Comment Field --}}
                                            @php 
                                                $comment = $req->stopDetails->comment ?? $req->resellDetails->comment ?? $req->changeChip->comment ?? $req->pause->comment ?? $req->resume->comment ?? $req->changePlan->comment ?? $req->changeDistributor->comment ?? $req->changeDate->comment ?? '';
                                            @endphp
                                            <div class="md:col-span-2">
                                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">{{ __('messages.notes') }}</label>
                                                <textarea name="comment" rows="1" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-sm">{{ $comment }}</textarea>
                                            </div>
                                        </div>
                                        <div class="flex justify-end gap-3 mt-4">
                                            <button type="button" onclick="toggleEdit({{ $req->id }})" class="text-sm font-bold text-gray-500 hover:text-gray-700">{{ __('messages.cancel') }}</button>
                                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-bold shadow hover:bg-indigo-700 transition">
                                                💾 {{ __('messages.save') }}
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $requests->links() }}
    </div>

    @push('scripts')
    <script>
        function toggleAll(source) {
            document.querySelectorAll('input[name="selected_requests[]"]').forEach(cb => cb.checked = source.checked);
        }

        function toggleEdit(id) {
            const row = document.getElementById(`edit-row-${id}`);
            if (row.classList.contains('hidden')) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        }
    </script>
    @endpush
</x-app-layout>
