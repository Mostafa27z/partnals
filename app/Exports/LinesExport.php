<?php

namespace App\Exports;

use App\Models\Line;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LinesExport implements FromCollection, WithHeadings, WithColumnFormatting, WithStyles
{
    public function collection()
    {
        return Line::with(['customer', 'plan', 'distributor'])
            ->get()
            ->map(function ($line) {
                return [
                    'رقم الهاتف'              => "\t" . $line->phone_number,
                    'المشغل / المزود'         => $line->provider,
                    'رقم المسلسل'             => "\t" . $line->serial_number,
                    'الباقة / النظام'         => $line->plan->name ?? '',
                    'تاريخ الربط'             => $line->attached_at ? $line->attached_at->format('Y-m-d H:i') : '',
                    'الموزع'                  => $line->distributor->name ?? '',
                    'الحالة'                  => $line->status,
                    'سعر البيع'               => $line->sale_price,
                    'سعر الشراء'              => $line->buy_price,
                    'تم البيع؟'               => $line->is_sold ? 'نعم' : 'لا',
                    'نوع النظام'              => $line->system_type,
                    'رقم الهاتف الثاني'       => "\t" . $line->second_phone,
                    'اسم العرض'               => $line->offer_name,
                    'اسم الفرع'               => $line->branch_name,
                    'اسم الموظف'              => $line->employee_name,
                    'كود المقدمة (GCode)'     => $line->gcode,
                    'نوع الخط'                => $line->line_type === 'prepaid' ? 'كارت' : 'فاتورة',
                    'حزمة الباقة'             => $line->package,
                    'تاريخ الدفع'             => $line->payment_date,
                    'تاريخ آخر فاتورة'        => $line->last_invoice_date,
                    'ملاحظات'                 => $line->notes,
                    'معروض للبيع؟'            => $line->for_sale ? 'نعم' : 'لا',
                    'تاريخ إنشاء الخط'        => $line->created_at ? $line->created_at->format('Y-m-d H:i') : '',
                    'تاريخ تحديث الخط'        => $line->updated_at ? $line->updated_at->format('Y-m-d H:i') : '',
                    'اسم العميل الكامل'       => $line->customer?->full_name ?? '',
                    'الرقم القومي للعميل'     => "\t" . ($line->customer?->national_id ?? ''),
                    'البريد الإلكتروني للعميل'=> $line->customer?->email ?? '',
                    'تاريخ ميلاد العميل'      => $line->customer?->birth_date ?? '',
                    'عنوان العميل'            => $line->customer?->address ?? '',
                    'رقم تواصل العميل'        => "\t" . ($line->customer?->contact_number ?? ''),
                    'رقم واتساب العميل'       => "\t" . ($line->customer?->whatsapp_number ?? ''),
                    'تاريخ إنشاء العميل'      => $line->customer?->created_at ? $line->customer->created_at->format('Y-m-d H:i') : '',
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

    public function columnFormats(): array
    {
        return [
            'A'  => NumberFormat::FORMAT_TEXT,  // رقم الهاتف
            'C'  => NumberFormat::FORMAT_TEXT,  // رقم المسلسل
            'L'  => NumberFormat::FORMAT_TEXT,  // رقم الهاتف الثاني
            'Z'  => NumberFormat::FORMAT_TEXT,  // الرقم القومي للعميل
            'AD' => NumberFormat::FORMAT_TEXT,  // رقم تواصل العميل
            'AE' => NumberFormat::FORMAT_TEXT,  // رقم واتساب العميل
        ];
    }

    public function styles(Worksheet $sheet): void
    {
        // Explicitly set explicit text format on every data cell in those columns
        $lastRow = $sheet->getHighestRow();

        foreach (['A', 'C', 'L', 'Z', 'AD', 'AE'] as $col) {
            foreach (range(2, $lastRow) as $row) {
                $sheet->getCell("{$col}{$row}")
                      ->getStyle()
                      ->getNumberFormat()
                      ->setFormatCode(NumberFormat::FORMAT_TEXT);
            }
        }
    }
}