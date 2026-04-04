<x-app-layout> 
    <x-slot name="header"> 
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
            📦 {{ __('messages.manage_lines_for_sale') }}
        </h2> 
    </x-slot> 

    <div class="max-w-6xl mx-auto mt-6 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg"> 
        @if (session('success')) 
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg shadow-sm text-sm sm:text-base"> 
                ✅ {{ session('success') }} 
            </div> 
        @endif 

        <form method="POST" action="{{ route('lines.mark-for-sale') }}"> 
            @csrf 

            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full text-center border-collapse text-sm sm:text-base"> 
                    <thead class="bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 uppercase text-xs sm:text-sm"> 
                        <tr> 
                            <th class="px-4 py-3 whitespace-nowrap">📞 {{ __('messages.phone_number') }}</th> 
                            <th class="px-4 py-3 whitespace-nowrap">👤 {{ __('messages.customer') }}</th> 
                            <th class="px-4 py-3 whitespace-nowrap">💰 {{ __('messages.sale_price') }}</th> 
                            <th class="px-4 py-3 whitespace-nowrap">📍 {{ __('messages.for_sale') }}</th> 
                        </tr> 
                    </thead> 
                    <tbody class="divide-y divide-gray-200"> 
                        @foreach ($lines as $line) 
                            <tr class="hover:bg-blue-50 transition"> 
                                <td class="px-4 py-3 font-mono text-gray-700 dark:text-gray-300">{{ $line->phone_number }}</td> 
                                <td class="px-4 py-3 text-gray-800 dark:text-gray-200">{{ $line->customer?->full_name ?? '-' }}</td> 

                                <td class="px-4 py-3"> 
                                    <input 
                                        type="number" step="0.01" name="lines[{{ $line->id }}][sale_price]" 
                                        value="{{ old("lines.$line->id.sale_price", $line->for_sale ? $line->sale_price : '') }}" 
                                        class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 w-28 text-center text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400" 
                                        placeholder="0.00"
                                    > 
                                </td> 

                                <td class="px-4 py-3"> 
                                    <input 
                                        type="checkbox" name="lines[{{ $line->id }}][selected]" value="1" 
                                        {{ $line->for_sale ? 'checked' : '' }} 
                                        class="w-5 h-5 cursor-pointer text-blue-600 rounded focus:ring-2 focus:ring-blue-400"
                                    > 
                                </td> 
                            </tr> 
                        @endforeach 
                    </tbody> 
                </table> 
                <div class="mt-6">
    {{ $lines->links() }}
</div>

            </div>

            <div class="flex justify-end mt-6"> 
                <button 
                    type="submit" 
                    class="inline-flex items-center gap-2 bg-blue-600 text-white font-semibold px-6 py-2.5 rounded-lg shadow-md hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500"
                > 
                     {{ __('messages.save_changes') }} 
                </button> 
            </div> 
        </form> 
    </div> 
</x-app-layout>
