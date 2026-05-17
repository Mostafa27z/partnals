<form 
    action="{{ isset($line) ? route('lines.update', $line) : route('lines.store') }}" 
    method="POST" 
    class="space-y-4 bg-white dark:bg-gray-800 p-6 rounded shadow"
> 
    @csrf
    @if(isset($line))
        @method('PUT')
    @endif

    <div>
        <label class="block font-medium">مقدمة الرقم (GCode)</label>
        <select name="gcode" class="input input-bordered w-full" required>
            @foreach(['010', '011', '012', '015'] as $code)
                <option value="{{ $code }}" {{ old('gcode', $line->gcode ?? '') == $code ? 'selected' : '' }}>{{ $code }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block font-medium">رقم الهاتف (9 أرقام)</label>
        <input type="text" name="phone_number" maxlength="11" class="input input-bordered w-full"
               value="{{ old('phone_number', $line->phone_number ?? '') }}" required>
    </div>

    <div>
        <label class="block font-medium">مزود الخدمة</label>
        <select name="provider" class="input input-bordered w-full" required>
            @foreach(['Vodafone', 'Etisalat', 'Orange', 'WE'] as $provider)
                <option value="{{ $provider }}" {{ old('provider', $line->provider ?? '') == $provider ? 'selected' : '' }}>
                    {{ $provider }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block font-medium">نوع الخط</label>
        <select name="line_type" class="input input-bordered w-full" required>
            <option value="prepaid" {{ old('line_type', $line->line_type ?? '') == 'prepaid' ? 'selected' : '' }}>مدفوع مسبقاً</option>
            <option value="postpaid" {{ old('line_type', $line->line_type ?? '') == 'postpaid' ? 'selected' : '' }}>فاتورة</option>
        </select>
    </div>

    <div>
        <label class="block font-medium">النظام</label>
        <select name="plan_id" class="input input-bordered w-full">
            <option value="">-- اختر نظاماً --</option>
            @foreach($plans as $plan)
                <option value="{{ $plan->id }}" {{ old('plan_id', $line->plan_id ?? '') == $plan->id ? 'selected' : '' }}>
                    {{ $plan->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block font-medium">الباقة</label>
        <input type="text" name="package" class="input input-bordered w-full" value="{{ old('package', $line->package ?? '') }}">
    </div>

    <div>
        <label class="block font-medium">تاريخ الدفع</label>
        <input type="date" name="payment_date" class="input input-bordered w-full" value="{{ old('payment_date', $line->payment_date ?? '') }}">
    </div>

    <div>
        <label class="block font-medium">ملاحظات</label>
        <textarea name="notes" class="input input-bordered w-full">{{ old('notes', $line->notes ?? '') }}</textarea>
    </div>

    <hr class="my-4">

    <div>
        <label class="block font-medium">ربط عميل موجود (اختياري)</label>
        <select name="customer_id" class="input input-bordered w-full">
            <option value="">-- لا تربط بعميل --</option>
            @foreach ($customers as $customerOption)
                <option value="{{ $customerOption->id }}"
                    {{ old('customer_id', $line->customer_id ?? '') == $customerOption->id ? 'selected' : '' }}>
                    {{ $customerOption->full_name }} - {{ $customerOption->national_id }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block font-medium">اسم عميل جديد (اختياري)</label>
            <input type="text" name="new_full_name" class="input input-bordered w-full" value="{{ old('new_full_name') }}">
        </div>

        <div>
            <label class="block font-medium">الرقم القومي</label>
            <input type="text" name="new_national_id" id="new_national_id" maxlength="14" class="input input-bordered w-full" value="{{ old('new_national_id') }}">
        </div>
    </div>

    <div class="flex justify-end">
        <button type="submit" class="btn btn-primary">
            {{ isset($line) ? 'تحديث الخط' : 'إضافة الخط' }}
        </button>
    </div>
</form>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const newNationalIdInput = document.getElementById('new_national_id');
        if (newNationalIdInput) {
            newNationalIdInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 14);
            });

            newNationalIdInput.addEventListener('blur', function() {
                const value = newNationalIdInput.value.trim();
                let errEl = newNationalIdInput.nextElementSibling;
                if (!errEl || !errEl.classList.contains('nid-error-msg')) {
                    errEl = document.createElement('span');
                    errEl.className = 'nid-error-msg text-red-500 text-sm mt-1 block font-bold';
                    newNationalIdInput.parentNode.insertBefore(errEl, newNationalIdInput.nextSibling);
                }

                const isAr = document.documentElement.lang === 'ar';
                const lengthMsg = isAr ? 'الرقم القومي يجب أن يتكون من 14 رقماً' : 'National ID must be 14 digits';

                if (value !== '' && value.length !== 14) {
                    errEl.textContent = lengthMsg;
                    newNationalIdInput.classList.add('border-red-500', 'focus:ring-red-500');
                } else {
                    errEl.textContent = '';
                    newNationalIdInput.classList.remove('border-red-500', 'focus:ring-red-500');
                }
            });
        }
    });
</script>
@endpush
