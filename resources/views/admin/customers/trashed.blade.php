<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
            🗑️ {{ __('messages.Deleted Customers') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 shadow rounded p-4 overflow-x-auto">
            <table class="min-w-full table-auto text-sm text-center divide-y divide-gray-200">
                <thead class="bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-2">{{ __('messages.Full Name') }}</th>
                        <th class="px-4 py-2">{{ __('messages.National ID') }}</th>
                        <th class="px-4 py-2">{{ __('messages.Deleted At') }}</th>
                        <th class="px-4 py-2">{{ __('messages.Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100">
                    @forelse($customers as $customer)
                        <tr>
                            <td class="px-4 py-2">{{ $customer->full_name }}</td>
                            <td class="px-4 py-2">{{ $customer->national_id }}</td>
                            <td class="px-4 py-2">{{ $customer->deleted_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-2">
                                <div class="flex justify-center gap-3">
                                    <form action="{{ route('customers.restore', $customer->id) }}" method="POST">
                                        @csrf
                                        <button class="text-green-600 hover:underline" title="{{ __('messages.Restore') }}">
                                            ♻️ {{ __('messages.Restore') }}
                                        </button>
                                    </form>

                                    <form action="{{ route('customers.forceDelete', $customer->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline"
                                            onclick="return confirm('{{ __('messages.Confirm Permanent Deletion') }}')"
                                            title="{{ __('messages.Force Delete') }}">
                                            🗑️ {{ __('messages.Force Delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 text-gray-500 dark:text-gray-400">
                                {{ __('messages.No Deleted Customers') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
