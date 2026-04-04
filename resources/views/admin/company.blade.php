<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            معلومات الشركة
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto bg-white dark:bg-gray-800 p-6 rounded shadow">
            <form method="POST" action="{{ route('company.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300">اسم الشركة</label>
                        <input type="text" name="company_name" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300">وصف الشركة</label>
                        <textarea name="company_description" rows="3" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm"></textarea>
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300">شعار الشركة</label>
                        <input type="file" name="company_logo" class="mt-1 block w-full" />
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300">تفعيل البريد الإلكتروني</label>
                        <input type="text" name="email_activation" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300">اسم المستخدم للتفعيل</label>
                        <input type="text" name="active_username" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300">كلمة المرور للتفعيل</label>
                        <input type="text" name="active_password" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300">المنفذ للتفعيل</label>
                        <input type="number" name="active_port" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300">عدد أيام العقوبة</label>
                        <input type="number" name="suspension_penalty_days" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300">عدد الأيام المسموح بها للتعليق</label>
                        <input type="number" name="allowed_suspension_days" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300">البريد الإلكتروني للمشاكل</label>
                        <input type="text" name="email_problem" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300">اسم المستخدم للمشاكل</label>
                        <input type="text" name="problem_username" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300">كلمة المرور للمشاكل</label>
                        <input type="text" name="problem_password" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300">المنفذ للمشاكل</label>
                        <input type="number" name="problem_port" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-gray-700 dark:text-gray-300">إعدادات SMTP أو السيرفر</label>
                        <input type="text" name="smtp_configuration" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300">CC</label>
                        <input type="text" name="cc" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300">BCC</label>
                        <input type="text" name="bcc" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300">BCC2</label>
                        <input type="text" name="bcc2" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300">اسم المستخدم للبوابة</label>
                        <input type="text" name="portal_username" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300">كلمة المرور للبوابة</label>
                        <input type="text" name="portal_password" class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
                    </div>
                </div>

                <div class="mt-6 text-left">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 shadow">
                        حفظ البيانات
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
