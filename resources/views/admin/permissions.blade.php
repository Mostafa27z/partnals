<x-app-layout> 
    <x-slot name="header"> 
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"> 
            {{ __('إدارة صلاحيات الأزرار حسب نوع المستخدم') }} 
        </h2> 
    </x-slot> 

    <div class="py-12"> 
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> 
            @if(session('success')) 
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded shadow">
                    {{ session('success') }} 
                </div> 
            @endif 

            <form action="{{ route('permissions.update') }}" method="POST"> 
                <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg p-4"> 
                    @csrf 

                    <table class="w-full table-auto border dark:border-gray-700 text-sm text-right text-gray-800 dark:text-gray-200 dark:text-gray-300"> 
                        <thead class="bg-gray-100 dark:bg-gray-900 border-b dark:border-gray-700"> 
                            <tr> 
                                <th class="p-2 border dark:border-gray-700">{{ __('الصلاحية') }}</th> 
                                @foreach($roles->where('id', '!=', 1) as $role) 
                                    <th class="p-2 border dark:border-gray-700">{{ $role->name }}</th> 
                                @endforeach 
                            </tr> 
                        </thead> 
                        <tbody> 
                            @foreach($permissions as $permission) 
                                <tr class="hover:bg-gray-50 dark:bg-gray-700/50 dark:hover:bg-gray-700/50 transition-colors"> 
                                    <td class="p-2 border dark:border-gray-700 font-medium">{{ __($permission->name) }}</td> 
                                    @foreach($roles->where('id', '!=', 1) as $role) 
                                        <td class="p-2 border dark:border-gray-700 text-center"> 
                                            <input type="checkbox" 
                                                class="rounded dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                name="permission_{{ $permission->id }}[]" 
                                                value="{{ $role->id }}" 
                                                {{ $permission->roles->contains('id', $role->id) ? 'checked' : '' }}> 
                                        </td> 
                                    @endforeach 
                                </tr> 
                            @endforeach 
                        </tbody> 
                    </table> 
                </div>

                <div class="text-left mt-6 flex justify-center">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow">
                        {{ __('حفظ التعديلات') }}
                    </button>
                </div>
            </form> 
        </div> 
    </div> 
</x-app-layout> 
