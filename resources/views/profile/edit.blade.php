<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
            👤 {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Update Profile Information -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-gray-700 hover:shadow-lg transition">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-6 flex items-center gap-2">
                    🔑 {{ __('Update Password') }}
                </h3>
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account (Optional) -->
           
            <!-- <div class="bg-white dark:bg-gray-800 shadow-md rounded-2xl p-6 sm:p-8 border border-red-100 dark:border-red-900/50 hover:shadow-lg transition">
                <h3 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-4 flex items-center gap-2">
                    ⚠️ {{ __('Delete Account') }}
                </h3>
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div> -->
            
        </div>
    </div>
</x-app-layout>