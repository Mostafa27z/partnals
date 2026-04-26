<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoiceErrorsExport;

function exportExample($filename, $data) {
    Excel::store(new InvoiceErrorsExport($data), $filename, 'public');
    echo "Generated: public/storage/$filename\n";
}

// Example values
$phone = '25899911'; 

$samples = [
    'stop_requests' => [
        ['رقم الهاتف', 'السبب', 'ملاحظات'],
        [$phone, 'إيقاف نهائي', 'تم تسوية الحساب']
    ],
    'resume_requests' => [
        ['رقم الهاتف', 'السبب', 'ملاحظات'],
        [$phone, 'عودة العميل من السفر', 'نرجو التفعيل السريع']
    ],
    'pause_requests' => [
        ['رقم الهاتف', 'السبب', 'ملاحظات'],
        [$phone, 'طلب العميل الإيقاف المؤقت', 'لا يوجد ملاحظات']
    ],
    'change_date_requests' => [
        ['رقم الهاتف', 'التاريخ الجديد YYYY-MM-DD', 'السبب', 'ملاحظات'],
        [$phone, '2026-05-15', 'تغيير موعد الفوترة', 'طلب العميل']
    ],
    'change_distributor_requests' => [
        ['رقم الهاتف', 'اسم الموزع الجديد', 'السبب', 'ملاحظات'],
        [$phone, 'موزع مثال', 'تغيير بناء على طلب العميل', 'ملاحظة إضافية']
    ],
    'change_chip_requests' => [
        ['رقم الهاتف', 'السيريال الجديد', 'السبب', 'ملاحظات'],
        [$phone, '987654321098765', 'تغيير شريحة للايفون', 'لا يوجد']
    ],
    'change_plan_requests' => [
        ['رقم الهاتف', 'اسم النظام الجديد', 'السبب', 'ملاحظات'],
        [$phone, 'باقة 100 جيجا', 'ترقية الباقة', 'باقة اكبر']
    ],
    'resell_requests' => [
        ['رقم الهاتف', 'النوع', 'السيريال القديم', 'السيريال الجديد', 'ملاحظات'],
        [$phone, 'chip', '123456789', '987654321', 'بيع لمستفيد جديد']
    ],
];

echo "Generating Sample Excels for Bulk Requests...\n";

foreach ($samples as $key => $data) {
    exportExample('sample_' . $key . '.xlsx', $data);
}

echo "Done! All files are in the storage directory.\n";
