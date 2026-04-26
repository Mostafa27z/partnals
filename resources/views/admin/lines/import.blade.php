<x-app-layout> 
    <x-slot name="header"> 
        <h2 class="text-2xl font-black text-gray-800 dark:text-gray-100 flex items-center gap-3">
            <span class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-violet-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 dark:shadow-none">
                <span class="text-white text-lg">📥</span>
            </span>
            {{ __('messages.upload_excel_lines') }}
        </h2> 
    </x-slot> 

    <div class="py-8 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8"> 
        @if(session('success')) 
            <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/30 text-emerald-700 dark:text-emerald-300 rounded-2xl shadow-sm flex items-center gap-3 font-bold">
                <span class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg flex items-center justify-center text-lg shrink-0">✅</span>
                {{ session('success') }}
            </div> 
        @endif 

        @if($errors->any()) 
            <div class="mb-6 p-4 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800/30 text-rose-700 dark:text-rose-300 rounded-2xl shadow-sm"> 
                <ul class="list-disc list-inside space-y-1 text-sm font-medium"> 
                    @foreach($errors->all() as $e) 
                        <li>{{ $e }}</li> 
                    @endforeach 
                </ul> 
            </div> 
        @endif 

        <form action="{{ route('lines.import.process') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50"> 
            @csrf 
            <div class="mb-6"> 
                <label for="file" class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-3">{{ __('messages.excel_file') }}</label> 
                <input 
                    type="file" 
                    name="file" 
                    id="file" 
                    accept=".xlsx" 
                    required 
                    class="w-full text-sm file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 dark:file:bg-indigo-900/30 file:text-indigo-700 dark:file:text-indigo-400 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/50 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 cursor-pointer"
                > 
            </div> 
            <button 
                type="submit" 
                class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/25 hover:-translate-y-0.5"
            >
                {{ __('messages.upload_file') }}
            </button> 
        </form> 
    </div> 
</x-app-layout>
