<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class CustomersExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithCustomValueBinder
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function bindValue(Cell $cell, $value = null)
    {
        if (is_numeric($value) && strlen((string) $value) >= 7) {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);
            return true;
        }
        return parent::bindValue($cell, $value);
    }

    public function collection()
    {
        return Customer::filter($this->request)
            ->with(['lines.plan', 'lines.distributor'])
            ->get()
            ->map(function ($customer) {
                $firstLine = $customer->lines->first();

                return [
                    // Customer Data
                    'الاسم الكامل'      => $customer->full_name,
                    'الرقم القومي'      => $customer->national_id,
                    'البريد الإلكتروني' => $customer->email,
                    'تاريخ الميلاد'     => $customer->birth_date,
                    'العنوان'           => $customer->address,
                    'رقم التواصل'       => $customer->contact_number,
                    'رقم الواتساب'      => $customer->whatsapp_number,
                    'تاريخ الإضافة'     => $customer->created_at ? $customer->created_at->format('Y-m-d H:i') : '',
                    'تاريخ التحديث'     => $customer->updated_at ? $customer->updated_at->format('Y-m-d H:i') : '',

                    // First Line Data
                    'رقم خط الهاتف الأول'          => $firstLine?->phone_number ?? '',
                    'مشغل خط الهاتف الأول'         => $firstLine?->provider ?? '',
                    'رقم مسلسل خط الهاتف الأول'     => $firstLine?->serial_number ?? '',
                    'باقة خط الهاتف الأول'         => $firstLine?->plan?->name ?? '',
                    'تاريخ ربط خط الهاتف الأول'     => $firstLine && $firstLine->attached_at ? $firstLine->attached_at->format('Y-m-d H:i') : '',
                    'موزع خط الهاتف الأول'         => $firstLine?->distributor?->name ?? '',
                    'حالة خط الهاتف الأول'         => $firstLine?->status ?? '',
                    'سعر بيع خط الهاتف الأول'      => $firstLine?->sale_price ?? '',
                    'سعر شراء خط الهاتف الأول'     => $firstLine?->buy_price ?? '',
                    'هل تم بيع خط الهاتف الأول؟'    => $firstLine ? ($firstLine->is_sold ? 'نعم' : 'لا') : '',
                    'نوع نظام خط الهاتف الأول'      => $firstLine?->system_type ?? '',
                    'رقم هاتف إضافي للخط الأول'    => $firstLine?->second_phone ?? '',
                    'اسم عرض الخط الأول'           => $firstLine?->offer_name ?? '',
                    'اسم فرع الخط الأول'            => $firstLine?->branch_name ?? '',
                    'اسم موظف الخط الأول'           => $firstLine?->employee_name ?? '',
                    'كود مقدمة الخط الأول (GCode)' => $firstLine?->gcode ?? '',
                    'نوع الخط الأول'               => $firstLine ? ($firstLine->line_type === 'prepaid' ? 'كارت' : 'فاتورة') : '',
                    'حزمة باقة الخط الأول'          => $firstLine?->package ?? '',
                    'تاريخ دفع الخط الأول'          => $firstLine?->payment_date ?? '',
                    'تاريخ آخر فاتورة للخط الأول'   => $firstLine?->last_invoice_date ?? '',
                    'ملاحظات الخط الأول'            => $firstLine?->notes ?? '',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'الاسم الكامل',
            'الرقم القومي',
            'البريد الإلكتروني',
            'تاريخ الميلاد',
            'العنوان',
            'رقم التواصل',
            'رقم الواتساب',
            'تاريخ الإضافة',
            'تاريخ التحديث',
            'رقم خط الهاتف الأول',
            'مشغل خط الهاتف الأول',
            'رقم مسلسل خط الهاتف الأول',
            'باقة خط الهاتف الأول',
            'تاريخ ربط خط الهاتف الأول',
            'موزع خط الهاتف الأول',
            'حالة خط الهاتف الأول',
            'سعر بيع خط الهاتف الأول',
            'سعر شراء خط الهاتف الأول',
            'هل تم بيع خط الهاتف الأول؟',
            'نوع نظام خط الهاتف الأول',
            'رقم هاتف إضافي للخط الأول',
            'اسم عرض الخط الأول',
            'اسم فرع الخط الأول',
            'اسم موظف الخط الأول',
            'كود مقدمة الخط الأول (GCode)',
            'نوع الخط الأول',
            'حزمة باقة الخط الأول',
            'تاريخ دفع الخط الأول',
            'تاريخ آخر فاتورة للخط الأول',
            'ملاحظات الخط الأول',
        ];
    }
}
