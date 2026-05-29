<?php

namespace App\Exports;

use App\Models\Plan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class PlansExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, WithCustomValueBinder
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

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Plan::filter($this->request)->get();
    }

    public function headings(): array
    {
        return [
            'ID (المسلسل)',
            'Name (اسم النظام)',
            'Price (السعر)',
            'Provider (المشغل)',
            'Provider Price (سعر المشغل)',
            'Type (النوع)',
            'Plan Code (كود النظام)',
            'Penalty (الغرامة)',
            'Created At (تاريخ الإنشاء)',
        ];
    }

    public function map($plan): array
    {
        return [
            $plan->id,
            $plan->name,
            $plan->price,
            $plan->provider,
            $plan->provider_price,
            $plan->type,
            $plan->plan_code,
            $plan->penalty,
            $plan->created_at ? $plan->created_at->format('Y-m-d H:i:s') : '',
        ];
    }
}
