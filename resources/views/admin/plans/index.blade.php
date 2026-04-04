<x-app-layout>
    <div class="bg-gradient-to-b from-gray-50 via-white to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen py-12">

        <!-- Hero Section -->
        <div class="max-w-6xl mx-auto px-6 text-center mb-10">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">
                {{ __('messages.Manage Plans') }}
            </h1>
            <div class="mt-6 flex flex-wrap justify-center gap-4">
                <a href="{{ route('plans.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full shadow-lg transform hover:scale-105 transition">
                    + {{ __('messages.Add Plan') }}
                </a>
                <a href="{{ route('plans.trashed') }}"
                   class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-full shadow-lg transform hover:scale-105 transition">
                    🗑️ {{ __('messages.Deleted Plans') }}
                </a>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-6">

            <!-- Filter Card -->
            <form method="GET" 
                  class="bg-white dark:bg-gray-800/90 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 mb-8 border border-gray-200 dark:border-gray-700">
                <div class="flex flex-wrap gap-4 justify-between items-center">
                    <div class="flex flex-wrap gap-3 items-center">
                        <input name="search" placeholder="{{ __('messages.Search') }}..."
                               value="{{ request('search') }}"
                               class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 transition w-48">
                        <select name="provider"
                                class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">{{ __('messages.Provider') }}</option>
                            @foreach(\App\Models\Plan::select('provider')->distinct()->pluck('provider') as $provider)
                                <option value="{{ $provider }}" {{ request('provider') == $provider ? 'selected' : '' }}>
                                    {{ $provider }}
                                </option>
                            @endforeach
                        </select>
                        <input type="number" step="0.01" name="min_price"
                               placeholder="Min Price" value="{{ request('min_price') }}"
                               class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2 rounded-lg w-28 focus:ring-2 focus:ring-blue-500 transition">
                        <input type="number" step="0.01" name="max_price"
                               placeholder="Max Price" value="{{ request('max_price') }}"
                               class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2 rounded-lg w-28 focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-full shadow-md hover:scale-105 transition">
                            {{ __('messages.Search') }}
                        </button>
                        <a href="{{ route('plans.export') }}"
                           class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-full shadow-md hover:scale-105 transition">
                            📤 {{ __('messages.Export to Excel') }}
                        </a>
                    </div>
                </div>
            </form>

            <!-- Table -->
            <div class="overflow-x-auto bg-white dark:bg-gray-800/95 dark:bg-gray-800/90 backdrop-blur-sm shadow-lg rounded-2xl border border-gray-200 dark:border-gray-700">
                <table class="min-w-full text-right">
                    <thead class="bg-gray-100 dark:bg-gray-900/80 dark:bg-gray-700/80 text-gray-700 dark:text-gray-300 dark:text-gray-200">
                        <tr>
                            @foreach(['Name','Price','Provider','Provider Price','Type','Plan Code','Description','Actions'] as $header)
                                <th class="px-6 py-4 border-b dark:border-gray-700 text-sm font-semibold">
                                    {{ __("messages.$header") }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-200">
                        @forelse($plans as $plan)
                            <tr class="hover:bg-gray-50 dark:bg-gray-700/50 dark:hover:bg-gray-700 transition">
                                <td class="px-6 py-3 border-b dark:border-gray-700">{{ $plan->name }}</td>
                                <td class="px-6 py-3 border-b dark:border-gray-700">{{ $plan->price }}</td>
                                <td class="px-6 py-3 border-b dark:border-gray-700">{{ $plan->provider }}</td>
                                <td class="px-6 py-3 border-b dark:border-gray-700">{{ $plan->provider_price }}</td>
                                <td class="px-6 py-3 border-b dark:border-gray-700">{{ $plan->type }}</td>
                                <td class="px-6 py-3 border-b dark:border-gray-700">{{ $plan->plan_code }}</td>
                                <td class="px-6 py-3 border-b dark:border-gray-700">{{ $plan->penalty }}</td>
                                <td class="px-6 py-3 border-b dark:border-gray-700 flex gap-4 justify-center">
                                    <a href="{{ route('plans.show', $plan->id) }}" class="text-green-600 hover:underline">{{ __('messages.View') }}</a>
                                    <a href="{{ route('plans.edit', $plan->id) }}" class="text-blue-600 hover:underline">{{ __('messages.Edit') }}</a>
                                    <form method="POST" action="{{ route('plans.destroy', $plan->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('{{ __('messages.Are you sure?') }}')" class="text-red-600 hover:underline">
                                            {{ __('messages.Delete') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-gray-500 dark:text-gray-400 py-6">
                                    {{ __('messages.No records found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $plans->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
