<x-app-layout>  
    <x-slot name="header">  
        <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 flex items-center gap-3">
            <span class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-violet-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none">
                <span class="text-white text-lg">📞</span>
            </span>
            {{ __('messages.line_details') }}
        </h2>  
    </x-slot>  

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">
    <!-- Quick Request & Pay Buttons (mirroring All view) -->
    <div class="flex gap-3 mb-4">
        <a href="{{ route('invoices.create', $line) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-sm hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-500/25">💳 {{ __('messages.pay') }}</a>
        <form method="GET" onsubmit="return redirectToCreateRequest(event)" class="inline">
            <select id="request-type" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 mr-2" required>
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
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/25">{{ __('messages.create_request') }}</button>
        </form>
    </div>  
        <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 space-y-8">  
            
            <!-- Details Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-gray-700 dark:text-gray-300">  
                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.id') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->id }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.customer_id') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->customer_id ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.attached_date') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->attached_at ?? '-' }}</p>
                </div>

                <div class="bg-indigo-50/50 dark:bg-indigo-900/10 p-4 rounded-xl border border-indigo-100 dark:border-indigo-800/30">
                    <p class="text-[10px] font-black uppercase tracking-widest text-indigo-500 dark:text-indigo-400 mb-1">{{ __('messages.phone_number') }}</p>
                    <p class="font-mono font-black text-indigo-700 dark:text-indigo-300 text-lg">{{ $line->phone_number }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.serial_number') }}</p>
                    <p class="font-mono font-bold text-gray-800 dark:text-gray-200">{{ $line->serial_number ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.secondary_phone') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->second_phone ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.provider') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->provider }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.status') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->status ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.offer_name') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->offer_name ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.branch_name') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->branch_name ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.employee_name') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->employee_name ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.gcode') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->gcode ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.distributor') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->distributor->name ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.line_type') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">
                        {{ $line->line_type === 'prepaid' ? __('messages.prepaid') : __('messages.postpaid') }}
                    </p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.plan') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->plan->name ?? __('messages.not_specified') }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.package') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->package ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.last_invoice_date') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->last_invoice_date ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.payment_date') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->payment_date ?? '-' }}</p>
                </div>

                <div class="bg-indigo-50/30 dark:bg-indigo-900/10 p-4 rounded-xl border border-indigo-100 dark:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-indigo-500 dark:text-indigo-400 mb-1">{{ __('messages.provider_day') ?? 'يوم تشغيل المزود' }}</p>
                    <p class="font-black text-indigo-700 dark:text-indigo-300">
                        {{ $line->providerData->invoice_day ?? '-' }} {{ __('messages.day_of_month') ?? 'من الشهر' }}
                    </p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors col-span-1 sm:col-span-2 lg:col-span-3">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.notes') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->notes ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.added_by') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->addedBy->name ?? __('messages.unknown') }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.created_at') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->created_at->format('Y-m-d H:i') }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.updated_at') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->updated_at->format('Y-m-d H:i') }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.for_sale') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">
                        {{ $line->for_sale ? __('messages.yes') : __('messages.no') }}
                    </p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.sale_price') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $line->sale_price ?? '-' }}</p>
                </div>

                <div class="bg-gray-50/80 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50 hover:border-indigo-200 dark:hover:border-indigo-800/30 transition-colors col-span-1 sm:col-span-2 lg:col-span-3">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.deleted_at') }}</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">
                        {{ $line->deleted_at ? $line->deleted_at->format('Y-m-d H:i') : __('messages.not_deleted') }}
                    </p>
                </div>
            </div>

            <!-- Back Button -->
            <!-- Requests Table -->
            <div class="pt-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">{{ __('messages.requests_for_line') ?? 'Requests' }}</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-center border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm">
                            <thead class="bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 text-sm font-semibold">
                                <tr>
                                    <th class="p-3">{{ __('messages.type') }}</th>
                                    <th class="p-3">{{ __('messages.status') }}</th>
                                    <th class="p-3">{{ __('messages.request_date') }}</th>
                                    <th class="p-3">{{ __('messages.requested_by') }}</th>
                                    <th class="p-3">{{ __('messages.notes') }}</th>
                                    <th class="p-3">{{ __('messages.details') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($requests ?? collect() as $req)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors border-b border-gray-100 dark:border-gray-700/50">
                                        <td class="p-3">{{ __('messages.request_type_'.$req->request_type) ?? $req->request_type }}</td>
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
                                        <td class="p-3">{{ $req->requestedBy->name ?? '-' }}</td>
                                        <td class="p-3">{{ \Illuminate\Support\Str::limit($req->notes ?? ($req->resellDetails->comment ?? ''), 80) }}</td>
                                        <td class="p-3 flex items-center justify-center gap-2">
                                            <a href="{{ route('requests.show', $req->id) }}" class="text-indigo-600 dark:text-indigo-400 font-bold hover:underline transition-all">{{ __('messages.view') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $requests->links() ?? '' }}
                    </div>
                </div>
            </div>
            @if($line->customer)  
                <div class="pt-4">  
                    <a href="{{ route('customers.show', $line->customer) }}"  
                       class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold text-sm border border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all shadow-sm">  
                        🔙 {{ __('messages.back_to_customer_details') }}
                    </a>  
                </div>  
            @endif  
        </div>  
    </div>  
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
</script>
@endpush
</x-app-layout>
