<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\Line;
use Illuminate\Support\Facades\Auth;

class ForSaleLinesImport implements ToCollection, WithStartRow
{
    public $errorsList = [];

    public function startRow(): int
    {
        return 2; // Skip header
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if (!isset($row[0]) || trim($row[0]) === '') {
                continue;
            }

            $phoneNumber = trim($row[0]);
            if (strlen($phoneNumber) === 10 && strpos($phoneNumber, '1') === 0) {
                $phoneNumber = '0' . $phoneNumber;
            }

            $buyPrice = isset($row[1]) && is_numeric($row[1]) ? (float)$row[1] : null;
            $salePrice = isset($row[2]) && is_numeric($row[2]) ? (float)$row[2] : null;

            if (!preg_match('/^\d{11}$/', $phoneNumber)) {
                $this->addError($row, 'رقم الهاتف يجب أن يكون 11 رقم');
                continue;
            }

            $gcode = substr($phoneNumber, 0, 3);
            if (!in_array($gcode, Line::allowedGcodes())) {
                $this->addError($row, 'مقدمة الرقم غير صحيحة (يجب أن تبدأ بـ 010, 011, 012, 015)');
                continue;
            }

            $line = Line::where('phone_number', $phoneNumber)->first();

            if ($line) {
                // If exists, update its prices and make it for sale
                $line->update([
                    'buy_price' => $buyPrice,
                    'sale_price' => $salePrice,
                    'for_sale' => true,
                    'is_sold' => false,
                ]);
            } else {
                // If not exists, create it
                $provider = Line::providerForGcode($gcode);

                Line::create([
                    'phone_number' => $phoneNumber,
                    'gcode' => $gcode,
                    'provider' => $provider,
                    'line_type' => 'prepaid',
                    'buy_price' => $buyPrice,
                    'sale_price' => $salePrice,
                    'for_sale' => true,
                    'is_sold' => false,
                    'added_by' => Auth::id() ?? 1,
                ]);
            }
        }
    }

    private function addError($row, $errorMessage)
    {
        $this->errorsList[] = [
            $row[0] ?? '',
            $row[1] ?? '',
            $row[2] ?? '',
            $errorMessage
        ];
    }
}
