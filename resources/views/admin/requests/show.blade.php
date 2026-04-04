<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">تفاصيل الطلب رقم {{ $request->id }}</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6 bg-white dark:bg-gray-800 p-6 rounded shadow space-y-4">

        <div class="grid grid-cols-2 gap-4">
            <div><strong>رقم الخط:</strong> {{ $request->line->phone_number }}</div>
            <div><strong>نوع الطلب:</strong> {{ $request->request_type }}</div>
            <div><strong>الحالة:</strong> {{ $request->status }}</div>
            <div><strong>العميل:</strong> {{ $request->line->customer->full_name ?? '-' }}</div>
            <div><strong>الرقم القومي:</strong> {{ $request->line->customer->national_id ?? '-' }}</div>
            <div><strong>أنشئ بواسطة:</strong> {{ $request->requestedBy?->name ?? 'System' }}</div>
            <div><strong>تم بواسطة:</strong> {{ $request->doneBy?->name ?? '-' }}</div>
            <div><strong>تاريخ الإنشاء:</strong> {{ $request->created_at->format('Y-m-d H:i') }}</div>
        </div>

        <hr>

        {{-- التفاصيل حسب نوع الطلب --}}
        @if ($request->request_type === 'stop' && $request->stopDetails)
            <div>
                <h3 class="font-bold mb-2">🔻 تفاصيل طلب الإيقاف</h3>
                <p><strong>تاريخ آخر فاتورة:</strong> {{ $request->stopDetails->last_invoice_date }}</p>
            </div>
        @elseif ($request->request_type === 'resell' && $request->resellDetails)
            <div>
                <h3 class="font-bold mb-2">🔄 تفاصيل إعادة البيع</h3>
                <p><strong>النوع:</strong> {{ $request->resellDetails->resell_type === 'chip' ? 'على الشريحة' : 'في الفرع' }}</p>
                <p><strong>مسلسل قديم:</strong> {{ $request->resellDetails->old_serial ?? '-' }}</p>
                <p><strong>مسلسل جديد:</strong> {{ $request->resellDetails->new_serial ?? '-' }}</p>
                <p><strong>الاسم:</strong> {{ $request->resellDetails->full_name ?? '-' }}</p>
                <p><strong>الرقم القومي:</strong> {{ $request->resellDetails->national_id ?? '-' }}</p>
                <p><strong>تاريخ الطلب:</strong> {{ $request->resellDetails->request_date }}</p>
                <p><strong>ملاحظات:</strong> {{ $request->resellDetails->comment }}</p>
            </div>
        @elseif ($request->request_type === 'change_chip' && $request->changeChipDetails)
            <div>
                <h3 class="font-bold mb-2">🔁 تفاصيل تغيير الشريحة</h3>
                <p><strong>النوع:</strong> {{ $request->changeChipDetails->change_type === 'chip' ? 'على الشريحة' : 'في الفرع' }}</p>
                <p><strong>مسلسل قديم:</strong> {{ $request->changeChipDetails->old_serial ?? '-' }}</p>
                <p><strong>مسلسل جديد:</strong> {{ $request->changeChipDetails->new_serial ?? '-' }}</p>
                <p><strong>الاسم:</strong> {{ $request->changeChipDetails->full_name ?? '-' }}</p>
                <p><strong>الرقم القومي:</strong> {{ $request->changeChipDetails->national_id ?? '-' }}</p>
                <p><strong>تاريخ الطلب:</strong> {{ $request->changeChipDetails->request_date }}</p>
                <p><strong>ملاحظات:</strong> {{ $request->changeChipDetails->comment }}</p>
            </div>
        @elseif ($request->request_type === 'pause' && $request->pauseDetails)
            <div>
                <h3 class="font-bold mb-2">⏸️ تفاصيل الإيقاف المؤقت</h3>
                <p><strong>السبب:</strong> {{ $request->pauseDetails->reason }}</p>
                <p><strong>ملاحظات:</strong> {{ $request->pauseDetails->comment }}</p>
            </div>
        @elseif ($request->request_type === 'resume' && $request->resumeDetails)
            <div>
                <h3 class="font-bold mb-2">▶️ تفاصيل إعادة التشغيل</h3>
                <p><strong>السبب:</strong> {{ $request->resumeDetails->reason }}</p>
                <p><strong>ملاحظات:</strong> {{ $request->resumeDetails->comment }}</p>
            </div>
        @elseif ($request->request_type === 'change_plan' && $request->changePlanDetails)
            <div>
                <h3 class="font-bold mb-2">🔄 تفاصيل تغيير النظام</h3>
                <p><strong>النظام السابق:</strong> {{ $request->changePlanDetails->old_plan_name ?? '-' }}</p>
                <p><strong>النظام الجديد:</strong> {{ $request->changePlanDetails->newPlan->name ?? '-' }}</p>
                <p><strong>ملاحظات:</strong> {{ $request->changePlanDetails->comment ?? '-' }}</p>
            </div>
        @elseif ($request->request_type === 'change_distributor' && $request->changeDistributorDetails)
            <div>
                <h3 class="font-bold mb-2">🏬 تفاصيل تغيير الموزع</h3>
                <p><strong>من:</strong> {{ $request->changeDistributorDetails->old_distributor ?? '-' }}</p>
                <p><strong>إلى:</strong> {{ $request->changeDistributorDetails->new_distributor ?? '-' }}</p>
                <p><strong>ملاحظات:</strong> {{ $request->changeDistributorDetails->comment ?? '-' }}</p>
            </div>
        @elseif ($request->request_type === 'change_date' && $request->changeDateDetails)
            <div>
                <h3 class="font-bold mb-2">📅 تفاصيل تغيير تاريخ آخر فاتورة</h3>
                <p><strong>التاريخ السابق:</strong> {{ $request->changeDateDetails->old_date }}</p>
                <p><strong>التاريخ الجديد:</strong> {{ $request->changeDateDetails->new_date }}</p>
                <p><strong>ملاحظات:</strong> {{ $request->changeDateDetails->comment ?? '-' }}</p>
            </div>
        @else
            <p class="text-red-500">❗ لا توجد تفاصيل إضافية متاحة لهذا الطلب.</p>
        @endif
    </div>
</x-app-layout>
