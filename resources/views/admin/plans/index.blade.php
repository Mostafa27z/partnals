<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ __('messages.Manage Plans') }}
            </h2>
            <a href="{{ route('plans.trashed') }}"
               class="flex items-center gap-1 bg-red-600 text-white px-4 py-2 rounded-lg shadow hover:bg-red-700 transition">
                🗑️ {{ __('messages.Deleted Plans') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6 px-6" dir="rtl">

        <!-- Filter Form -->
        <form method="GET"
              class="bg-white shadow rounded-xl p-4 mb-6 flex flex-col lg:flex-row items-center justify-between gap-4 border border-gray-200">
            
            <!-- Search & Filters -->
            <div class="flex flex-wrap gap-3 items-center w-full lg:w-auto">
                <input name="search" placeholder="{{ __('messages.Search') }}..."
                       value="{{ request('search') }}"
                       class="border border-gray-300 px-3 py-2 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-48">

                <select name="provider"
                        class="border border-gray-300 px-3 py-2 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">{{ __('messages.Provider') }}</option>
                    @foreach(\App\Models\Plan::select('provider')->distinct()->pluck('provider') as $provider)
                        <option value="{{ $provider }}" {{ request('provider') == $provider ? 'selected' : '' }}>
                            {{ $provider }}
                        </option>
                    @endforeach
                </select>

                {{-- <select name="type"
                        class="border border-gray-300 px-3 py-2 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                    <option value="" selected>{{ __('messages.Type') }}</option>
                    @foreach(\App\Models\Plan::select('type')->distinct()->pluck('type') as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select> --}}

                <input type="number" step="0.01" name="min_price"
                       placeholder="Min Price" value="{{ request('min_price') }}"
                       class="border border-gray-300 px-3 py-2 rounded-lg shadow-sm w-28 focus:ring-2 focus:ring-blue-500">

                <input type="number" step="0.01" name="max_price"
                       placeholder="Max Price" value="{{ request('max_price') }}"
                       class="border border-gray-300 px-3 py-2 rounded-lg shadow-sm w-28 focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Buttons -->
            <div class="flex flex-wrap gap-2">
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                    {{ __('messages.Search') }}
                </button>
                <a href="{{ route('plans.index') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition">
                    🔄 Reset
                </a>
                <a href="{{ route('plans.export') }}"
                   class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 transition">
                    📤 {{ __('messages.Export to Excel') }}
                </a>
                <a href="{{ route('plans.create') }}"
                   class="bg-purple-600 text-white px-5 py-2 rounded-lg shadow hover:bg-purple-700 transition">
                    + {{ __('messages.Add Plan') }}
                </a>
            </div>
        </form>

        <!-- Table -->
        <div class="overflow-x-auto bg-white shadow rounded-xl border border-gray-200">
            <table class="min-w-full text-right">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 border">{{ __('messages.Name') }}</th>
                        <th class="px-4 py-3 border">{{ __('messages.Price') }}</th>
                        <th class="px-4 py-3 border">{{ __('messages.Provider') }}</th>
                        <th class="px-4 py-3 border">{{ __('messages.Provider Price') }}</th>
                        <th class="px-4 py-3 border">{{ __('messages.Type') }}</th>
                        <th class="px-4 py-3 border">{{ __('messages.Plan Code') }}</th>
                        <th class="px-4 py-3 border">{{ __('messages.Description') }}</th>
                        <th class="px-4 py-3 border">{{ __('messages.Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800">
                    @forelse($plans as $plan)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-2 border">{{ $plan->name }}</td>
                            <td class="px-4 py-2 border">{{ $plan->price }}</td>
                            <td class="px-4 py-2 border">{{ $plan->provider }}</td>
                            <td class="px-4 py-2 border">{{ $plan->provider_price }}</td>
                            <td class="px-4 py-2 border">{{ $plan->type }}</td>
                            <td class="px-4 py-2 border">{{ $plan->plan_code }}</td>
                            <td class="px-4 py-2 border">{{ $plan->penalty }}</td>
                            <td class="px-4 py-2 border flex gap-3 justify-center flex-wrap">
                                <a href="{{ route('plans.show', $plan->id) }}"
                                   class="text-green-600 hover:underline">{{ __('messages.View') }}</a>
                                <a href="{{ route('plans.edit', $plan->id) }}"
                                   class="text-blue-600 hover:underline">{{ __('messages.Edit') }}</a>
                                <form method="POST" action="{{ route('plans.destroy', $plan->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('{{ __('messages.Are you sure?') }}')"
                                            class="text-red-600 hover:underline">
                                        {{ __('messages.Delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500 py-4">
                                {{ __('messages.No records found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $plans->appends(request()->query())->links() }}
        </div>
    </div>
</x-app-layout>
