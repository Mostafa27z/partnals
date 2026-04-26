<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-gray-900 dark:text-white leading-tight">
            {{ __('messages.add_user') ?? 'Add User' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-[2.5rem] border border-gray-100 dark:border-gray-700 overflow-hidden p-10">
                <form action="{{ route('users.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <div>
                        <label class="block text-sm font-black text-gray-700 dark:text-gray-300 mb-2 px-1">{{ __('messages.name') }}</label>
                        <input type="text" name="name" value="{{ old('name') }}" required 
                               class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all font-bold text-gray-800 dark:text-white"
                               placeholder="{{ __('messages.enter_user_name') ?? 'Enter full name' }}">
                        @error('name')<p class="mt-2 text-sm text-rose-600 font-bold px-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-black text-gray-700 dark:text-gray-300 mb-2 px-1">{{ __('messages.email') }}</label>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                               class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all font-bold text-gray-800 dark:text-white"
                               placeholder="email@example.com">
                        @error('email')<p class="mt-2 text-sm text-rose-600 font-bold px-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-black text-gray-700 dark:text-gray-300 mb-2 px-1">{{ __('messages.role') }}</label>
                        <select name="role_id" required 
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all font-bold text-gray-800 dark:text-white">
                            <option value="" disabled selected>{{ __('messages.select_role') ?? 'Select Case' }}</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')<p class="mt-2 text-sm text-rose-600 font-bold px-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-black text-gray-700 dark:text-gray-300 mb-2 px-1">{{ __('messages.base_salary') }}</label>
                        <input type="number" step="0.01" name="base_salary" value="{{ old('base_salary') }}" required 
                               class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all font-bold text-gray-800 dark:text-white"
                               placeholder="0.00">
                        @error('base_salary')<p class="mt-2 text-sm text-rose-600 font-bold px-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-black text-gray-700 dark:text-gray-300 mb-2 px-1">{{ __('messages.password') }}</label>
                            <input type="password" name="password" required 
                                   class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all font-bold text-gray-800 dark:text-white"
                                   placeholder="••••••••">
                        </div>
                        <div>
                            <label class="block text-sm font-black text-gray-700 dark:text-gray-300 mb-2 px-1">{{ __('messages.confirm_password') }}</label>
                            <input type="password" name="password_confirmation" required 
                                   class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all font-bold text-gray-800 dark:text-white"
                                   placeholder="••••••••">
                        </div>
                    </div>
                    @error('password')<p class="mt-2 text-sm text-rose-600 font-bold px-1">{{ $message }}</p>@enderror

                    <div class="flex gap-4 pt-6">
                        <button type="submit" class="flex-1 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black shadow-xl shadow-indigo-200 dark:shadow-none transition-all active:scale-95">
                            {{ __('messages.save') }}
                        </button>
                        <a href="{{ route('users.index') }}" class="flex-1 py-4 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-2xl font-black transition-all active:scale-95 text-center flex items-center justify-center">
                            {{ __('messages.cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
