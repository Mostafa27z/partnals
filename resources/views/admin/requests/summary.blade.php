<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                📊 {{ __('messages.summary_requests') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto mt-6 p-6">
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
        @endphp

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($counts as $type => $data)
                <div class="bg-white dark:bg-gray-800 shadow rounded-2xl p-6 border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2 text-lg font-semibold text-gray-700 dark:text-gray-300">
                            <span class="text-2xl">{{ $icons[$type] ?? '📄' }}</span>
                            {{ __('messages.request_type_' . $type) }}
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
                            📅 {{ __('messages.today') }}: {{ $data['today'] }}
                        </span>
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                            📦 {{ __('messages.total') }}: {{ $data['total'] }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
