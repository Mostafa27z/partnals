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
        $tomorrow = Carbon::tomorrow()->toDateString();

        $lines = Line::whereDate('payment_date', '<=', $tomorrow)->get();

        foreach ($lines as $line) {
            // لا تنشئ الطلب مرتين إذا كان موجود بالفعل
            $exists = Request::hasActiveRequest($line->id, 'stop');

            if ($exists) continue;

            // 1. إنشاء سجل في جدول requests
            $request = Request::create([
                'customer_id'   => $line->customer_id,
                'line_id'       => $line->id,
                'request_type'  => 'stop',
                'status'        => 'pending',
            ]);

            // 2. إنشاء سجل في جدول request_stop_lines مربوط بنفس الـ request
            RequestStopLine::create([
                'request_id'        => $request->id,
                'last_invoice_date' => $line->last_invoice_date,
            ]);
        }

        // Auto deactivate lines whose active period has expired
        $expiredLines = Line::whereNotNull('last_invoice_date')
            ->where('status', 'active')
            ->get();

        $deactivatedCount = 0;
        foreach ($expiredLines as $line) {
            try {
                $lastInvoice = Carbon::parse($line->last_invoice_date);
                $expiryDate = $lastInvoice->copy()->addMonth();
                if (Carbon::now()->startOfDay()->gte($expiryDate->startOfDay())) {
                    $line->status = 'inactive';
                    $line->save();
                    $deactivatedCount++;
                }
            } catch (\Exception $e) {
                // Ignore parsing errors
            }
        }

        $this->info("✅ تم إنشاء الطلبات بنجاح. وتم إيقاف {$deactivatedCount} خطاً منتهية الصلاحية.");
    }
}
