<?php

namespace App\Exports;

use App\Models\Plan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PlansExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
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
