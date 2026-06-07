<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
            {{ __('messages.add_line') }}
        </h2>
    </x-slot>

    <div class="py-8" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                        {{ $customer->full_name }}
                    </h3>

                    <p class="text-sm text-gray-500">
                        {{ __('messages.select_line_to_attach') }}
                    </p>
                </div>

                <form method="POST"
                      action="{{ route('customers.storeline', $customer) }}">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('messages.Line') }}
                        </label>

                        <select
                            id="line_id"
                            name="line_id"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">
                                {{ __('messages.Select Line') }}
                            </option>

                            @foreach($lines as $line)
                                <option value="{{ $line->id }}">
                                    {{ $line->phone_number }}
                                    - {{ $line->provider }}
                                </option>
                            @endforeach
                        </select>

                        @error('line_id')
                            <div class="text-red-500 text-sm mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('customers.show', $customer) }}"
                           class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700">
                            {{ __('messages.cancel') }}
                        </a>

                        <button type="submit"
                                class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                            {{ __('messages.save') }}
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
    @push('scripts')
<script>
$(document).ready(function () {
    $('#line_id').select2({
        width: '100%',
        placeholder: "{{ __('messages.Select Line') }}"
    });
});
</script>
@endpush
</x-app-layout>