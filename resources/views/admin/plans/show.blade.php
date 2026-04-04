<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-900">
            {{ __('messages.Plan Details') }}
        </h2>
    </x-slot>

    <div class="py-10 px-6 md:px-10 max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-2xl border border-gray-200 dark:border-gray-700" dir="rtl">
        <div class="space-y-4 text-right text-lg text-gray-900 leading-relaxed">
            <p><strong class="font-semibold">{{ __('messages.Name') }}:</strong> {{ $plan->name }}</p>
            <p><strong class="font-semibold">{{ __('messages.Price') }}:</strong> {{ $plan->price }} {{ __('messages.EGP') }}</p>
            <p><strong class="font-semibold">{{ __('messages.Provider') }}:</strong> {{ $plan->provider }}</p>
            <p><strong class="font-semibold">{{ __('messages.Provider Price') }}:</strong> {{ $plan->provider_price }} {{ __('messages.EGP') }}</p>
            <p><strong class="font-semibold">{{ __('messages.Type') }}:</strong> {{ $plan->type }}</p>
            <p><strong class="font-semibold">{{ __('messages.Plan Code') }}:</strong> {{ $plan->plan_code }}</p>
            <p><strong class="font-semibold">{{ __('messages.Description') }}:</strong> {{ $plan->penalty }}</p>
        </div>

        <div class="mt-8 flex justify-end">
            <a href="{{ route('plans.edit', $plan->id) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white text-lg font-semibold px-6 py-3 rounded-full shadow-md transform hover:scale-105 transition">
                {{ __('messages.Edit') }}
            </a>
        </div>
    </div>
</x-app-layout>
