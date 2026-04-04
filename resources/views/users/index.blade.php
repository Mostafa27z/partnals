<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">👥 {{ __('messages.users') }}</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6">
        <a href="{{ route('users.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700">
            ➕ {{ __('messages.add_user') }}
        </a>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg mt-6 overflow-x-auto">
            <table class="min-w-full text-center">
                <thead class="bg-gray-100 dark:bg-gray-900">
                    <tr>
                        <th class="px-4 py-2">{{ __('messages.name') }}</th>
                        <th class="px-4 py-2">{{ __('messages.email') }}</th>
                        <th class="px-4 py-2">{{ __('messages.role') }}</th>
                        <th class="px-4 py-2">{{ __('messages.created_at') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="border-t dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-2">{{ $user->name }}</td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            <td class="px-4 py-2">{{ $user->role?->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $user->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $users->links() }}</div>
    </div>
</x-app-layout>
