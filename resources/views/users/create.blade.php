<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">➕ {{ __('messages.add_new_user') }}</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium">{{ __('messages.name') }}</label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-medium">{{ __('messages.email') }}</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                           class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-medium">{{ __('messages.password') }}</label>
                    <input type="password" name="password" 
                           class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-medium">{{ __('messages.confirm_password') }}</label>
                    <input type="password" name="password_confirmation" 
                           class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-medium">{{ __('messages.role') }}</label>
                    <select name="role_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm" required>
                        <option value="">{{ __('messages.select_role') }}</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" 
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700">
                    💾 {{ __('messages.save') }}
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
