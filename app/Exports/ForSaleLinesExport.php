<?php

namespace App\Exports;

use App\Models\Line;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class ForSaleLinesExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithCustomValueBinder
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
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
        $query = Line::with(['customer', 'plan', 'distributor'])
            ->where('for_sale', true)
            ->where('is_sold', false);

        if (!empty($this->filters['provider'])) {
            $query->where('provider', $this->filters['provider']);
        }

        if (!empty($this->filters['plan_id'])) {
            $query->where('plan_id', $this->filters['plan_id']);
        }

        return $query->orderBy('sale_price')->get()
            ->map(function ($line) {
                return [
                    'رقم الهاتف' => $line->phone_number,
                    'المشغل / المزود' => $line->provider,
                    'رقم المسلسل' => $line->serial_number,
                    'الباقة / النظام' => $line->plan->name ?? '',
                    'تاريخ الربط' => $line->attached_at ? $line->attached_at->format('Y-m-d H:i') : '',
                    'الموزع' => $line->distributor->name ?? '',
                    'الحالة' => $line->status,
                    'سعر البيع' => $line->sale_price,
                    'سعر الشراء' => $line->buy_price,
                    'تم البيع؟' => $line->is_sold ? 'نعم' : 'لا',
                    'نوع النظام' => $line->system_type,
                    'رقم الهاتف الثاني' => $line->second_phone,
                    'اسم العرض' => $line->offer_name,
                    'اسم الفرع' => $line->branch_name,
                    'اسم الموظف' => $line->employee_name,
                    'كود المقدمة (GCode)' => $line->gcode,
                    'نوع الخط' => $line->line_type === 'prepaid' ? 'كارت' : 'فاتورة',
                    'حزمة الباقة' => $line->package,
                    'تاريخ الدفع' => $line->payment_date,
                    'تاريخ آخر فاتورة' => $line->last_invoice_date,
                    'ملاحظات' => $line->notes,
                    'معروض للبيع؟' => $line->for_sale ? 'نعم' : 'لا',
                    'تاريخ إنشاء الخط' => $line->created_at ? $line->created_at->format('Y-m-d H:i') : '',
                    'تاريخ تحديث الخط' => $line->updated_at ? $line->updated_at->format('Y-m-d H:i') : '',
                    'اسم العميل الكامل' => $line->customer?->full_name ?? '',
                    'الرقم القومي للعميل' => $line->customer?->national_id ?? '',
                    'البريد الإلكتروني للعميل' => $line->customer?->email ?? '',
                    'تاريخ ميلاد العميل' => $line->customer?->birth_date ?? '',
                    'العنوان' => $line->customer?->address ?? '',
                    'رقم تواصل العميل' => $line->customer?->contact_number ?? '',
                    'رقم واتساب العميل' => $line->customer?->whatsapp_number ?? '',
                    'تاريخ إنشاء العميل' => $line->customer?->created_at ? $line->customer->created_at->format('Y-m-d H:i') : '',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'رقم الهاتف',
            'المشغل / المزود',
            'رقم المسلسل',
            'الباقة / النظام',
            'تاريخ الربط',
            'الموزع',
            'الحالة',
            'سعر البيع',
            'سعر الشراء',
            'تم البيع؟',
            'نوع النظام',
            'رقم الهاتف الثاني',
            'اسم العرض',
            'اسم الفرع',
            'اسم الموظف',
            'كود المقدمة (GCode)',
            'نوع الخط',
            'حزمة الباقة',
            'تاريخ الدفع',
            'تاريخ آخر فاتورة',
            'ملاحظات',
            'معروض للبيع؟',
            'تاريخ إنشاء الخط',
            'تاريخ تحديث الخط',
            'اسم العميل الكامل',
            'الرقم القومي للعميل',
            'البريد الإلكتروني للعميل',
            'تاريخ ميلاد العميل',
            'العنوان',
            'رقم تواصل العميل',
            'رقم واتساب العميل',
            'تاريخ إنشاء العميل',
        ];
    }
}
