<x-app-layout> 
    <x-slot name="header"> 
        <h2 class="text-xl font-bold">طلب تغيير النظام لرقم {{ $line->phone_number }}</h2> 
    </x-slot> 
 
    <div class="max-w-xl mx-auto bg-white dark:bg-gray-800 p-6 rounded shadow mt-6"> 
        <form method="POST" action="{{ route('requests.change-plan.store') }}"> 
            @csrf 
            <input type="hidden" name="line_id" value="{{ $line->id }}"> 
 
            <!-- النظام الحالي -->
            <div class="mb-4 bg-gray-100 dark:bg-gray-900 p-4 rounded">
                <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-1">🔁 النظام الحالي</h3>
                <p>
                    {{ $line->plan?->name ?? 'لا يوجد نظام حالي' }} -
                    {{ $line->plan ? number_format($line->plan->price, 2) . ' ج.م' : '-' }}
                </p>
            </div>

            <!-- النظام الجديد -->
            <div class="mb-4"> 
                <label class="block font-bold mb-1">النظام الجديد ({{ $line->provider }})</label> 
                <select name="new_plan_id" class="w-full border p-2 rounded" required> 
                    <option value="">-- اختر النظام --</option> 
                    @foreach ($plans as $plan) 
                        <option value="{{ $plan->id }}">{{ $plan->name }} ({{ number_format($plan->price, 2) }} ج.م)</option> 
                    @endforeach 
                </select> 
            </div> 
 
            <!-- ملاحظات -->
            <div class="mb-4"> 
                <label class="block font-bold mb-1">ملاحظات (اختياري)</label> 
                <textarea name="comment" class="w-full border p-2 rounded">{{ old('comment') }}</textarea> 
            </div> 
 
            <div class="text-end"> 
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">💾 حفظ الطلب</button> 
            </div> 
        </form> 
    </div> 
</x-app-layout>
