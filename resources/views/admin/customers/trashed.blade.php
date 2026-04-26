<x-app-layout>
    <div class="bg-gradient-to-b from-gray-50 via-white to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen py-12">
        
        <!-- Hero Section -->
        <div class="max-w-6xl mx-auto px-6 text-center mb-10">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">
                🗑️ {{ __('messages.Deleted Customers') }}
            </h1>
            <div class="mt-6 text-center">
                <a href="{{ route('customers.index') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full shadow-lg transform hover:scale-105 transition inline-block">
                    ⬅️ {{ __('messages.Back to Customers') }}
                </a>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-6">

            @if(session('success'))
                <div class="mb-8 p-4 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-2xl shadow-sm border border-green-200 dark:border-green-800/50 flex items-center gap-3">
                    <span class="text-xl">✅</span>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            @if($customers->count() > 0)
                <div class="overflow-x-auto bg-white dark:bg-gray-800/95 dark:bg-gray-800/90 backdrop-blur-sm shadow-lg rounded-2xl border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full text-right">
                        <thead class="bg-gray-100 dark:bg-gray-900/80 dark:bg-gray-700/80 text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-4 border-b dark:border-gray-700 text-sm font-semibold">{{ __('messages.Full Name') }}</th>
                                <th class="px-6 py-4 border-b dark:border-gray-700 text-sm font-semibold">{{ __('messages.National ID') }}</th>
                                <th class="px-6 py-4 border-b dark:border-gray-700 text-sm font-semibold">{{ __('messages.Deleted At') }}</th>
                                <th class="px-6 py-4 border-b dark:border-gray-700 text-sm font-semibold text-center">{{ __('messages.Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800 dark:text-gray-200 divide-y divide-gray-100 dark:divide-gray-700/50 text-center">
                            @foreach($customers as $customer)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-6 py-4 font-bold text-start">{{ $customer->full_name }}</td>
                                    <td class="px-6 py-4 font-mono text-sm">{{ $customer->national_id }}</td>
                                    <td class="px-6 py-4 text-xs">{{ $customer->deleted_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-6 py-4 flex gap-3 justify-center">
                                        <form action="{{ route('customers.restore', $customer->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-full shadow-md hover:scale-105 transition text-sm font-bold flex items-center gap-2">
                                                <span>🔄</span> {{ __('messages.Restore') }}
                                            </button>
                                        </form>

                                        <form action="{{ route('customers.forceDelete', $customer->id) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('{{ __('messages.Confirm Permanent Deletion') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-full shadow-md hover:scale-105 transition text-sm font-bold flex items-center gap-2">
                                                <span>🗑️</span> {{ __('messages.Force Delete') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-8">
                    {{ $customers->links() }}
                </div>
            @else
                <div class="bg-white dark:bg-gray-800/90 backdrop-blur-sm p-12 rounded-2xl shadow-lg text-center border border-gray-200 dark:border-gray-700">
                    <div class="w-20 h-20 bg-gray-50 dark:bg-gray-900/50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-4xl text-gray-300 dark:text-gray-600">👤</span>
                    </div>
                    <p class="text-xl font-bold text-gray-500 dark:text-gray-400">
                        {{ __('messages.No Deleted Customers') }}
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
