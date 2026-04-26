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
                                                $isProtected = ($user->role && $user->role->name === 'admin') || ($user->id === auth()->id());
                                            @endphp

                                            @if(!$isProtected)
                                                <a href="{{ route('users.edit', $user) }}" class="flex items-center gap-2 px-4 py-2 text-sm font-black text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-xl transition-all active:scale-95">
                                                    <span>📝</span>
                                                    <span>{{ __('messages.edit') }}</span>
                                                </a>
                                                
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

    @push('scripts')
    <script>
        function confirmDeletion(userId, roleName, linesCount) {
            let message = "{{ __('messages.confirm_delete') ?? 'Are you sure?' }}";
            
            if (roleName === 'موزع' && linesCount > 0) {
                message = "{{ __('messages.distributor_delete_warning') }}".replace(':count', linesCount);
            }

            if (confirm(message)) {
                document.getElementById('delete-form-' + userId).submit();
            }
        }
    </script>
    @endpush
</x-app-layout>
