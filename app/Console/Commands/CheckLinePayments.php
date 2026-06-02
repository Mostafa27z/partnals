<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Line;
use App\Models\Request;
use App\Models\RequestStopLine;
use Carbon\Carbon;

class CheckLinePayments extends Command
{
    protected $signature = 'lines:check-payments';
    protected $description = 'فحص الخطوط وإنشاء طلبات إيقاف قبل يوم الدفع بيوم';

public function handle()
{
    $today = Carbon::today();

    $lines = Line::whereNotNull('last_invoice_date')
        ->where('status', 'active')
        ->get();

    $createdCount = 0;

    foreach ($lines as $line) {

        try {

            $lastInvoiceDate = Carbon::parse($line->last_invoice_date);

            /*
             مثال:
             last_invoice_date = 2026-01-02
             triggerDate      = 2026-02-01
            */
            $triggerDate = $lastInvoiceDate
                ->copy()
                ->subDay();

            // إذا لم يمر الوقت المطلوب بعد
            if (!$today->gte($triggerDate)) {
                continue;
            }

            // منع التكرار
            if (Request::hasActiveRequest($line->id, 'stop')) {
                continue;
            }

            // إنشاء الطلب
            $request = Request::create([
                'customer_id'  => $line->customer_id,
                'line_id'      => $line->id,
                'request_type' => 'stop',
                'status'       => 'pending',
            ]);

            // إنشاء التفاصيل
            RequestStopLine::create([
                'request_id'        => $request->id,
                'last_invoice_date' => $line->last_invoice_date,
            ]);

            $createdCount++;

        } catch (\Exception $e) {

            \Log::error('Line payment check failed', [
                'line_id' => $line->id,
                'error'   => $e->getMessage(),
            ]);
        }
    }

    // إيقاف الخطوط بعد مرور شهر كامل
    $expiredLines = Line::whereNotNull('last_invoice_date')
        ->where('status', 'active')
        ->get();

    $deactivatedCount = 0;

    foreach ($expiredLines as $line) {

        try {

            $expiryDate = Carbon::parse($line->last_invoice_date);

            if ($today->gte($expiryDate)) {

                $line->status = 'inactive';
                $line->save();

                $deactivatedCount++;
            }

        } catch (\Exception $e) {
            //
        }
    }

    $this->info("✅ تم إنشاء {$createdCount} طلب إيقاف.");
    $this->info("✅ تم إيقاف {$deactivatedCount} خط منتهي.");
}
}
