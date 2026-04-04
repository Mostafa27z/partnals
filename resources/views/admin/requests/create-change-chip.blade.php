<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">طلب تغيير شريحة</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-800 p-4 rounded shadow">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>❌ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('requests.change-chip.store') }}" method="POST" class="bg-white dark:bg-gray-800 p-6 rounded shadow space-y-4">
            @csrf
            <input type="hidden" name="line_id" value="{{ $line->id }}">

            <!-- نوع التغيير -->
            <div>
                <label class="block font-bold mb-1">نوع التغيير</label>
                <select name="change_type" class="w-full border p-2 rounded" required id="change-type">
                    <option value="">-- اختر النوع --</option>
                    <option value="chip" {{ old('change_type') == 'chip' ? 'selected' : '' }}>على الشريحة</option>
                    <option value="branch" {{ old('change_type') == 'branch' ? 'selected' : '' }}>في الفرع</option>
                </select>
            </div>

            <!-- مسلسل قديم -->
            <div>
                <label class="block font-bold mb-1">المسلسل القديم (اختياري)</label>
                <input type="text" minlength="19" maxlength="19" name="old_serial" value="{{ old('old_serial') }}" class="w-full border p-2 rounded">
            </div>

            <!-- مسلسل جديد -->
            <div id="new-serial-group">
                <label class="block font-bold mb-1">المسلسل الجديد</label>
                <input type="text" maxlength="19" name="new_serial" id="new_serial" value="{{ old('new_serial') }}" class="w-full border p-2 rounded">
            </div>

            <!-- الاسم الكامل -->
            <div id="full-name-group">
                <label class="block font-bold mb-1">الاسم الكامل (فرع فقط)</label>
                <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" class="w-full border p-2 rounded">
            </div>

            <!-- الرقم القومي -->
            <div id="national-id-group">
                <label class="block font-bold mb-1">الرقم القومي (فرع فقط)</label>
                <input type="text" name="national_id" id="national_id" value="{{ old('national_id') }}" class="w-full border p-2 rounded">
            </div>

            <!-- التاريخ -->
            <div>
                <label class="block font-bold mb-1">تاريخ الطلب</label>
                <input type="date" name="request_date" value="{{ old('request_date', now()->toDateString()) }}" class="w-full border p-2 rounded" required>
            </div>

            <!-- ملاحظات -->
            <div>
                <label class="block font-bold mb-1">ملاحظات</label>
                <textarea name="comment" class="w-full border p-2 rounded" rows="3">{{ old('comment') }}</textarea>
            </div>

            <div class="text-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow">💾 حفظ الطلب</button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const typeSelect = document.getElementById('change-type');
            const newSerialGroup = document.getElementById('new-serial-group');
            const newSerialInput = document.getElementById('new_serial');
            const fullNameGroup = document.getElementById('full-name-group');
            const nationalIdGroup = document.getElementById('national-id-group');
            const fullNameInput = document.getElementById('full_name');
            const nationalIdInput = document.getElementById('national_id');

            function toggleFields() {
                const value = typeSelect.value;

                if (value === 'chip') {
                    // newSerialGroup.style.display = '';
                    newSerialInput.required = true;

                    // fullNameGroup.style.display = 'none';
                    // nationalIdGroup.style.display = 'none';
                    fullNameInput.required = false;
                    nationalIdInput.required = false;

                } else if (value === 'branch') {
                    // newSerialGroup.style.display = '';
                    newSerialInput.required = false;

                    // fullNameGroup.style.display = '';
                    // nationalIdGroup.style.display = '';
                    fullNameInput.required = true;
                    nationalIdInput.required = true;

                } else {
                    // newSerialGroup.style.display = '';
                    // fullNameGroup.style.display = '';
                    // nationalIdGroup.style.display = '';
                    fullNameInput.required = false;
                    nationalIdInput.required = false;
                }
            }

            toggleFields(); // عند تحميل الصفحة
            typeSelect.addEventListener('change', toggleFields);
        });
    </script>
    @endpush
</x-app-layout>
