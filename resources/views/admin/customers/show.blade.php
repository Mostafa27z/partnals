<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.Customer Details') }}
        </h2>
    </x-slot>

    <div class="py-8" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ── Customer Info Card ── --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-lg overflow-hidden">

                {{-- Profile Header --}}
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white leading-tight">
                            {{ $customer->full_name }}
                        </p>
                        <div class="flex items-center flex-wrap gap-x-3 gap-y-1 mt-1">
                            <span class="text-xs text-gray-400">#{{ $customer->id }}</span>
                            <span class="text-gray-300 dark:text-gray-600 text-xs">·</span>
                            <span class="text-xs text-gray-400">{{ __('messages.National ID') }}: {{ $customer->national_id }}</span>
                            <span class="text-gray-300 dark:text-gray-600 text-xs">·</span>
                            @if($customer->deleted_at)
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2 py-0.5 rounded-full
                                             bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400
                                             border border-rose-200 dark:border-rose-700">
                                    <svg class="w-2.5 h-2.5" viewBox="0 0 8 8" fill="currentColor"><circle cx="4" cy="4" r="4"/></svg>
                                    {{ __('messages.Deleted') }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2 py-0.5 rounded-full
                                             bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400
                                             border border-emerald-200 dark:border-emerald-700">
                                    <svg class="w-2.5 h-2.5" viewBox="0 0 8 8" fill="currentColor"><circle cx="4" cy="4" r="4"/></svg>
                                    {{ __('messages.Not Deleted') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('customers.addline', $customer) }}"
   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition duration-150">
    + {{ __('messages.add_line') }}
</a>
                    </div>
                </div>

                {{-- Fields Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 divide-y divide-gray-100 dark:divide-gray-700
                            sm:divide-y-0 sm:[&>*]:border-b sm:[&>*]:border-gray-100 sm:dark:[&>*]:border-gray-700">

                    @php
                        $fields = [
                            ['icon' => 'M8 7V3m0 4a4 4 0 100 8 4 4 0 000-8z M3.05 11a9 9 0 1117.9 0', 'label' => __('messages.Birth Date'), 'value' => $customer->birth_date, 'mono' => true],
                            ['icon' => 'M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z M22 6l-10 7L2 6', 'label' => __('messages.Email'), 'value' => $customer->email, 'mono' => true],
                            ['icon' => 'M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z M12 10a2 2 0 100-4 2 2 0 000 4z', 'label' => __('messages.Address'), 'value' => $customer->address],
                            ['icon' => 'M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z', 'label' => __('messages.contact_number'), 'value' => $customer->contact_number, 'mono' => true],
                            ['icon' => 'M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z', 'label' => __('messages.whatsapp_number'), 'value' => $customer->whatsapp_number, 'mono' => true],
                        ];
                    @endphp

                    @foreach($fields as $i => $field)
                        <div class="flex items-start gap-3 px-6 py-4 {{ $i % 2 === 0 ? 'sm:border-r sm:border-gray-100 sm:dark:border-gray-700' : '' }}">
                            <div class="mt-0.5 flex-shrink-0 w-8 h-8 rounded-lg bg-gray-50 dark:bg-gray-700
                                        flex items-center justify-center text-gray-400 dark:text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $field['icon'] }}"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 mb-0.5">
                                    {{ $field['label'] }}
                                </p>
                                @if($field['value'])
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate
                                               {{ ($field['mono'] ?? false) ? 'font-mono' : '' }}">
                                        {{ $field['value'] }}
                                    </p>
                                @else
                                    <p class="text-sm text-gray-400 italic">—</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Timestamps Footer --}}
                <div class="grid grid-cols-3 divide-x divide-gray-100 dark:divide-gray-700
                            border-t border-gray-100 dark:border-gray-700 bg-gray-50/60 dark:bg-gray-900/30">
                    @foreach([
                        [__('messages.Created At'), $customer->created_at->format('Y-m-d H:i'), false],
                        [__('messages.Updated At'), $customer->updated_at->format('Y-m-d H:i'), false],
                        [__('messages.Deleted At'), $customer->deleted_at?->format('Y-m-d H:i'), true],
                    ] as [$label, $value, $isDel])
                        <div class="px-4 py-3">
                            <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 mb-1">
                                {{ $label }}
                            </p>
                            <p class="font-mono text-xs
                                       {{ $isDel && $value ? 'text-rose-500' : 'text-gray-600 dark:text-gray-400' }}
                                       {{ !$value ? 'italic text-gray-400' : '' }}">
                                {{ $value ?? '—' }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ── Linked Lines ── --}}
            @if($customer->lines->count())
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-lg overflow-hidden">

                <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-700 dark:text-gray-300">
                        {{ __('messages.Linked Lines') }}
                    </h3>
                    <span class="bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400
                                 border border-indigo-200 dark:border-indigo-700
                                 text-[11px] font-bold px-3 py-0.5 rounded-full tracking-wider">
                        {{ $customer->lines->count() }} {{ __('messages.Lines') }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-5">
                    @foreach($customer->lines as $line)
                        @php
                            $accent   = match($line->status) { 'active' => 'bg-emerald-400', 'paused' => 'bg-amber-400', default => 'bg-rose-400' };
                            $hoverBdr = match($line->status) { 'active' => 'hover:border-emerald-400', 'paused' => 'hover:border-amber-400', default => 'hover:border-rose-400' };
                        @endphp

                        <div class="relative bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600
                                    rounded-xl p-4 overflow-hidden transition-all duration-200
                                    hover:-translate-y-1 hover:shadow-lg {{ $hoverBdr }}">

                            {{-- Top accent bar --}}
                            <div class="absolute top-0 left-0 right-0 h-0.5 {{ $accent }}"></div>

                            {{-- Phone + Status --}}
                            <div class="flex items-start justify-between mb-2.5 mt-1">
                                <div>
                                    <p class="font-mono text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $line->phone_number }}
                                    </p>
                                    <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-400 mt-0.5">
                                        {{ $line->provider }}
                                    </p>
                                </div>
                                @if($line->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-full
                                                 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400
                                                 border border-emerald-200 dark:border-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        {{ __('messages.active') }}
                                    </span>
                                @elseif($line->status === 'paused')
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-full
                                                 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400
                                                 border border-amber-200 dark:border-amber-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                        {{ __('messages.paused') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold px-2.5 py-1 rounded-full
                                                 bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400
                                                 border border-rose-200 dark:border-rose-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                        {{ $line->status }}
                                    </span>
                                @endif
                            </div>

                            {{-- Serial --}}
                            @if($line->serial_number)
                                <div class="flex items-center gap-2 mb-2.5">
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">S/N</span>
                                    <span class="font-mono text-[11px] text-gray-400 bg-gray-100 dark:bg-gray-800 rounded px-2 py-0.5">
                                        {{ $line->serial_number }}
                                    </span>
                                </div>
                            @endif

                            {{-- Plan + Type --}}
                            <div class="flex items-center justify-between mb-3">
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-md
                                             bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400
                                             border border-indigo-100 dark:border-indigo-800">
                                    ★ {{ $line->plan?->name ?? '—' }}
                                </span>
                                <span class="text-[10px] font-semibold uppercase tracking-wider text-gray-400
                                             bg-gray-100 dark:bg-gray-800 rounded px-2 py-0.5">
                                    {{ $line->line_type === 'prepaid' ? __('messages.Prepaid') : __('messages.Postpaid') }}
                                </span>
                            </div>

                            <div class="border-t border-gray-200 dark:border-gray-600 mb-3 opacity-60"></div>

                            {{-- Dates --}}
                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-0.5">
                                        {{ __('messages.last_invoice_date') }}
                                    </p>
                                    <p class="font-mono text-xs text-gray-700 dark:text-gray-300">
                                        {{ $line->last_invoice_date ?? '—' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-0.5">
                                        {{ __('messages.payment_date') }}
                                    </p>
                                    <p class="font-mono text-xs text-gray-700 dark:text-gray-300">
                                        {{ $line->payment_date ? \Carbon\Carbon::parse($line->payment_date)->format('Y-m-d H:i') : '—' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Action --}}
                            <div class="flex justify-end">
                                <a href="{{ route('lines.show', $line) }}"
                                   class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-blue-600 hover:bg-blue-700
                                          text-white text-xs font-semibold rounded-lg transition-all duration-150 hover:scale-105">
                                    {{ __('messages.View') }}
                                    <svg class="w-3 h-3" viewBox="0 0 16 16" fill="none">
                                        <path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>