<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-900">
            {{ __('messages.Edit Plan') }}
        </h2>
    </x-slot>

    <div class="py-10 px-4 max-w-4xl mx-auto" dir="rtl">
        <form method="POST" action="{{ route('plans.update', $plan->id) }}"
              class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 space-y-6">
            @csrf
            @method('PUT')

            <!-- Plan Name -->
            <div>
                <label class="block text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('messages.Plan Name') }}
                </label>
                <input type="text" name="name" value="{{ old('name', $plan->name) }}"
                       class="w-full text-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                       required>
            </div>

            <!-- Price -->
            <div>
                <label class="block text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('messages.Price') }} ({{ __('messages.EGP') }})
                </label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $plan->price) }}"
                       class="w-full text-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                       required>
            </div>

            <!-- Provider -->
            <div>
                <label class="block text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('messages.Provider') }}
                </label>
                <select name="provider"
                        class="w-full text-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    @foreach(['Vodafone', 'Etisalat', 'Orange', 'WE'] as $code)
                        <option value="{{ $code }}" {{ old('provider', $plan->provider) === $code ? 'selected' : '' }}>
                            {{ $code }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Provider Price -->
            <div>
                <label class="block text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('messages.Provider Price') }} ({{ __('messages.EGP') }})
                </label>
                <input type="number" step="0.01" name="provider_price" value="{{ old('provider_price', $plan->provider_price) }}"
                       class="w-full text-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            </div>

            <!-- Type -->
            <div>
                <label class="block text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('messages.Type') }}
                </label>
                <input type="text" name="type" value="{{ old('type', $plan->type) }}"
                       class="w-full text-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            </div>

            <!-- Plan Code -->
            <div>
                <label class="block text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('messages.Plan Code') }}
                </label>
                <input type="text" name="plan_code" value="{{ old('plan_code', $plan->plan_code) }}"
                       class="w-full text-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            </div>

            <!-- Description / Penalty -->
            <div>
                <label class="block text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('messages.Description / Penalty') }}
                </label>
                <input type="text" name="penalty" value="{{ old('penalty', $plan->penalty) }}"
                       class="w-full text-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            </div>

            <!-- Submit Button -->
            <div class="text-left">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-lg font-semibold px-8 py-3 rounded-full shadow-md transform hover:scale-105 transition">
                    {{ __('messages.Update') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
