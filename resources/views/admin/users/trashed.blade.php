<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-gray-900 dark:text-white leading-tight">
            {{ __('messages.deleted_users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 flex justify-between items-center bg-white/50 dark:bg-gray-800/50 backdrop-blur-md p-6 rounded-[2rem] border border-gray-100 dark:border-gray-700 shadow-sm">
                <div>
                    <h3 class="text-xl font-black text-gray-800 dark:text-white">{{ __('messages.deleted_users') }}</h3>
                </div>
                <a href="{{ route('users.index') }}" class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-6 py-3 rounded-2xl font-black transition-all active:scale-95 flex items-center gap-2 border border-gray-200 dark:border-gray-600">
                    <span>⬅️</span>
                    <span>{{ __('messages.back_to_users') }}</span>
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-2xl border border-emerald-100 dark:border-emerald-800/30 shadow-sm font-bold flex items-center gap-3">
                    <span class="text-xl">✅</span>
                    {{ session('success') }}
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
                                <th class="px-8 py-5 text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-right">{{ __('messages.deleted_at') }}</th>
                                <th class="px-8 py-5 text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-left">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-all group">
                                    <td class="px-8 py-6 text-sm text-gray-400 dark:text-gray-500 font-bold">{{ $loop->iteration }}</td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3 rtl:flex-row-reverse">
                                            <div class="w-2 h-2 rounded-full bg-rose-500 shadow-sm shadow-rose-200"></div>
                                            <span class="text-base font-black text-gray-800 dark:text-gray-200">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-sm font-bold text-gray-600 dark:text-gray-400">{{ $user->email }}</td>
                                    <td class="px-8 py-6 text-sm font-bold text-gray-600 dark:text-gray-400">{{ $user->role->name ?? '-' }}</td>
                                    <td class="px-8 py-6 text-sm font-bold text-gray-600 dark:text-gray-400">{{ $user->deleted_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center justify-start gap-4">
                                            <form action="{{ route('users.restore', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="flex items-center gap-2 px-4 py-2 text-sm font-black text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-xl transition-all active:scale-95">
                                                    <span>🔄</span>
                                                    <span>{{ __('messages.restore') }}</span>
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('users.forceDelete', $user->id) }}" method="POST" onsubmit="return confirm('{{ __("messages.confirm_permanently_delete_user") ?? "Are you sure? This action cannot be undone." }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="flex items-center gap-2 px-4 py-2 text-sm font-black text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-xl transition-all active:scale-95">
                                                    <span>🧨</span>
                                                    <span>{{ __('messages.force_delete') }}</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-10 text-center text-gray-500 font-bold italic">
                                        {{ __('messages.no_deleted_users') ?? 'No deleted users found.' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-8 py-6 bg-gray-50 dark:bg-gray-900/50">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
