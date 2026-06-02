<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class InvoiceErrorsExport extends DefaultValueBinder implements FromArray, WithCustomValueBinder, WithColumnFormatting
{
    protected $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    public function bindValue(Cell $cell, $value = null)
    {
        $stringValue = (string) $value;
        if (is_numeric($value) && (strpos($stringValue, '0') === 0 || strlen($stringValue) >= 7)) {
            $cell->setValueExplicit($stringValue, DataType::TYPE_STRING);
            return true;
        }
        return parent::bindValue($cell, $value);
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function array(): array
    {
        return $this->errors;
    }
}

