<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2" dir="rtl">
             {{ __('messages.deleted_lines') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-4 lg:px-8" dir="rtl">
        @if (session('success'))
            <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-lg shadow">
                 {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden">
            <table class="min-w-full text-center divide-y divide-gray-200">
                <thead class="bg-gray-100 dark:bg-gray-900">
                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('messages.phone_number') }}</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('messages.customer') }}</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('messages.national_id') }}</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('messages.provider') }}</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('messages.deleted_at') }}</th>
                        <th class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white dark:bg-gray-800">
                    @forelse($lines as $line)
                        <tr class="hover:bg-gray-50 dark:bg-gray-700/50 transition-colors">
                            <td class="px-4 py-3 font-mono text-gray-900 whitespace-nowrap">{{ $line->phone_number }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $line->customer->full_name ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $line->customer->national_id ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $line->provider }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $line->deleted_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap flex justify-center gap-3 flex-wrap">
                                <!-- Restore Button -->
                                <form action="{{ route('lines.restore', $line->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="bg-green-100 hover:bg-green-200 text-green-700 px-4 py-2 rounded-lg shadow-sm transition font-medium"
                                        onclick="return confirm('{{ __('messages.restore_confirm') }}')">
                                        ♻️ {{ __('messages.restore') }}
                                    </button>
                                </form>

                                <!-- Force Delete Button -->
                                <form action="{{ route('lines.forceDelete', $line->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg shadow-sm transition font-medium"
                                        onclick="return confirm('{{ __('messages.force_delete_confirm') }}')">
                                        🗑️ {{ __('messages.force_delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-gray-500 dark:text-gray-400 py-8 text-center text-lg">
                                {{ __('messages.no_deleted_lines') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="p-6 border-t bg-gray-50 dark:bg-gray-700/50">
                {{ $lines->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
