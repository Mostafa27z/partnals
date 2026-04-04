<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2" dir="rtl">
            📜 {{ __('messages.requests_history') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8" dir="rtl">
        <!-- Filter Form -->
        <form method="GET" action="{{ route('requests.history') }}"
              class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            
            <div>
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('messages.phone_number') }}</label>
                <input type="text" name="phone" value="{{ request('phone') }}"
                       placeholder="{{ __('messages.phone_number') }}"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring focus:ring-blue-200" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('messages.national_id') }}</label>
                <input type="text" name="nid" value="{{ request('nid') }}"
                       placeholder="{{ __('messages.national_id') }}"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring focus:ring-blue-200" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('messages.provider') }}</label>
                <input type="text" name="provider" value="{{ request('provider') }}"
                       placeholder="{{ __('messages.provider') }}"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring focus:ring-blue-200" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('messages.type') }}</label>
                <select name="type" 
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">{{ __('messages.select_type') }}</option>
                    @foreach(['resell', 'change_plan', 'change_chip', 'pause', 'resume', 'change_date', 'change_distributor', 'stop'] as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                            {{ __('messages.request_type_' . $type) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('messages.from_date') }}</label>
                <input type="date" name="from" value="{{ request('from') }}"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring focus:ring-blue-200" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('messages.to_date') }}</label>
                <input type="date" name="to" value="{{ request('to') }}"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring focus:ring-blue-200" />
            </div>

            <div class="sm:col-span-2 lg:col-span-4 flex justify-end">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                    🔍 {{ __('messages.search') }}
                </button>
            </div>
        </form>

        <!-- Requests Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto"> <!-- 📌 مهم علشان responsiveness -->
                <table class="min-w-full divide-y divide-gray-200 text-center whitespace-nowrap">
                    <thead class="bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 text-sm font-semibold">
                        <tr>
                            <th class="px-4 py-3">{{ __('messages.phone_number') }}</th>
                            <th class="px-4 py-3">{{ __('messages.name') }}</th>
                            <th class="px-4 py-3">{{ __('messages.national_id') }}</th>
                            <th class="px-4 py-3">{{ __('messages.request_type') }}</th>
                            <th class="px-4 py-3">{{ __('messages.request_created') }}</th>
                            <th class="px-4 py-3">{{ __('messages.request_done') }}</th>
                            <th class="px-4 py-3">{{ __('messages.date') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse ($requests as $request)
                            <tr class="hover:bg-gray-50 dark:bg-gray-700/50 transition">
                                <td class="px-4 py-3">{{ $request->line->phone_number }}</td>
                                <td class="px-4 py-3">{{ $request->line->customer?->full_name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $request->line->customer?->national_id ?? '-' }}</td>
                                <td class="px-4 py-3">{{ __('messages.request_type_' . $request->request_type) }}</td>
                                <td class="px-4 py-3">{{ $request->requestedBy?->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $request->doneBy?->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $request->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 text-gray-500 dark:text-gray-400">{{ __('messages.no_completed_requests') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 px-4 pb-4">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
