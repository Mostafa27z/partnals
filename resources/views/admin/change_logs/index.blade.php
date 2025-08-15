<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800 leading-tight">
                📝 {{ __('messages.Change Log') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8" dir="rtl">
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
            @if ($logs->count())
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full text-sm text-gray-800">
                        <thead class="bg-gray-50 text-gray-700 sticky top-0 z-10">
                            <tr class="text-right">
                                <th class="px-4 py-3 border-b font-semibold">{{ __('messages.Model') }}</th>
                                <th class="px-4 py-3 border-b font-semibold">{{ __('messages.Record ID') }}</th>
                                <th class="px-4 py-3 border-b font-semibold">{{ __('messages.Field') }}</th>
                                <th class="px-4 py-3 border-b font-semibold text-red-700">{{ __('messages.Old Value') }}</th>
                                <th class="px-4 py-3 border-b font-semibold text-green-700">{{ __('messages.New Value') }}</th>
                                <th class="px-4 py-3 border-b font-semibold">{{ __('messages.User') }}</th>
                                <th class="px-4 py-3 border-b font-semibold">{{ __('messages.Date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($logs as $log)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-4 py-2">
                                        @switch(class_basename($log->model_type))
                                            @case('Customer') {{ __('messages.Customer') }} @break
                                            @case('Line') {{ __('messages.Line') }} @break
                                            @default {{ class_basename($log->model_type) }}
                                        @endswitch
                                    </td>

                                    <td class="px-4 py-2">
                                        @if (class_basename($log->model_type) === 'Customer' && $log->model)
                                            {{ $log->model->national_id ?? '---' }}
                                        @elseif (class_basename($log->model_type) === 'Line' && $log->model)
                                            {{ $log->model->phone_number ?? '---' }}
                                        @else
                                            {{ $log->model_id }}
                                        @endif
                                    </td>

                                    <td class="px-4 py-2">{{ $log->field_name }}</td>
                                    <td class="px-4 py-2 text-red-600 font-medium">{{ $log->old_value }}</td>
                                    <td class="px-4 py-2 text-green-600 font-medium">{{ $log->new_value }}</td>
                                    <td class="px-4 py-2">{{ $log->user?->name ?? __('messages.System') }}</td>
                                    <td class="px-4 py-2 text-gray-500">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            @else
                <p class="text-gray-600 text-center py-6">{{ __('messages.No changes found.') }}</p>
            @endif
        </div>
    </div>
</x-app-layout>
