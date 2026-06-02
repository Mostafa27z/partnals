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

            $numsOfMonths = (int) ($row[1] ?? 0);
            $totalAmount = (float) ($row[2] ?? 0);
            $totalCost = (float) ($row[3] ?? 0);

            $line = Line::where('phone_number', $phoneNumber)->first();

            if (!$line) {
                $this->addError($row, 'الرقم غير موجود في النظام');
                continue;
            }

            if ($numsOfMonths <= 0) {
                $this->addError($row, 'عدد الشهور يجب أن يكون أكبر من 0');
                continue;
            }

            // Expected Start Date Validation
            $latestInvoice = Invoice::where('line_id', $line->id)->where('is_paid', true)->orderBy('invoice_month', 'desc')->first();

            if ($latestInvoice) {
                $requestedStartDate = Carbon::parse($latestInvoice->invoice_month)->copy()->addMonth()->startOfMonth();
            } else if ($line->attached_at) {
                $requestedStartDate = Carbon::parse($line->attached_at)->startOfMonth();
            } else {
                $requestedStartDate = now()->startOfMonth();
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

                // Check and create resume request if new last invoice date is in the future
                $resumeExists = \App\Models\Request::where('line_id', $line->id)
                    ->where('request_type', 'resume')
                    ->whereDate('created_at', now()->toDateString())
                    ->exists();

                if ($maxProcessedMonth->greaterThan(now()) && !$resumeExists) {
                    $resumeRequest = \App\Models\Request::create([
                        'line_id'      => $line->id,
                        'customer_id'  => $line->customer_id,
                        'request_type' => 'resume',
                        'status'       => 'pending',
                        'requested_by' => Auth::id() ?? 1,
                    ]);

                    \App\Models\RequestResumeLine::create([
                        'request_id' => $resumeRequest->id,
                        'reason'     => 'تم دفع الفاتورة',
                        'comment'    => 'تم إنشاء الطلب بواسطة النظام تلقائياً عبر استيراد الإكسيل',
                    ]);
                }
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
            $errorMessage
        ];
        $this->errorsList[] = $errorRow;
    }
}
