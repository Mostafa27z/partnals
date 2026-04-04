@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- عنوان الصفحة --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-extrabold text-gray-900">لوحة تحكم الأدمن - إدارة الصلاحيات</h2>
    </div>

    {{-- المحتوى التعريفي بالنظام --}}
    <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-lg p-5 mb-6 shadow-sm">
        <h5 class="text-lg font-semibold mb-2">📌 نبذة عن النظام</h5>
        <p class="mb-3">
            هذا النظام يتيح لك كمدير التحكم الكامل في الصلاحيات الممنوحة للمستخدمين.  
            يمكنك تفعيل أو إلغاء تفعيل أي صلاحية من خلال الجدول أدناه، مما يساعد على ضبط المهام 
            والصلاحيات بدقة داخل بيئة العمل.
        </p>
        <ul class="list-disc list-inside space-y-1">
            <li>✔️ عرض وإدارة الصلاحيات الحالية.</li>
            <li>✔️ تفعيل/إلغاء تفعيل أي صلاحية بسهولة.</li>
            <li>✔️ حفظ التغييرات بشكل فوري لتطبيقها على المستخدمين.</li>
        </ul>
    </div>

    {{-- رسالة نجاح --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- نموذج إدارة الصلاحيات --}}
    <form action="{{ route('admin.dashboard.update') }}" method="POST" class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 border border-gray-200 dark:border-gray-700">
        @csrf
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 text-center">
                <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-3 text-sm font-semibold">اسم الصلاحية</th>
                        <th class="px-6 py-3 text-sm font-semibold">مفعلة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($permissions as $permission)
                    <tr class="hover:bg-gray-50 dark:bg-gray-700/50 transition">
                        <td class="px-6 py-3 font-medium text-gray-800 dark:text-gray-200">{{ $permission->name }}</td>
                        <td class="px-6 py-3">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                {{ $permission->is_active ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded focus:ring-blue-500">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 text-left">
            <button type="submit" 
                class="px-6 py-2 bg-green-600 text-white font-medium rounded-lg shadow hover:bg-green-700 transition">
                💾 تحديث الصلاحيات
            </button>
        </div>
    </form>
</div>
@endsection
