<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 flex items-center gap-3" dir="rtl">
            <span class="w-10 h-10 bg-gradient-to-tr from-rose-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-rose-200 dark:shadow-none">
                <span class="text-white text-lg">🗑️</span>
            </span>
            {{ __('messages.deleted_lines') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-4 lg:px-8" dir="rtl">
        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/30 text-emerald-700 dark:text-emerald-300 rounded-2xl shadow-sm flex items-center gap-3 font-bold">
                <span class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg flex items-center justify-center text-lg shrink-0">✅</span>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
            <table class="min-w-full text-center divide-y divide-gray-100 dark:divide-gray-700/50">
                <thead>
                    <tr class="bg-gray-50/80 dark:bg-gray-900/50">
                        <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.phone_number') }}</th>
                        <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.customer') }}</th>
                        <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.national_id') }}</th>
                        <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.provider') }}</th>
                        <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.deleted_at') }}</th>
                        <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse($lines as $line)
                        <tr class="hover:bg-rose-50/30 dark:hover:bg-rose-900/10 transition-colors duration-200">
                            <td class="px-4 py-3.5 font-mono font-bold text-gray-800 dark:text-gray-200 whitespace-nowrap">{{ $line->phone_number }}</td>
                            <td class="px-4 py-3.5 text-gray-600 dark:text-gray-400 whitespace-nowrap">{{ $line->customer->full_name ?? '-' }}</td>
                            <td class="px-4 py-3.5 text-gray-600 dark:text-gray-400 whitespace-nowrap">{{ $line->customer->national_id ?? '-' }}</td>
                            <td class="px-4 py-3.5 text-gray-600 dark:text-gray-400 whitespace-nowrap">{{ $line->provider }}</td>
                            <td class="px-4 py-3.5 text-gray-500 dark:text-gray-400 whitespace-nowrap text-sm">{{ $line->deleted_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <div class="flex justify-center gap-2">
                                    <!-- Restore Button -->
                                    <form action="{{ route('lines.restore', $line->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 font-bold text-xs hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition-all border border-emerald-200 dark:border-emerald-800/30"
                                            onclick="return confirm('{{ __('messages.restore_confirm') }}')">
                                            ♻️ {{ __('messages.restore') }}
                                        </button>
                                    </form>

                                    <!-- Force Delete Button -->
                                    <form action="{{ route('lines.forceDelete', $line->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-rose-50 dark:bg-rose-900/20 text-rose-700 dark:text-rose-400 font-bold text-xs hover:bg-rose-100 dark:hover:bg-rose-900/40 transition-all border border-rose-200 dark:border-rose-800/30"
                                            onclick="return confirm('{{ __('messages.force_delete_confirm') }}')">
                                            🗑️ {{ __('messages.force_delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900/50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                    <span class="text-3xl">📭</span>
                                </div>
                                <p class="text-gray-400 dark:text-gray-500 font-bold">{{ __('messages.no_deleted_lines') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="p-5 border-t border-gray-100 dark:border-gray-700">
                {{ $lines->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
