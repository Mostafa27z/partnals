<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="text-3xl  font-bold dark:text-white text-black">
                📋 {{ __('messages.Customers List') }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('customers.trashed') }}" 
                   class="bg-red-600 hover:bg-red-700 text-white text-lg font-semibold px-5 py-3 rounded-full shadow-md transition transform hover:scale-105">
                    🗑️ {{ __('messages.Deleted Customers') }}
                </a>
                <a href="{{ route('customers.create') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white text-lg font-semibold px-5 py-3 rounded-full shadow-md transition transform hover:scale-105">
                    + {{ __('messages.New Customer') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Filter Form -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                <form method="GET" action="{{ route('customers.index') }}" class="flex flex-wrap gap-4 items-center">
                    <input type="text" name="phone_number" value="{{ request('phone_number') }}" 
                           placeholder="{{ __('messages.Phone Number') }}" 
                           class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2 w-full sm:w-48 focus:ring-2 focus:ring-blue-500" />
                    
                    <input type="text" name="national_id" value="{{ request('national_id') }}" 
                           placeholder="{{ __('messages.National ID') }}" 
                           class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-4 py-2 w-full sm:w-48 focus:ring-2 focus:ring-blue-500" />

                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow transition">
                        {{ __('messages.Search') }}
                    </button>
                    <a href="{{ route('customers.export') }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow transition">
                        {{ __('messages.Export to Excel') }}
                    </a>
                </form>
            </div>

            <!-- Customers Table -->
            <div class="bg-white dark:bg-gray-800 overflow-x-auto rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 text-center text-lg" dir="rtl">
                    <thead class="bg-gray-100 dark:bg-gray-900 text-black dark:text-white font-semibold">
                        <tr>
                            <th class="px-6 py-4">{{ __('messages.Full Name') }}</th>
                            <th class="px-6 py-4">{{ __('messages.National ID') }}</th>
                            <th class="px-6 py-4">{{ __('messages.Invoices') }}</th>
                            <th class="px-6 py-4" colspan="3">{{ __('messages.Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($customers as $customer)
                            <tr class="hover:bg-gray-50 dark:bg-gray-700/50 transition">
                                <td class="px-6 py-4 font-medium text-gray-800 dark:text-gray-200">{{ $customer->full_name }}</td>
                                <td class="px-6 py-4">{{ $customer->national_id }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('customers.invoices', $customer) }}" 
                                       class="text-green-600 hover:text-green-800 font-medium">
                                        {{ __('messages.View Invoices') }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('customers.show', $customer) }}" 
                                       class="text-blue-600 hover:text-blue-800 font-medium">
                                        {{ __('messages.View') }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('customers.edit', $customer) }}" 
                                       class="text-yellow-500 hover:text-yellow-600 font-medium">
                                        {{ __('messages.Edit') }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('{{ __('messages.Are you sure?') }}')" 
                                                class="text-red-600 hover:text-red-800 font-medium">
                                            {{ __('messages.Delete') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-6 px-6 py-4">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
