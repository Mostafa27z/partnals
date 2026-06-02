<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-gray-900 dark:text-white leading-tight">
            {{ __('messages.manage_users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 flex justify-between items-center bg-white/50 dark:bg-gray-800/50 backdrop-blur-md p-6 rounded-[2rem] border border-gray-100 dark:border-gray-700 shadow-sm">
                <div>
                    <h3 class="text-xl font-black text-gray-800 dark:text-white">{{ __('messages.users_list') ?? 'Users List' }}</h3>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('users.trashed') }}" class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-6 py-3 rounded-2xl font-black transition-all active:scale-95 flex items-center gap-2 border border-gray-200 dark:border-gray-600">
                        <span>🗑️</span>
                        <span>{{ __('messages.deleted_users') }}</span>
                    </a>
                    <a href="{{ route('users.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-2xl font-black shadow-lg shadow-indigo-200 dark:shadow-none transition-all active:scale-95 flex items-center gap-2">
                        <span class="text-xl">+</span>
                        <span>{{ __('messages.add_user') ?? 'Add User' }}</span>
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-2xl border border-emerald-100 dark:border-emerald-800/30 shadow-sm font-bold flex items-center gap-3">
                    <span class="text-xl">✅</span>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 rounded-2xl border border-rose-100 dark:border-rose-800/30 shadow-sm font-bold flex items-center gap-3">
                    <span class="text-xl">⚠️</span>
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-[2.5rem] border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-right" dir="rtl">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                                <th class="px-8 py-5 text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-right">#</th>
                                <th class="px-8 py-5 text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-right">{{ __('messages.name') }}</th>
                                <th class="px-8 py-5 text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-right">{{ __('messages.email') }}</th>
                                <th class="px-8 py-5 text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-right">{{ __('messages.role') }}</th>
                                <th class="px-8 py-5 text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-right">{{ __('messages.base_salary') }}</th>
                                <th class="px-8 py-5 text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-right">{{ __('messages.lines_count') ?? 'Lines Count' }}</th>
                                <th class="px-8 py-5 text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-left">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-all group">
                                    <td class="px-8 py-6 text-sm text-gray-400 dark:text-gray-500 font-bold">{{ $loop->iteration }}</td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3 rtl:flex-row-reverse">
                                            <div class="w-2 h-2 rounded-full bg-indigo-500 shadow-sm shadow-indigo-200"></div>
                                            <span class="text-base font-black text-gray-800 dark:text-gray-200">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-sm font-bold text-gray-600 dark:text-gray-400">{{ $user->email }}</td>
                                    <td class="px-8 py-6">
                                        <span class="px-3 py-1 text-xs font-black rounded-full {{ $user->role && $user->role->name === 'admin' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                            {{ $user->role->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-sm font-bold text-gray-600 dark:text-gray-400">
                                        {{ number_format($user->base_salary, 2) }} {{ __('messages.currency') }}
                                    </td>
                                    <td class="px-8 py-6 text-sm font-bold text-gray-600 dark:text-gray-400">
                                        @if($user->role && $user->role->name === 'موزع')
                                            {{ $user->lines->count() }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center justify-start gap-4">
                                            @php
                                                $cannotDelete = ($user->role && $user->role->name === 'admin') || ($user->id === auth()->id());
                                            @endphp

                                            <a href="{{ route('users.edit', $user) }}" class="flex items-center gap-2 px-4 py-2 text-sm font-black text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-xl transition-all active:scale-95">
                                                <span>📝</span>
                                                <span>{{ __('messages.edit') }}</span>
                                            </a>

                                            @if(!$cannotDelete)
                                                <button onclick="confirmDeletion('{{ $user->id }}', '{{ $user->role->name ?? '' }}', {{ $user->lines->count() }})" 
                                                        class="flex items-center gap-2 px-4 py-2 text-sm font-black text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-xl transition-all active:scale-95">
                                                    <span>🗑️</span>
                                                    <span>{{ __('messages.delete') }}</span>
                                                </button>

                                                <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user) }}" method="POST" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @else
                                                <div class="flex items-center gap-2 px-4 py-2 text-sm font-bold text-gray-400 dark:text-gray-500 cursor-not-allowed">
                                                    <span>🔒</span>
                                                    <span>{{ __('messages.protected') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-8 py-6 bg-gray-50 dark:bg-gray-900/50">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
    {{-- Delete User Modal --}}
    <div id="deleteUserModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-gray-900/50 backdrop-blur-sm transition-opacity" style="display: none;">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 w-full max-w-lg p-6 m-4 animate-in fade-in zoom-in duration-200">
            <h3 class="text-xl font-black text-gray-800 dark:text-white flex items-center gap-2 mb-4">
                <span class="w-8 h-8 bg-rose-100 dark:bg-rose-900/30 text-rose-600 rounded-lg flex items-center justify-center">⚠️</span>
                {{ __('messages.confirm_delete') ?? 'تأكيد الحذف' }}
            </h3>
            
            <p id="deleteModalMessage" class="text-gray-600 dark:text-gray-400 mb-6 font-bold text-sm">
                {{ __('messages.are_you_sure_delete_user') ?? 'هل أنت متأكد من حذف هذا المستخدم؟' }}
            </p>

            <form id="modalDeleteForm" method="POST">
                @csrf
                @method('DELETE')
                
                <div id="distributorOptions" class="hidden mb-6 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-700">
                    <p class="text-sm font-black text-gray-800 dark:text-gray-200 mb-3">ماذا تريد أن تفعل بخطوط هذا الموزع؟</p>
                    
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="line_action" value="delete" class="w-4 h-4 text-rose-600 focus:ring-rose-500 border-gray-300 dark:border-gray-600" checked onchange="toggleDistributorSelect()">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">حذف الخطوط (نقل لسلة المهملات)</span>
                        </label>
                        
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="line_action" value="reassign" class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600" onchange="toggleDistributorSelect()">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">نقل الخطوط لموزع آخر</span>
                        </label>
                    </div>

                    <div id="distributorSelectWrapper" class="mt-4 hidden animate-in fade-in slide-in-from-top-2">
                        <label class="block text-xs font-black text-gray-500 dark:text-gray-400 mb-1">اختر الموزع الجديد</label>
                        <select name="new_distributor_id" id="new_distributor_id" class="w-full px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 transition text-sm">
                            <option value="">-- اختر الموزع --</option>
                            @foreach($distributors as $distributor)
                                <option value="{{ $distributor->id }}">{{ $distributor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700/50">
                    <button type="button" onclick="closeDeleteModal()" class="px-5 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-bold transition-all">
                        {{ __('messages.cancel') ?? 'إلغاء' }}
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold transition-all shadow-lg shadow-rose-500/30">
                        {{ __('messages.confirm') ?? 'تأكيد الحذف' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmDeletion(userId, roleName, linesCount) {
            const modal = document.getElementById('deleteUserModal');
            const form = document.getElementById('modalDeleteForm');
            const distOptions = document.getElementById('distributorOptions');
            const messageEl = document.getElementById('deleteModalMessage');
            
            // Set form action dynamically
            form.action = `/admin/users/${userId}`;

            // Reset options
            document.querySelector('input[name="line_action"][value="delete"]').checked = true;
            toggleDistributorSelect();

            // Hide the user being deleted from the new distributor dropdown
            const distSelect = document.getElementById('new_distributor_id');
            for (let i = 0; i < distSelect.options.length; i++) {
                if (distSelect.options[i].value == userId) {
                    distSelect.options[i].style.display = 'none';
                } else {
                    distSelect.options[i].style.display = '';
                }
            }

            if (roleName === 'موزع' && linesCount > 0) {
                messageEl.textContent = `هذا الموزع لديه ${linesCount} خط/خطوط. يرجى تحديد الإجراء المطلوب قبل الحذف.`;
                distOptions.classList.remove('hidden');
            } else {
                messageEl.textContent = "{{ __('messages.are_you_sure_delete_user') ?? 'هل أنت متأكد من حذف هذا المستخدم؟' }}";
                distOptions.classList.add('hidden');
            }

            modal.style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('deleteUserModal').style.display = 'none';
        }

        function toggleDistributorSelect() {
            const isReassign = document.querySelector('input[name="line_action"][value="reassign"]').checked;
            const selectWrapper = document.getElementById('distributorSelectWrapper');
            const select = document.getElementById('new_distributor_id');
            
            if (isReassign) {
                selectWrapper.classList.remove('hidden');
                select.required = true;
            } else {
                selectWrapper.classList.add('hidden');
                select.required = false;
                select.value = "";
            }
        }
    </script>
    @endpush
</x-app-layout>
