<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="text-2xl">📊</span>
            <h2 class="text-xl font-black text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.summary_requests') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 px-4 max-w-7xl mx-auto">
        @php
            $icons = [
                'resell' => '🔁',
                'change_plan' => '📶',
                'change_chip' => '📱',
                'pause' => '⏸️',
                'resume' => '▶️',
                'change_date' => '📅',
                'change_distributor' => '🏢',
                'stop' => '⛔',
            ];
            
            $gradients = [
                'resell' => 'from-indigo-500 to-purple-600',
                'change_plan' => 'from-emerald-500 to-teal-600',
                'change_chip' => 'from-blue-500 to-indigo-600',
                'pause' => 'from-rose-500 to-pink-600',
                'resume' => 'from-emerald-400 to-emerald-600',
                'change_date' => 'from-amber-500 to-orange-600',
                'change_distributor' => 'from-violet-500 to-purple-600',
                'stop' => 'from-red-500 to-rose-600',
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach ($counts as $type => $data)
                <div class="group relative bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700/50 p-8 overflow-hidden transition-all hover:scale-[1.02] hover:-translate-y-1">
                    <!-- Decorative Background Gradient for active feel -->
                    <div class="absolute -top-24 -right-24 w-48 h-48 bg-gradient-to-br {{ $gradients[$type] ?? 'from-gray-400 to-gray-600' }} rounded-full blur-3xl opacity-5 group-hover:opacity-20 transition-opacity"></div>
                    
                    <div class="relative z-10">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-3xl flex items-center justify-center text-3xl mb-4 shadow-inner">
                                {{ $icons[$type] ?? '📄' }}
                            </div>
                            
                            <h3 class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-1 leading-none">
                                {{ __('messages.request_type') }}
                            </h3>
                            <p class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-wider mb-6">
                                {{ __('messages.request_type_' . $type) }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-3 pt-6 border-t border-gray-50 dark:border-gray-700/50">
                            <div class="bg-gray-50/50 dark:bg-gray-900/50 rounded-2xl p-3 text-center">
                                <p class="text-[8px] font-black text-indigo-500 dark:text-indigo-400 uppercase tracking-widest mb-1">{{ __('messages.today') }}</p>
                                <p class="text-lg font-black text-gray-900 dark:text-white leading-none">{{ $data['today'] }}</p>
                            </div>
                            <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl p-3 text-center border border-indigo-100 dark:border-indigo-800/50">
                                <p class="text-[8px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">{{ __('messages.total') }}</p>
                                <p class="text-lg font-black text-indigo-600 dark:text-indigo-400 leading-none">{{ $data['total'] }}</p>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-center">
                            <a href="{{ route('requests.all', ['type' => $type]) }}" class="text-[10px] font-black text-gray-300 dark:text-gray-600 hover:text-indigo-500 dark:hover:text-indigo-400 uppercase tracking-widest transition-colors flex items-center gap-2">
                                {{ __('messages.view_all') ?? 'View All' }}
                                <span class="text-lg">→</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
