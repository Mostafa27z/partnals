<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class RequestsExport extends DefaultValueBinder implements FromView, WithCustomValueBinder
{
    protected $requests;

    public function __construct($requests)
    {
        $this->requests = $requests;
    }

    public function bindValue(Cell $cell, $value = null)
    {
        if (is_numeric($value) && strlen((string) $value) >= 7) {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);
            return true;
        }
        return parent::bindValue($cell, $value);
    }

    public function view(): View
    {
        return view('exports.requests', ['requests' => $this->requests]);
    }
}
