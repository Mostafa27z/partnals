<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\Line;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class InvoicesImport implements ToCollection, WithStartRow
{
    public $errorsList = [];

    public function startRow(): int
    {
        return 2; // Skip header
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            // Check if row is empty
            if (!isset($row[0])) {
                continue;
            }

            $phoneNumber = trim($row[0]);
            if (strlen($phoneNumber) === 10 && strpos($phoneNumber, '1') === 0) {
                $phoneNumber = '0' . $phoneNumber;
            }

            $startMonth = str_pad($row[1], 2, '0', STR_PAD_LEFT);
            $year = $row[2];
            $numsOfMonths = (int) $row[3];
            $totalAmount = (float) $row[4];
            $totalCost = (float) $row[5];

            $line = Line::where('phone_number', $phoneNumber)->first();

            if (!$line) {
                $this->addError($row, 'الرقم غير موجود في النظام');
                continue;
            }

            if ($numsOfMonths <= 0) {
                $this->addError($row, 'عدد الشهور يجب أن يكون أكبر من 0');
                continue;
            }

            try {
                $requestedStartDate = Carbon::create($year, $startMonth, 1)->startOfMonth();
            } catch (\Exception $e) {
                $this->addError($row, 'تاريخ البداية (الشهر/السنة) غير صالح');
                continue;
            }

            // Expected Start Date Validation
            $latestInvoice = Invoice::where('line_id', $line->id)->where('is_paid', true)->orderBy('invoice_month', 'desc')->first();

            if ($latestInvoice) {
                $expectedStart = Carbon::parse($latestInvoice->invoice_month)->copy()->addMonth()->startOfMonth();
            } else if ($line->attached_at) {
                $expectedStart = Carbon::parse($line->attached_at)->startOfMonth();
            } else {
                $this->addError($row, 'الخط غير مربوط بعميل ولا توجد فواتير سابقة');
                continue;
            }

            if ($requestedStartDate->gt($expectedStart)) {
                $this->addError($row, 'توجد شهور سابقة غير مدفوعة (فجوة). متوقع: ' . $expectedStart->format('Y-m'));
                continue;
            }

            // Calculations
            $monthlyAmount = $totalAmount / $numsOfMonths;
            $monthlyCost = $totalCost / $numsOfMonths;
            $monthlyProfit = $monthlyAmount - $monthlyCost;

            $maxProcessedMonth = null;

            for ($i = 0; $i < $numsOfMonths; $i++) {
                $currentMonth = $requestedStartDate->copy()->addMonths($i);

                $existingInvoice = Invoice::where('line_id', $line->id)
                    ->where('invoice_month', $currentMonth->format('Y-m-d'))
                    ->first();

                if ($existingInvoice) {
                    // Update if already paid or exists
                    $existingInvoice->update([
                        'amount' => $monthlyAmount,
                        'operator_price' => $monthlyCost,
                        'calculated_profit' => $monthlyProfit,
                        'is_paid' => true,
                        'payment_date' => now(),
                    ]);
                } else {
                    // Create new
                    Invoice::create([
                        'line_id'           => $line->id,
                        'amount'            => $monthlyAmount,
                        'operator_price'    => $monthlyCost,
                        'calculated_profit' => $monthlyProfit,
                        'invoice_month'     => $currentMonth,
                        'is_paid'           => true,
                        'payment_date'      => now(),
                        'paid_by'           => Auth::id() ?? 1,
                        'notes'             => 'تم الإضافة من الإكسيل',
                    ]);
                }

                $maxProcessedMonth = $currentMonth;
            }

            // Update line's payment_date, and last invoice date if the newly added months exceed its current last date
            if ($maxProcessedMonth) {
                $currentLast = $line->last_invoice_date ? Carbon::parse($line->last_invoice_date) : Carbon::create(1900, 1, 1);
                $updateData = ['payment_date' => now()];
                if ($maxProcessedMonth->gt($currentLast)) {
                    $updateData['last_invoice_date'] = $maxProcessedMonth;
                }
                $line->update($updateData);
            }
        }
    }

    private function addError($row, $errorMessage)
    {
        $errorRow = [
            $row[0] ?? '',
            $row[1] ?? '',
            $row[2] ?? '',
            $row[3] ?? '',
            $row[4] ?? '',
            $row[5] ?? '',
            $errorMessage
        ];
        $this->errorsList[] = $errorRow;
    }
}
