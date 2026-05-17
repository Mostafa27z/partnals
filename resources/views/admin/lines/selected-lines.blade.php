<table>
    <thead>
        <tr>
            <th>رقم الهاتف</th>
            <th>المشغل / المزود</th>
            <th>رقم المسلسل</th>
            <th>الباقة / النظام</th>
            <th>تاريخ الربط</th>
            <th>الموزع</th>
            <th>الحالة</th>
            <th>سعر البيع</th>
            <th>سعر الشراء</th>
            <th>تم البيع؟</th>
            <th>نوع النظام</th>
            <th>رقم الهاتف الثاني</th>
            <th>اسم العرض</th>
            <th>اسم الفرع</th>
            <th>اسم الموظف</th>
            <th>كود المقدمة (GCode)</th>
            <th>نوع الخط</th>
            <th>حزمة الباقة</th>
            <th>تاريخ الدفع</th>
            <th>تاريخ آخر فاتورة</th>
            <th>ملاحظات</th>
            <th>معروض للبيع؟</th>
            <th>تاريخ إنشاء الخط</th>
            <th>تاريخ تحديث الخط</th>
            <th>اسم العميل الكامل</th>
            <th>الرقم القومي للعميل</th>
            <th>البريد الإلكتروني للعميل</th>
            <th>تاريخ ميلاد العميل</th>
            <th>العنوان</th>
            <th>رقم تواصل العميل</th>
            <th>رقم واتساب العميل</th>
            <th>تاريخ إنشاء العميل</th>
        </tr>
    </thead>
    <tbody>
        @foreach($lines as $line)
            <tr>
                <td>{{ $line->phone_number }}</td>
                <td>{{ $line->provider }}</td>
                <td>{{ $line->serial_number }}</td>
                <td>{{ $line->plan->name ?? '-' }}</td>
                <td>{{ $line->attached_at ? $line->attached_at->format('Y-m-d H:i') : '-' }}</td>
                <td>{{ $line->distributor->name ?? '-' }}</td>
                <td>{{ $line->status }}</td>
                <td>{{ $line->sale_price }}</td>
                <td>{{ $line->buy_price }}</td>
                <td>{{ $line->is_sold ? 'نعم' : 'لا' }}</td>
                <td>{{ $line->system_type }}</td>
                <td>{{ $line->second_phone }}</td>
                <td>{{ $line->offer_name }}</td>
                <td>{{ $line->branch_name }}</td>
                <td>{{ $line->employee_name }}</td>
                <td>{{ $line->gcode }}</td>
                <td>{{ $line->line_type === 'prepaid' ? 'كارت' : 'فاتورة' }}</td>
                <td>{{ $line->package }}</td>
                <td>{{ $line->payment_date ? \Carbon\Carbon::parse($line->payment_date)->format('Y-m-d H:i') : '-' }}</td>
                <td>{{ $line->last_invoice_date }}</td>
                <td>{{ $line->notes }}</td>
                <td>{{ $line->for_sale ? 'نعم' : 'لا' }}</td>
                <td>{{ $line->created_at ? $line->created_at->format('Y-m-d H:i') : '-' }}</td>
                <td>{{ $line->updated_at ? $line->updated_at->format('Y-m-d H:i') : '-' }}</td>
                <td>{{ $line->customer->full_name ?? '-' }}</td>
                <td>{{ $line->customer->national_id ?? '-' }}</td>
                <td>{{ $line->customer->email ?? '-' }}</td>
                <td>{{ $line->customer->birth_date ?? '-' }}</td>
                <td>{{ $line->customer->address ?? '-' }}</td>
                <td>{{ $line->customer->contact_number ?? '-' }}</td>
                <td>{{ $line->customer->whatsapp_number ?? '-' }}</td>
                <td>{{ $line->customer->created_at ? $line->customer->created_at->format('Y-m-d H:i') : '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
