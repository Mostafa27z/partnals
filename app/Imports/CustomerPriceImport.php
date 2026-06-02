<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\Line;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CustomerPriceImport implements ToCollection, WithStartRow
{
    public $errorsList = [];

    public function startRow(): int
    {
        return 2; // Skip header
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if (!isset($row[0])) continue;

            $phoneNumber = trim($row[0]);
            if (strlen($phoneNumber) === 10 && strpos($phoneNumber, '1') === 0) {
                $phoneNumber = '0' . $phoneNumber;
            }

            $customerPrice = (float) ($row[1] ?? 0);

            $line = Line::where('phone_number', $phoneNumber)->first();

            if (!$line) {
                $this->addError($row, 'الرقم غير موجود في النظام');
                continue;
            }

            $latestInvoice = Invoice::where('line_id', $line->id)->where('is_paid', true)->orderBy('invoice_month', 'desc')->first();
            if ($latestInvoice) {
                $invoiceMonth = Carbon::parse($latestInvoice->invoice_month)->copy()->addMonth()->startOfMonth();
            } else if ($line->attached_at) {
                $invoiceMonth = Carbon::parse($line->attached_at)->startOfMonth();
            } else {
                $invoiceMonth = now()->startOfMonth();
            }

            $invoice = Invoice::where('line_id', $line->id)
                ->where('invoice_month', $invoiceMonth->format('Y-m-d'))
                ->first();

            if ($invoice) {
                $updateData = [
                    'amount' => $customerPrice,
                    'is_paid' => true,
                    'payment_date' => now(),
                    'paid_by' => Auth::id() ?? 1,
                ];
                
                if ($invoice->operator_price > 0) {
                     $updateData['calculated_profit'] = $customerPrice - $invoice->operator_price;
                }

                $invoice->update($updateData);
            } else {
                // Create new paid invoice
                $opPrice = optional($line->plan)->provider_price ?? 0;
                Invoice::create([
                    'line_id'           => $line->id,
                    'amount'            => $customerPrice,
                    'operator_price'    => $opPrice,
                    'calculated_profit' => $customerPrice - $opPrice,
                    'invoice_month'     => $invoiceMonth,
                    'is_paid'           => true,
                    'payment_date'      => now(),
                    'paid_by'           => Auth::id() ?? 1,
                    'notes'             => 'تم إضافة سعر العميل من الإكسيل',
                ]);
            }
            
            // Update line's last_invoice_date if this is newer
            $currentLast = $line->last_invoice_date ? Carbon::parse($line->last_invoice_date) : Carbon::create(1900, 1, 1);
            if ($invoiceMonth->gt($currentLast)) {
                $line->update(['last_invoice_date' => $invoiceMonth, 'payment_date' => now(), 'for_sale' => 0]);
            }
        }
    }

    private function addError($row, $errorMessage)
    {
        $this->errorsList[] = [
            $row[0] ?? '',
            $row[1] ?? '',
            $errorMessage
        ];
    }
}
