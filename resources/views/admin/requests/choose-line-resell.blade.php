<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-2xl">🔁</span>
            <h2 class="text-xl font-black text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.choose_line_for_resell') ?? 'Choose Line for Resell' }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 px-4 max-w-7xl mx-auto">
        <!-- Table Card -->
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700/50 overflow-hidden">
            @if($lines->count())
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 dark:bg-gray-900/50">
                                <th class="px-6 py-5 text-{{ $direction == 'rtl' ? 'right' : 'left' }} text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700/50">{{ __('messages.phone_number') }}</th>
                                <th class="px-6 py-5 text-{{ $direction == 'rtl' ? 'right' : 'left' }} text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700/50">{{ __('messages.customer') }}</th>
                                <th class="px-6 py-5 text-{{ $direction == 'rtl' ? 'right' : 'left' }} text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700/50">{{ __('messages.provider') }}</th>
                                <th class="px-6 py-5 text-{{ $direction == 'rtl' ? 'right' : 'left' }} text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700/50">{{ __('messages.plan') }}</th>
                                <th class="px-6 py-5 text-center text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest border-b border-gray-100 dark:border-gray-700/50">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                            @foreach ($lines as $line)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/20 transition-colors">
                                    <td class="px-6 py-5">
                                        <span class="font-mono font-black text-gray-900 dark:text-white tracking-widest text-sm">{{ $line->phone_number }}</span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="font-black text-gray-800 dark:text-gray-200 text-sm">{{ $line->customer->full_name ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="px-3 py-1 bg-gray-50 dark:bg-gray-900 rounded-full text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest">
                                            {{ $line->provider }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="text-xs font-bold text-gray-600 dark:text-gray-400">{{ $line->plan->name ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <a href="{{ route('requests.resell.create', $line->id) }}"
                                           class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-6 py-2 rounded-xl shadow-lg shadow-indigo-500/20 transition-all active:scale-[0.98] inline-flex items-center gap-2 text-[10px] uppercase tracking-widest">
                                            <span>🔁</span>
                                            {{ __('messages.request_type_resell') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-8 py-6 border-t border-gray-50 dark:border-gray-700/50">
                    {{ $lines->links() }}
                </div>
            @else
                <div class="py-24 text-center">
                    <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-[2rem] flex items-center justify-center mx-auto mb-4 text-3xl">📭</div>
                    <p class="text-gray-400 dark:text-gray-500 font-black uppercase tracking-widest text-xs">{{ __('messages.no_lines_found') ?? 'No lines available' }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
