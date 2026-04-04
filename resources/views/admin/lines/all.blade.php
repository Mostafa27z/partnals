<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 md:gap-6">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
        📱 {{ __('messages.all_lines') }}
    </h2>

    <div class="flex gap-2 md:gap-4">
        <a href="{{ route('lines.for-sale') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
            📦 {{ __('messages.for_sale') }}
        </a>
        <a href="{{ route('lines.trashed') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow">
            🗑️ {{ __('messages.trashed') }}
        </a>
    </div>
</div>

    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 break-words max-w-full text-sm sm:text-base" dir="rtl">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow">
                {{ session('success') }}
            </div>
        @endif


        {{-- Search Form --}}
        <div class="mb-4 flex flex-wrap gap-4 items-center">
    <a href="{{ route('lines.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
        ➕ {{ __('messages.new_line') }}
    </a>
    <a href="{{ route('lines.import.form') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow">
        📥 {{ __('messages.upload_excel') }}
    </a>
    <a href="{{ route('lines.export') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
        ⬇️ {{ __('messages.export_all') }}
    </a>

    <form method="GET" action="{{ route('lines.all') }}" class="flex flex-wrap gap-4">
        <input type="text" name="phone" value="{{ request('phone') }}" placeholder="{{ __('messages.phone_number') }}" class="input input-bordered w-full sm:w-40" />
        <input type="text" name="nid" value="{{ request('nid') }}" placeholder="{{ __('messages.national_id') }}" class="input input-bordered w-full sm:w-40" />
        <input type="text" name="provider" value="{{ request('provider') }}" placeholder="{{ __('messages.provider') }}" class="input input-bordered w-full sm:w-40" />
        <input type="text" name="distributor" value="{{ request('distributor') }}" placeholder="{{ __('messages.distributor') }}" class="input input-bordered w-full sm:w-40" />
        <select name="plan_id" class="input input-bordered w-full sm:w-40">
            <option value="">-- {{ __('messages.plan') }} --</option>
            @foreach($plans as $plan)
                <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                    {{ $plan->name }}
                </option>
            @endforeach
        </select>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
            🔍 {{ __('messages.search') }}
        </button>
    </form>
