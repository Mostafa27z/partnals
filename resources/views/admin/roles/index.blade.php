<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-gray-900 dark:text-white leading-tight">
            {{ __('messages.manage_roles') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 flex justify-between items-center bg-white/50 dark:bg-gray-800/50 backdrop-blur-md p-6 rounded-[2rem] border border-gray-100 dark:border-gray-700 shadow-sm">
                <div>
                    <h3 class="text-xl font-black text-gray-800 dark:text-white">{{ __('messages.manage_roles') }}</h3>
                    <!-- <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">{{ __('إدارة صلاحيات الوصول وأدوار المستخدمين') }}</p> -->
                </div>
                <button onclick="toggleModal('addRoleModal')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-2xl font-black shadow-lg shadow-indigo-200 dark:shadow-none transition-all active:scale-95 flex items-center gap-2">
                    <span class="text-xl">+</span>
                    <span>{{ __('messages.add_role') }}</span>
                </button>
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
                    <table class="w-full text-right">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                                <th class="px-8 py-5 text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">#</th>
                                <th class="px-8 py-5 text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ __('messages.role_name') }}</th>
                                <th class="px-8 py-5 text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-left">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($roles as $role)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-all group">
                                    <td class="px-8 py-6 text-sm text-gray-400 dark:text-gray-500 font-bold">{{ $loop->iteration }}</td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-2 h-2 rounded-full bg-indigo-500 shadow-sm shadow-indigo-200"></div>
                                            <span class="text-base font-black text-gray-800 dark:text-gray-200">{{ $role->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center justify-start gap-4 rtl:justify-end">
                                            @if($role->name !== 'admin' && $role->name !== 'موزع')
                                                <button onclick="editRole({{ $role->id }}, '{{ $role->name }}')" class="flex items-center gap-2 px-4 py-2 text-sm font-black text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-xl transition-all active:scale-95">
                                                    <span>📝</span>
                                                    <span>{{ __('messages.edit') }}</span>
                                                </button>
                                                
                                                <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_delete_role') }}')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="flex items-center gap-2 px-4 py-2 text-sm font-black text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-xl transition-all active:scale-95">
                                                        <span>🗑️</span>
                                                        <span>{{ __('messages.delete') }}</span>
                                                    </button>
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
            </div>
        </div>
    </div>

    {{-- Add Role Modal --}}
    <div id="addRoleModal" class="fixed inset-0 z-50 hidden transition-all duration-300">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('addRoleModal')"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-[2.5rem] w-full max-w-lg p-8 shadow-2xl border border-gray-100 dark:border-gray-700 animate-in zoom-in duration-300">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white">{{ __('messages.add_role') }}</h3>
                    <button onclick="toggleModal('addRoleModal')" class="text-gray-400 hover:text-gray-600 transition-colors">✕</button>
                </div>
                <form action="{{ route('roles.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-black text-gray-700 dark:text-gray-300 mb-2 px-1">{{ __('messages.role_name') }}</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-5 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all font-bold text-gray-800 dark:text-white" placeholder="مثال: مدير المبيعات">
                    </div>
                    <div class="flex gap-4 pt-4">
                        <button type="submit" class="flex-1 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black shadow-xl shadow-indigo-200 dark:shadow-none transition-all active:scale-95">
                            {{ __('messages.save') }}
                        </button>
                        <button type="button" onclick="toggleModal('addRoleModal')" class="flex-1 py-4 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-2xl font-black transition-all active:scale-95">
                            {{ __('messages.cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Role Modal --}}
    <div id="editRoleModal" class="fixed inset-0 z-50 hidden transition-all duration-300">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="toggleModal('editRoleModal')"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-[2.5rem] w-full max-w-lg p-8 shadow-2xl border border-gray-100 dark:border-gray-700 animate-in zoom-in duration-300">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white">{{ __('messages.edit_role') }}</h3>
                    <button onclick="toggleModal('editRoleModal')" class="text-gray-400 hover:text-gray-600 transition-colors">✕</button>
                </div>
                <form id="editRoleForm" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-black text-gray-700 dark:text-gray-300 mb-2 px-1">{{ __('messages.role_name') }}</label>
                        <input type="text" name="name" id="editRoleName" required class="w-full px-5 py-4 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all font-bold text-gray-800 dark:text-white">
                    </div>
                    <div class="flex gap-4 pt-4">
                        <button type="submit" class="flex-1 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black shadow-xl shadow-indigo-200 dark:shadow-none transition-all active:scale-95">
                            {{ __('messages.update') }}
                        </button>
                        <button type="button" onclick="toggleModal('editRoleModal')" class="flex-1 py-4 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-2xl font-black transition-all active:scale-95">
                            {{ __('messages.cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            modal.classList.toggle('hidden');
        }

        function editRole(id, name) {
            const form = document.getElementById('editRoleForm');
            const input = document.getElementById('editRoleName');
            form.action = `/admin/roles/${id}`;
            input.value = name;
            toggleModal('editRoleModal');
        }

        @if ($errors->has('name'))
            document.addEventListener('DOMContentLoaded', () => {
                toggleModal('addRoleModal');
            });
        @endif
    </script>
</x-app-layout>
