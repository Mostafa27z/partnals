<x-app-layout>
    <div class="bg-gradient-to-b from-gray-50 via-white to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen py-12">

        <!-- Hero Section -->
        <div class="max-w-6xl mx-auto px-6 text-center mb-10">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">
                {{ __('messages.Manage Plans') }}
            </h1>
            <div class="mt-6 flex flex-wrap justify-center gap-4">
                <a href="{{ route('plans.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full shadow-lg transform hover:scale-105 transition">
                    + {{ __('messages.Add Plan') }}
                </a>
                @if(auth()->user()->hasPermission('delete plan'))
                <a href="{{ route('plans.trashed') }}"
                   class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-full shadow-lg transform hover:scale-105 transition">
                    🗑️ {{ __('messages.Deleted Plans') }}
                </a>
                @endif
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-6">

            <!-- Filter Card -->
            <form method="GET" 
                  class="bg-white dark:bg-gray-800/90 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 mb-8 border border-gray-200 dark:border-gray-700">
                <div class="flex flex-wrap gap-4 justify-between items-center">
                    <div class="flex flex-wrap gap-3 items-center">
                        <input name="search" placeholder="{{ __('messages.Search') }}..."
                               value="{{ request('search') }}"
                               class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 transition w-48">
                        <select name="provider"
                                class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">{{ __('messages.Provider') }}</option>
                            @foreach(\App\Models\Plan::select('provider')->distinct()->pluck('provider') as $provider)
                                <option value="{{ $provider }}" {{ request('provider') == $provider ? 'selected' : '' }}>
                                    {{ $provider }}
                                </option>
                            @endforeach
                        </select>
                        <input type="number" step="0.01" name="min_price"
                               placeholder="Min Price" value="{{ request('min_price') }}"
                               class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2 rounded-lg w-28 focus:ring-2 focus:ring-blue-500 transition">
                        <input type="number" step="0.01" name="max_price"
                               placeholder="Max Price" value="{{ request('max_price') }}"
                               class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2 rounded-lg w-28 focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-full shadow-md hover:scale-105 transition">
                            {{ __('messages.Search') }}
                        </button>
                        <a href="{{ route('plans.export', request()->query()) }}"
                           class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-full shadow-md hover:scale-105 transition">
                            📤 {{ __('messages.Export to Excel') }}
                        </a>
                    </div>
                </div>
            </form>

            <!-- Table -->
            <div class="overflow-x-auto bg-white dark:bg-gray-800/95 dark:bg-gray-800/90 backdrop-blur-sm shadow-lg rounded-2xl border border-gray-200 dark:border-gray-700">
                <table class="min-w-full text-right">
                    <thead class="bg-gray-100 dark:bg-gray-900/80 dark:bg-gray-700/80 text-gray-700 dark:text-gray-300 dark:text-gray-200">
                        <tr>
                            @foreach(['Name','Price','Provider','Provider Price','Type','Plan Code','Description','Actions'] as $header)
                                <th class="px-6 py-4 border-b dark:border-gray-700 text-sm font-semibold">
                                    {{ __("messages.$header") }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-200">
                        @forelse($plans as $plan)
                            <tr class="hover:bg-gray-50 dark:bg-gray-700/50 dark:hover:bg-gray-700 transition">
                                <td class="px-6 py-3 border-b dark:border-gray-700">{{ $plan->name }}</td>
                                <td class="px-6 py-3 border-b dark:border-gray-700">{{ $plan->price }}</td>
                                <td class="px-6 py-3 border-b dark:border-gray-700">{{ $plan->provider }}</td>
                                <td class="px-6 py-3 border-b dark:border-gray-700">{{ $plan->provider_price }}</td>
                                <td class="px-6 py-3 border-b dark:border-gray-700">{{ $plan->type }}</td>
                                <td class="px-6 py-3 border-b dark:border-gray-700">{{ $plan->plan_code }}</td>
                                <td class="px-6 py-3 border-b dark:border-gray-700">{{ $plan->penalty }}</td>
                                <td class="px-6 py-3 border-b dark:border-gray-700 flex gap-4 justify-center">
                                    <a href="{{ route('plans.show', $plan->id) }}" class="text-green-600 hover:underline">{{ __('messages.View') }}</a>
                                    <a href="{{ route('plans.edit', $plan->id) }}" class="text-blue-600 hover:underline">{{ __('messages.Edit') }}</a>
                                    @if(auth()->user()->hasPermission('delete plan'))
                                    <button type="button" onclick="openDeleteModal('{{ route('plans.destroy', $plan->id) }}', '{{ addslashes($plan->name) }}', '{{ $plan->id }}', '{{ $plan->lines()->count() }}', '{{ addslashes($plan->provider) }}')" class="text-red-600 hover:underline">
                                        {{ __('messages.Delete') }}
                                    </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-gray-500 dark:text-gray-400 py-6">
                                    {{ __('messages.No records found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $plans->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Plan Modal -->
    <div id="deletePlanModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="closeDeleteModal()"></div>

            <!-- Modal panel -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl text-right overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200 dark:border-gray-700">
                <form id="deletePlanForm" method="POST" action="" class="p-6">
                    @csrf
                    @method('DELETE')
                    
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4" id="modal-title">
                        {{ __('messages.delete_plan_title') }}
                    </h3>
                    
                    <p class="text-gray-600 dark:text-gray-300 mb-6 text-sm" id="deletePlanWarningText">
                        {{ __('messages.delete_plan_warning', ['name' => '']) }}
                    </p>

                    <!-- Options -->
                    <div class="space-y-4 mb-6 text-right">
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition">
                            <input type="radio" name="action" value="reassign" checked onchange="toggleReassignDropdown(true)" class="h-5 w-5 text-blue-600 border-gray-300 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                            <span class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                {{ __('messages.reassign_lines') }}
                            </span>
                        </label>

                        <!-- Reassign Dropdown Container -->
                        <div id="reassignDropdownContainer" class="pl-8 text-right">
                            <label for="reassign_plan_id" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">
                                {{ __('messages.select_replacement_plan') }}
                            </label>
                            <select name="reassign_plan_id" id="reassign_plan_id" required class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2.5 rounded-xl focus:ring-2 focus:ring-blue-500 transition">
                                <option value="">{{ __('messages.Choose Plan') }}</option>
                                @foreach($allPlans as $ap)
                                    <option value="{{ $ap->id }}" data-provider="{{ $ap->provider }}">{{ $ap->name }} ({{ $ap->price }} {{ __('messages.currency') }})</option>
                                @endforeach
                            </select>
                        </div>

                        <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition">
                            <input type="radio" name="action" value="delete_only" onchange="toggleReassignDropdown(false)" class="h-5 w-5 text-blue-600 border-gray-300 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                            <span class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                {{ __('messages.just_delete_plan') }}
                            </span>
                        </label>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="closeDeleteModal()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold rounded-full transition">
                            {{ __('messages.cancel') }}
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-full shadow-lg transition">
                            {{ __('messages.confirm_delete') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(deleteUrl, planName, planId, linesCount, planProvider) {
            const modal = document.getElementById('deletePlanModal');
            const form = document.getElementById('deletePlanForm');
            const warningText = document.getElementById('deletePlanWarningText');
            const select = document.getElementById('reassign_plan_id');

            // Set action url
            form.action = deleteUrl;

            // Set warning text with plan name and lines count
            const warningTemplate = {!! json_encode(__('messages.delete_plan_warning', ['name' => ':name', 'count' => ':count'])) !!};
            warningText.innerText = warningTemplate.replace(':name', planName).replace(':count', linesCount);

            // Filter options to show only plans related to the same provider (excluding current plan)
            Array.from(select.options).forEach(option => {
                if (option.value === "") {
                    option.style.display = '';
                } else if (option.value === String(planId)) {
                    option.style.display = 'none';
                } else if (option.dataset.provider === planProvider) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });

            // Reset inputs
            form.querySelector('input[name="action"][value="reassign"]').checked = true;
            toggleReassignDropdown(true);

            // Show modal
            modal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deletePlanModal');
            modal.classList.add('hidden');
        }

        function toggleReassignDropdown(show) {
            const container = document.getElementById('reassignDropdownContainer');
            const select = document.getElementById('reassign_plan_id');
            if (show) {
                container.classList.remove('hidden');
                select.required = true;
            } else {
                container.classList.add('hidden');
                select.required = false;
                select.value = ''; // Reset value
            }
        }
    </script>
</x-app-layout>