</div>


        {{-- Export Form --}}
        <div class="bg-white dark:bg-gray-800 overflow-x-auto w-full rounded-lg shadow border border-gray-200 dark:border-gray-700">
    <form method="POST" action="{{ route('lines.export.selected') }}">
        @csrf
       <div class="overflow-x-auto">
    <table class="min-w-full text-center text-sm sm:text-base">
        <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 text-base sm:text-lg">
            <tr>
                <th class="px-4 py-2 text-center"><input type="checkbox" id="select-all"></th>
                <th class="px-4 py-2 text-center">{{ __('messages.phone_number') }}</th>
                <th class="px-4 py-2 text-center">{{ __('messages.national_id') }}</th>
                <th class="px-4 py-2 text-center">{{ __('messages.customer_name') }}</th>
                <th class="px-4 py-2 text-center">{{ __('messages.status') }}</th>
                <th colspan="4" class="px-4 py-2 text-center">{{ __('messages.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lines as $line)
                <tr class="hover:bg-gray-50 dark:bg-gray-700/50 transition-colors">
                    <td class="px-4 py-2">
                        <input type="checkbox" name="selected_lines[]" value="{{ $line->id }}" class="line-checkbox">
                    </td>
                    <td class="px-4 py-2 font-medium">{{ $line->phone_number }}</td>
                    <td class="px-4 py-2">{{ $line->customer->national_id ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $line->customer->full_name ?? '-' }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded font-semibold {{ $line->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $line->status === 'active' ? __('messages.active') : __('messages.inactive') }}
                        </span>
                    </td>
                    <td class="px-2 py-2 whitespace-nowrap">
                        <a href="{{ route('lines.show', $line->id) }}" 
                           class="bg-blue-100 hover:bg-blue-200 text-blue-800 font-semibold px-2 sm:px-3 py-1 rounded transition inline-block text-sm sm:text-base">
                            👁️ {{ __('messages.view') }}
                        </a>
                    </td>
                    <td class="px-2 py-2 whitespace-nowrap">
                        <a href="{{ route('lines.edit', $line->id) }}" 
                           class="bg-yellow-100 hover:bg-yellow-200 text-yellow-800 font-semibold px-2 sm:px-3 py-1 rounded transition inline-block text-sm sm:text-base">
                            ✏️ {{ __('messages.edit') }}
                        </a>
                    </td>
                    <td class="px-2 py-2 whitespace-nowrap">
                        <button type="button" 
                                class="bg-red-100 hover:bg-red-200 text-red-800 font-semibold px-2 sm:px-3 py-1 rounded transition inline-block text-sm sm:text-base"
                                onclick="confirmDelete({{ $line->id }})">
                            🗑 {{ __('messages.delete') }}
                        </button>
                    </td>
                    <td class="px-2 py-2 whitespace-nowrap">
                        @if($line->plan)
                            <a href="{{ route('invoices.create', $line) }}" 
                               class="bg-green-100 hover:bg-green-200 text-green-800 font-semibold px-2 sm:px-3 py-1 rounded transition inline-block text-sm sm:text-base">
                                💳 {{ __('messages.pay') }}
                            </a>
                        @endif
                    </td>
                </tr>

                <!-- تفاصيل الخط -->
                <tr id="line-details-{{ $line->id }}" style="display: none;" class="bg-gray-100 dark:bg-gray-900 text-sm sm:text-base">
                    <td colspan="9" class="p-4 text-start space-y-1">
                        <div><strong>{{ __('messages.provider') }}:</strong> {{ $line->provider }}</div>
                        <div><strong>{{ __('messages.line_type') }}:</strong> {{ $line->line_type === 'prepaid' ? __('messages.prepaid') : __('messages.postpaid') }}</div>
                        <div><strong>{{ __('messages.plan') }}:</strong> {{ $line->plan->name ?? '-' }}</div>
                        <div><strong>{{ __('messages.distributor') }}:</strong> {{ $line->distributor ?? '-' }}</div>
                        <div><strong>GCode:</strong> {{ $line->gcode }}</div>
                        <div><strong>{{ __('messages.attached_at') }}:</strong> {{ \Carbon\Carbon::parse($line->attached_at)->format('Y-m-d') }}</div>
                        <div><strong>{{ __('messages.last_invoice') }}:</strong> {{ \Carbon\Carbon::parse($line->last_invoice_date)->format('Y-m-d') }}</div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


        <div class="mt-4 text-end">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">⬇️ {{ __('messages.export_selected') }}</button>
        </div>
    </form>

    <div class="mt-4">
        {{ $lines->links() }}
    </div>
</div>

<div class="mt-6 p-4 bg-blue-50 rounded shadow break-words max-w-full text-sm sm:text-base">
                <h3 class="font-bold mb-2 break-words max-w-full text-sm sm:text-base">📱 {{ __('messages.phone') }}: {{ $line->phone_number }}</h3>

                <form method="GET" onsubmit="return redirectToCreateRequest(event)">
                    <label for="request-type" class="block mb-1 font-medium break-words max-w-full text-sm sm:text-base">{{ __('messages.select_request_type') }}:</label>
                    <select id="request-type" class="input input-bordered w-full max-w-xs break-words max-w-full text-sm sm:text-base" required>
                        <option value="">-- {{ __('messages.select_type') }} --</option>
                        <option value="resell">{{ __('messages.resell') }}</option>
                        <option value="change-plan">{{ __('messages.change_plan') }}</option>
                        <option value="change-chip">{{ __('messages.change_chip') }}</option>
                        <option value="pause">{{ __('messages.pause') }}</option>
                        <option value="resume">{{ __('messages.resume') }}</option>
                        <option value="change-date">{{ __('messages.change_date') }}</option>
                        <option value="change-distributor">{{ __('messages.change_distributor') }}</option>
                        <option value="stop-line">{{ __('messages.stop_line') }}</option>
                    </select>

                    <button type="submit" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded break-words max-w-full text-sm sm:text-base">
                        ➕ {{ __('messages.create_request') }}
                    </button>
                </form>
            </div>
        @php
            $forms = [
                ['route' => 'requests.stop.import', 'label' => __('messages.import_stop_requests')],
                ['route' => 'requests.resell.import', 'label' => __('messages.import_resell_requests')],
                ['route' => 'requests.change-plan.import', 'label' => __('messages.import_change_plan_requests')],
                ['route' => 'requests.change-chip.import', 'label' => __('messages.import_change_chip_requests')],
                ['route' => 'requests.change-distributor.import', 'label' => __('messages.import_change_distributor_requests')],
                ['route' => 'requests.change-date.import', 'label' => __('messages.import_change_date_requests')],
                ['route' => 'requests.resume.import', 'label' => __('messages.import_resume_requests')],
                ['route' => 'requests.pause.import', 'label' => __('messages.import_pause_requests')],
            ];
        @endphp

        @foreach ($forms as $form)
            <form action="{{ route($form['route']) }}" method="POST" enctype="multipart/form-data"
                  class="mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4 bg-white dark:bg-gray-800 p-4 rounded shadow w-full">
                @csrf
                <input type="file" name="file" accept=".xlsx" required
                       class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded p-2 w-full sm:w-auto text-sm sm:text-base" />

                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded w-full sm:w-auto text-sm sm:text-base text-center">
                    {{ $form['label'] }}
                </button>
            </form>
        @endforeach

        @if($lines->count() === 1)
            @php
                $line = $lines->first();
            @endphp

            

            @push('scripts')
            <script>
                function redirectToCreateRequest(event) {
                    event.preventDefault();
                    const type = document.getElementById('request-type').value;
                    if (!type) {
                        alert("❌ {{ __('messages.select_request_type_first') }}");
                        return false;
                    }

                    const lineId = {{ $line->id }};
                    const baseUrl = {
                        'resell': '/admin/requests/resell/' + lineId,
                        'change-plan': '/admin/requests/change-plan/' + lineId,
                        'change-chip': '/admin/requests/change-chip/' + lineId,
                        'pause': '/admin/requests/pause/' + lineId,
                        'resume': '/admin/requests/resume/' + lineId + '/create',
                        'change-date': '/admin/requests/change-date/' + lineId,
                        'change-distributor': '/admin/requests/change-distributor/' + lineId,
                        'stop-line': '/admin/requests/stop/' + lineId,
                    };

                    if (baseUrl[type]) {
                        window.location.href = baseUrl[type];
                    } else {
                        alert("❌ {{ __('messages.request_type_not_supported') }}");
                    }
                }
                function toggleDetails(id) {
    const row = document.getElementById('line-details-' + id);
    row.style.display = row.style.display === 'none' ? '' : 'none';
}

document.getElementById('select-all').addEventListener('change', function () {
    document.querySelectorAll('.line-checkbox').forEach(cb => cb.checked = this.checked);
});

function confirmDelete(lineId) {
    if (confirm('{{ __('messages.delete_line_confirmation') }}')) {
        const form = document.getElementById('delete-form');
        form.action = `/admin/lines/${lineId}`;
        form.submit();
    }
}

            </script>
            @endpush
        @endif

        {{-- Hidden Delete Form --}}
        <form method="POST" id="delete-form" style="">
            @csrf
            @method('DELETE')
        </form>

        @push('scripts')
            <script>
                document.getElementById('select-all').addEventListener('change', function () {
                    document.querySelectorAll('.line-checkbox').forEach(cb => cb.checked = this.checked);
                });

                // function confirmDelete(lineId) {
                //     if (confirm('{{ __('messages.delete_line_confirmation') }}')) {
                //         const form = document.getElementById('delete-form');
                //         form.action = `/admin/lines/${lineId}`;
                //         form.submit();
                //     }
                // }
            </script>
        @endpush
    </div>
</x-app-layout>