<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-gray-900 dark:text-white leading-tight">
            {{ __('messages.edit_user') ?? 'Edit User' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-[2.5rem] border border-gray-100 dark:border-gray-700 overflow-hidden p-10">
                <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-black text-gray-700 dark:text-gray-300 mb-2 px-1">{{ __('messages.name') }}</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                               class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all font-bold text-gray-800 dark:text-white">
                        @error('name')<p class="mt-2 text-sm text-rose-600 font-bold px-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-black text-gray-700 dark:text-gray-300 mb-2 px-1">{{ __('messages.email') }}</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                               class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all font-bold text-gray-800 dark:text-white">
                        @error('email')<p class="mt-2 text-sm text-rose-600 font-bold px-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-black text-gray-700 dark:text-gray-300 mb-2 px-1">{{ __('messages.role') }}</label>
                        <select name="role_id" required 
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all font-bold text-gray-800 dark:text-white">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')<p class="mt-2 text-sm text-rose-600 font-bold px-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-black text-gray-700 dark:text-gray-300 mb-2 px-1">{{ __('messages.base_salary') }}</label>
                        <input type="number" step="0.01" name="base_salary" value="{{ old('base_salary', $user->base_salary) }}" required 
                               class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all font-bold text-gray-800 dark:text-white"
                               placeholder="0.00">
                        @error('base_salary')<p class="mt-2 text-sm text-rose-600 font-bold px-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                        <p class="text-xs font-black text-gray-400 dark:text-gray-500 mb-6 uppercase tracking-widest">{{ __('messages.change_password_optional') ?? 'Change Password (Optional)' }}</p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-black text-gray-700 dark:text-gray-300 mb-2 px-1">{{ __('messages.new_password') ?? 'New Password' }}</label>
                                <input type="password" name="password" 
                                       class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all font-bold text-gray-800 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-black text-gray-700 dark:text-gray-300 mb-2 px-1">{{ __('messages.confirm_new_password') ?? 'Confirm New Password' }}</label>
                                <input type="password" name="password_confirmation" 
                                       class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all font-bold text-gray-800 dark:text-white">
                            </div>
                        </div>
                        @error('password')<p class="mt-2 text-sm text-rose-600 font-bold px-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex gap-4 pt-6">
                        <button type="submit" class="flex-1 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black shadow-xl shadow-indigo-200 dark:shadow-none transition-all active:scale-95">
                            {{ __('messages.update') }}
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
