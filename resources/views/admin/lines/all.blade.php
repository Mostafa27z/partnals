<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 md:gap-6">
            <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 flex items-center gap-3">
                <span class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-violet-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none">
                    <span class="text-white text-lg">📱</span>
                </span>
                {{ __('messages.all_lines') }}
            </h2>

            <div class="flex gap-3">
                <a href="{{ route('lines.for-sale') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 font-bold text-sm border border-indigo-100 dark:border-indigo-800/30 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-all shadow-sm">
                    📦 {{ __('messages.for_sale') }}
                </a>
                <a href="{{ route('lines.trashed') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold text-sm border border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all shadow-sm">
                    🗑️ {{ __('messages.trashed') }}
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

        {{-- Error Pop-up --}}
        @if($errors->has('file'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    alert("{{ $errors->first('file') }}");
                });
            </script>
        @endif

        {{-- Action Buttons & Search --}}
        <div class="mb-6 p-5 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50">
            <div class="flex flex-wrap gap-3 mb-5">
                <a href="{{ route('lines.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/25 hover:-translate-y-0.5">
                    ➕ {{ __('messages.new_line') }}
                </a>
                <a href="{{ route('lines.import.form') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold text-sm border border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all shadow-sm">
                    📥 {{ __('messages.upload_excel') }}
                </a>
                <a href="{{ route('lines.export') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 font-bold text-sm border border-emerald-200 dark:border-emerald-800/30 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition-all shadow-sm">
                    ⬇️ {{ __('messages.export_all') }}
                </a>
            </div>

            <form method="GET" action="{{ route('lines.all') }}" class="flex flex-wrap gap-3 items-end">
                <input type="text" name="phone" value="{{ request('phone') }}" placeholder="{{ __('messages.phone_number') }}" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 w-full sm:w-40 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition placeholder:text-gray-400 text-sm" />
                <input type="text" name="customer_name" value="{{ request('customer_name') }}" placeholder="{{ __('messages.customer_name') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 w-full sm:w-40 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition placeholder:text-gray-400 text-sm" />
                <input type="text" name="nid" id="filter_nid" value="{{ request('nid') }}" placeholder="{{ __('messages.national_id') }}" maxlength="14" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 w-full sm:w-40 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition placeholder:text-gray-400 text-sm" />
                <input type="text" name="provider" value="{{ request('provider') }}" placeholder="{{ __('messages.provider') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 w-full sm:w-40 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition placeholder:text-gray-400 text-sm" />
                <div class="w-full sm:w-40">
                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">{{ __('messages.last_invoice_from') }}</label>
                    <input type="date" name="last_invoice_from" value="{{ request('last_invoice_from') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm" />
                </div>
                <div class="w-full sm:w-40">
                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">{{ __('messages.last_invoice_to') }}</label>
                    <input type="date" name="last_invoice_to" value="{{ request('last_invoice_to') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm" />
                </div>
                @if(auth()->user()->role->name !== 'موزع')
                <select name="distributor_id" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 w-full sm:w-40 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
                    <option value="">-- {{ __('messages.distributor') }} --</option>
                    <option value="none" {{ request('distributor_id') === 'none' ? 'selected' : '' }}>{{ __('messages.no_distributor') ?? 'No Distributor' }}</option>
                    @foreach($distributors as $distributor)
                        <option value="{{ $distributor->id }}" {{ request('distributor_id') == $distributor->id ? 'selected' : '' }}>
                            {{ $distributor->name }}
                        </option>
                    @endforeach
                </select>
                @endif
                <select name="plan_id" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 w-full sm:w-40 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
                    <option value="">-- {{ __('messages.plan') }} --</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }}
                        </option>
                    @endforeach
                </select>
                <button class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/25">
                    🔍 {{ __('messages.search') }}
                </button>
            </form>

            {{-- Validation Errors --}}
            @if(isset($searchErrors) && !empty($searchErrors))
                @foreach($searchErrors as $error)
                    <div class="mt-4 p-4 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800/30 text-rose-700 dark:text-rose-300 rounded-xl flex items-center gap-3 font-bold">
                        <span class="text-lg">⚠️</span>
                        {{ $error }}
                    </div>
                @endforeach
            @endif

            {{-- Input Warnings --}}
            @if(isset($searchWarnings) && !empty($searchWarnings))
                @foreach($searchWarnings as $warning)
                    <div class="mt-4 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/30 text-amber-700 dark:text-amber-300 rounded-xl flex items-center gap-3 font-bold">
                        <span class="text-lg">⚠️</span>
                        {{ $warning }}
                    </div>
                @endforeach
            @endif
        </div>


        {{-- Results --}}
        @if($hasSearch)
            <div class="mb-4 text-gray-600 dark:text-gray-400">
                {{ __('messages.total_lines') }}: {{ $totalCount }}
            </div>
        @endif
        @if(!$hasSearch)
            <div class="p-12 text-center bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50">
                <div class="w-20 h-20 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl flex items-center justify-center mx-auto mb-5">
                    <span class="text-4xl">🔍</span>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-lg font-bold">{{ __('messages.search_to_view_lines') }}</p>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
                <form method="POST" id="bulk-action-form">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-center text-sm">
                            <thead>
                                <tr class="bg-gray-50/80 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">

                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.phone_number') }}</th>
                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.national_id') }}</th>
                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.customer_name') }}</th>
                                    <th class="px-4 py-4 text-center text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">الموزع</th>
                                    <th colspan="3" class="px-4 py-4 text-center text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                                @foreach($lines as $line)
                                    <tr class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors duration-200">

                                        <td class="px-4 py-3.5 font-mono font-bold text-gray-800 dark:text-gray-200">{{ $line->phone_number }}</td>
                                        <td class="px-4 py-3.5 text-gray-600 dark:text-gray-400">{{ $line->customer->national_id ?? '-' }}</td>
                                        <td class="px-4 py-3.5 font-medium text-gray-700 dark:text-gray-300">{{ $line->customer->full_name ?? '-' }}</td>
                                        <td class="px-4 py-3.5">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400">
                                                {{ $line->distributor?->name ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-1.5 py-3.5 whitespace-nowrap">
                                            <a href="{{ route('lines.show', $line->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400 font-bold text-xs hover:bg-indigo-100 dark:hover:bg-indigo-900/40 transition-all">
                                                👁️ {{ __('messages.view') }}
                                            </a>
                                        </td>
                                        <td class="px-1.5 py-3.5 whitespace-nowrap">
                                            <a href="{{ route('lines.edit', $line->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 font-bold text-xs hover:bg-amber-100 dark:hover:bg-amber-900/40 transition-all">
                                                ✏️ {{ __('messages.edit') }}
                                            </a>
                                        </td>
                                        <td class="px-1.5 py-3.5 whitespace-nowrap">
                                            @if($line->plan)
                                                <a href="{{ route('invoices.create', $line) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 font-bold text-xs hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition-all">
                                                    💳 {{ __('messages.pay') }}
                                                </a>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- تفاصيل الخط -->
                                    <tr id="line-details-{{ $line->id }}" style="display: none;" class="bg-indigo-50/30 dark:bg-indigo-900/10">
                                        <td colspan="7" class="p-5 text-start space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                                <div class="bg-white dark:bg-gray-800 p-3 rounded-xl border border-gray-100 dark:border-gray-700">
                                                    <span class="text-xs font-black uppercase tracking-wider text-gray-400 block mb-1">{{ __('messages.provider') }}</span>
                                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $line->provider }}</span>
                                                </div>
                                                <div class="bg-white dark:bg-gray-800 p-3 rounded-xl border border-gray-100 dark:border-gray-700">
                                                    <span class="text-xs font-black uppercase tracking-wider text-gray-400 block mb-1">{{ __('messages.serial_number') }}</span>
                                                    <span class="font-bold font-mono text-gray-800 dark:text-gray-200">{{ $line->serial_number ?? '-' }}</span>
                                                </div>
                                                <div class="bg-white dark:bg-gray-800 p-3 rounded-xl border border-gray-100 dark:border-gray-700">
                                                    <span class="text-xs font-black uppercase tracking-wider text-gray-400 block mb-1">{{ __('messages.line_type') }}</span>
                                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $line->line_type === 'prepaid' ? __('messages.prepaid') : __('messages.postpaid') }}</span>
                                                </div>
                                                <div class="bg-white dark:bg-gray-800 p-3 rounded-xl border border-gray-100 dark:border-gray-700">
                                                    <span class="text-xs font-black uppercase tracking-wider text-gray-400 block mb-1">{{ __('messages.plan') }}</span>
                                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $line->plan->name ?? '-' }}</span>
                                                </div>
                                                <div class="bg-white dark:bg-gray-800 p-3 rounded-xl border border-gray-100 dark:border-gray-700">
                                                    <span class="text-xs font-black uppercase tracking-wider text-gray-400 block mb-1">{{ __('messages.distributor') }}</span>
                                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $line->distributor->name ?? '-' }}</span>
                                                </div>
                                                <div class="bg-white dark:bg-gray-800 p-3 rounded-xl border border-gray-100 dark:border-gray-700">
                                                    <span class="text-xs font-black uppercase tracking-wider text-gray-400 block mb-1">{{ __('messages.gcode') }}</span>
                                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $line->gcode }}</span>
                                                </div>
                                                <div class="bg-white dark:bg-gray-800 p-3 rounded-xl border border-gray-100 dark:border-gray-700">
                                                    <span class="text-xs font-black uppercase tracking-wider text-gray-400 block mb-1">{{ __('messages.attached_at') }}</span>
                                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($line->attached_at)->format('Y-m-d') }}</span>
                                                </div>
                                                <div class="bg-white dark:bg-gray-800 p-3 rounded-xl border border-gray-100 dark:border-gray-700">
                                                    <span class="text-xs font-black uppercase tracking-wider text-gray-400 block mb-1">{{ __('messages.last_invoice') }}</span>
                                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($line->last_invoice_date)->format('Y-m-d') }}</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Bulk Actions --}}
                    <div class="p-4 bg-gray-50/80 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700 flex flex-wrap justify-end gap-3">
                        <button type="submit" formaction="{{ route('lines.export.selected') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-sm hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-500/25">
                            ⬇️ {{ __('messages.export_selected') }}
                        </button>
                    </div>
                </form>

                {{-- Pagination --}}
                <div class="p-5 border-t border-gray-100 dark:border-gray-700">
                    {{ $lines->appends(request()->query())->links() }}
                </div>
            </div>
        @endif

        {{-- Quick Action (single line) --}}
        @if($lines->count() === 1)
            @php $line = $lines->first(); @endphp
            <div class="mt-6 p-6 bg-indigo-50/50 dark:bg-indigo-900/10 rounded-2xl border border-indigo-100 dark:border-indigo-800/30 shadow-sm">
                <h3 class="font-black text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center text-sm">📱</span>
                    {{ __('messages.phone') }}: <span class="font-mono">{{ $line->phone_number }}</span>
                </h3>

                <form method="GET" onsubmit="return redirectToCreateRequest(event)">
                    <label for="request-type" class="block mb-2 font-bold text-sm text-gray-600 dark:text-gray-400">{{ __('messages.select_request_type') }}:</label>
                    <div class="flex flex-wrap gap-3 items-end">
                        <select id="request-type" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 w-full max-w-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm" required>
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
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/25">
                            ➕ {{ __('messages.create_request') }}
                        </button>
                    </div>
                </form>
            </div>
        @endif

        {{-- Import Forms --}}
        @php
            $forms = [
                ['route' => 'requests.stop.import', 'label' => __('messages.import_stop_requests'), 'sample' => 'sample_stop_requests.xlsx'],
                ['route' => 'requests.resell.import', 'label' => __('messages.import_resell_requests'), 'sample' => 'sample_resell_requests.xlsx'],
                ['route' => 'requests.change-plan.import', 'label' => __('messages.import_change_plan_requests'), 'sample' => 'sample_change_plan_requests.xlsx'],
                ['route' => 'requests.change-chip.import', 'label' => __('messages.import_change_chip_requests'), 'sample' => 'sample_change_chip_requests.xlsx'],
                ['route' => 'requests.change-distributor.import', 'label' => __('messages.import_change_distributor_requests'), 'sample' => 'sample_change_distributor_requests.xlsx'],
                ['route' => 'requests.change-date.import', 'label' => __('messages.import_change_date_requests'), 'sample' => 'sample_change_date_requests.xlsx'],
                ['route' => 'requests.resume.import', 'label' => __('messages.import_resume_requests'), 'sample' => 'sample_resume_requests.xlsx'],
                ['route' => 'requests.pause.import', 'label' => __('messages.import_pause_requests'), 'sample' => 'sample_pause_requests.xlsx'],
            ];
        @endphp

        @foreach ($forms as $form)
            <form action="{{ route($form['route']) }}" method="POST" enctype="multipart/form-data"
                  class="mt-3 flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4 bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 w-full">
                @csrf
                <input type="file" name="file" accept=".xlsx" required
                       class="text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 dark:file:bg-indigo-900/30 file:text-indigo-700 dark:file:text-indigo-400 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/50 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 w-full sm:w-auto cursor-pointer" />
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/25 w-full sm:w-auto justify-center">
                    🚀 {{ $form['label'] }}
                </button>
                <a href="{{ asset('storage/' . $form['sample']) }}" download 
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold text-sm border border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all shadow-sm w-full sm:w-auto justify-center">
                    📄 تنزيل نموذج فارغ
                </a>
            </form>
        @endforeach

        {{-- Scripts for single line --}}
        @if($lines->count() === 1)
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


            </script>
            @endpush
        @endif

        @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                document.getElementById('filter_nid')?.addEventListener('input', (e) => {
                    e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 14);
                });
            });
        </script>
        @endpush

</x-app-layout>