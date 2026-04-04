<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-3xl font-bold text-gray-900 leading-tight">
                🗑️ {{ __('messages.Deleted Plans') }}
            </h2>
            {{-- <a href="{{ route('plans.index') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white text-lg font-semibold px-5 py-3 rounded-full shadow-md transform hover:scale-105 transition">
                ⬅️ {{ __('messages.Back to Plans') }}
            </a> --}}
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-800 rounded shadow text-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($plans->count() > 0)
            <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow-lg rounded-2xl border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 text-center text-lg">
                    <thead class="bg-gray-100 dark:bg-gray-900">
                        <tr class="text-gray-900">
                            <th class="px-6 py-4">{{ __('messages.Name') }}</th>
                            <th class="px-6 py-4">{{ __('messages.Price') }}</th>
                            <th class="px-6 py-4">{{ __('messages.Provider') }}</th>
                            <th class="px-6 py-4">{{ __('messages.Plan Code') }}</th>
                            <th class="px-6 py-4">{{ __('messages.Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($plans as $plan)
                            <tr class="hover:bg-gray-50 dark:bg-gray-700/50 transition">
                                <td class="px-6 py-4 font-medium text-gray-800 dark:text-gray-200">{{ $plan->name }}</td>
                                <td class="px-6 py-4">{{ $plan->price }}</td>
                                <td class="px-6 py-4">{{ $plan->provider ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $plan->plan_code ?? '-' }}</td>
                                <td class="px-6 py-4 space-x-4">
                                    <form action="{{ route('plans.restore', $plan->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit"
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-full shadow transition">
                                            🔄 {{ __('messages.Restore') }}
                                        </button>
                                    </form>

                                    <form action="{{ route('plans.force-delete', $plan->id) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('{{ __('messages.Confirm Permanent Delete') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-full shadow transition">
                                            🗑️ {{ __('messages.Delete Permanently') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $plans->links() }}
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 p-8 rounded shadow-lg text-center text-gray-500 dark:text-gray-400 text-lg">
                {{ __('messages.No Deleted Plans') }}
            </div>
        @endif
    </div>
</x-app-layout>
