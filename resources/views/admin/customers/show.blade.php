<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.Customer Details') }}
        </h2>
    </x-slot>

    <div class="py-8" dir="rtl">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Customer Info Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 border border-gray-100 space-y-6">
                <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 border-b dark:border-gray-700 pb-3">
                    {{ __('messages.Customer Details') }}
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-base text-gray-800 dark:text-gray-200">
                    <p><span class="font-medium">{{ __('ID') }}:</span> {{ $customer->id }}</p>
                    <p><span class="font-medium">{{ __('messages.Full Name') }}:</span> {{ $customer->full_name }}</p>
                    <p><span class="font-medium">{{ __('messages.National ID') }}:</span> {{ $customer->national_id }}</p>
                    <p><span class="font-medium">{{ __('messages.Birth Date') }}:</span> {{ $customer->birth_date ?? '-' }}</p>
                    <p><span class="font-medium">{{ __('messages.Email') }}:</span> {{ $customer->email ?? '-' }}</p>
                    <p><span class="font-medium">{{ __('messages.Address') }}:</span> {{ $customer->address ?? '-' }}</p>
                    <p><span class="font-medium">{{ __('messages.Created At') }}:</span> {{ $customer->created_at->format('Y-m-d H:i') }}</p>
                    <p><span class="font-medium">{{ __('messages.Updated At') }}:</span> {{ $customer->updated_at->format('Y-m-d H:i') }}</p>
                    <p class="sm:col-span-2">
                        <span class="font-medium">{{ __('messages.Deleted At') }}:</span> 
                        {{ $customer->deleted_at ? $customer->deleted_at->format('Y-m-d H:i') : __('messages.Not Deleted') }}
                    </p>
                </div>
            </div>

            <!-- Linked Lines -->
            @if($customer->lines->count())
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 border border-gray-100 mt-8">
                    <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 border-b dark:border-gray-700 pb-3 mb-4">
                        {{ __('messages.Linked Lines') }}
                    </h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-base border border-gray-200 dark:border-gray-700">
                            <thead class="bg-gray-100 dark:bg-gray-900">
                                <tr class="text-gray-700 dark:text-gray-300 text-center">
                                    <th class="px-4 py-2 border">{{ __('messages.Phone Number') }}</th>
                                    <th class="px-4 py-2 border">{{ __('messages.Provider') }}</th>
                                    <th class="px-4 py-2 border">{{ __('messages.Line Type') }}</th>
                                    <th class="px-4 py-2 border">{{ __('messages.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customer->lines as $line)
                                    <tr class="text-center hover:bg-gray-50 dark:bg-gray-700/50">
                                        <td class="px-4 py-2 border font-medium">{{ $line->phone_number }}</td>
                                        <td class="px-4 py-2 border">{{ $line->provider }}</td>
                                        <td class="px-4 py-2 border">
                                            {{ $line->line_type === 'prepaid' ? __('messages.Prepaid') : __('messages.Postpaid') }}
                                        </td>
                                        <td class="px-4 py-2 border">
                                            <a href="{{ route('lines.show', $line) }}" 
                                               class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                                {{ __('messages.View') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
