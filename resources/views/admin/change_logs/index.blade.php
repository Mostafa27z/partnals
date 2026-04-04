<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-extrabold text-gray-900 leading-tight" data-aos="fade-down">
                📝 {{ __('messages.Change Log') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8" dir="rtl">
        <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl border border-gray-100" data-aos="fade-up" data-aos-delay="100">
            @if ($logs->count())
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full text-sm text-gray-800 dark:text-gray-200">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 sticky top-0 z-10">
                            <tr class="text-right">
                                <th class="px-5 py-3 border-b dark:border-gray-700 font-semibold">{{ __('messages.Model') }}</th>
                                <th class="px-5 py-3 border-b dark:border-gray-700 font-semibold">{{ __('messages.Record ID') }}</th>
                                <th class="px-5 py-3 border-b dark:border-gray-700 font-semibold">{{ __('messages.Field') }}</th>
                                <th class="px-5 py-3 border-b dark:border-gray-700 font-semibold text-red-700">{{ __('messages.Old Value') }}</th>
                                <th class="px-5 py-3 border-b dark:border-gray-700 font-semibold text-green-700">{{ __('messages.New Value') }}</th>
                                <th class="px-5 py-3 border-b dark:border-gray-700 font-semibold">{{ __('messages.User') }}</th>
                                <th class="px-5 py-3 border-b dark:border-gray-700 font-semibold">{{ __('messages.Date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($logs as $log)
                                <tr class="hover:bg-blue-50 transition-all duration-200 transform hover:scale-[1.01]" 
                                    data-aos="fade-up" 
                                    data-aos-delay="{{ $loop->index * 50 }}">
                                    <td class="px-5 py-3">
                                        @switch(class_basename($log->model_type))
                                            @case('Customer') {{ __('messages.Customer') }} @break
                                            @case('Line') {{ __('messages.Line') }} @break
                                            @default {{ class_basename($log->model_type) }}
                                        @endswitch
                                    </td>
                                    <td class="px-5 py-3">
                                        @if (class_basename($log->model_type) === 'Customer' && $log->model)
                                            {{ $log->model->national_id ?? '---' }}
                                        @elseif (class_basename($log->model_type) === 'Line' && $log->model)
                                            {{ $log->model->phone_number ?? '---' }}
                                        @else
                                            {{ $log->model_id }}
                                        @endif
                                    </td>
                                    <td class="px-5 py-3">{{ $log->field_name }}</td>
                                    <td class="px-5 py-3 text-red-600 font-medium">{{ $log->old_value }}</td>
                                    <td class="px-5 py-3 text-green-600 font-medium">{{ $log->new_value }}</td>
                                    <td class="px-5 py-3">{{ $log->user?->name ?? __('messages.System') }}</td>
                                    <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6" data-aos="fade-up" data-aos-delay="200">
                    {{ $logs->links() }}
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-10 text-lg" data-aos="fade-in">
                    {{ __('messages.No changes found.') }}
                </p>
            @endif
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                AOS.init({
                    duration: 600,
                    once: true,
                    offset: 50
                });
            });
        </script>
    @endpush
</x-app-layout>
