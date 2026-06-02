<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Line;
use Carbon\Carbon;

class UpdateLineStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lines:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'تحديث حالة جميع الخطوط تلقائياً بناءً على تاريخ آخر فاتورة (نشط طوال الشهر ويصبح غير نشط بعد مرور شهر)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("🔄 جاري فحص وتحديث حالات الخطوط بناءً على تاريخ آخر فاتورة...");

        $lines = Line::whereNotNull('last_invoice_date')->get();
        $updatedCount = 0;
        $activeCount = 0;
        $inactiveCount = 0;

        $results = [];

        foreach ($lines as $line) {
            try {
                $lastInvoice = Carbon::parse($line->last_invoice_date);
                $expiryDate = $lastInvoice->copy();
                $oldStatus = $line->status;

                // إذا وصلنا أو تجاوزنا تاريخ انتهاء الصلاحية (بعد مرور شهر)، يصبح غير نشط
                if (Carbon::now()->startOfDay()->gte($expiryDate->startOfDay())) {
                    $newStatus = 'inactive';
                    $inactiveCount++;
                } else {
                    $newStatus = 'active';
                    $activeCount++;
                }

                if ($oldStatus !== $newStatus) {
                    $line->status = $newStatus;
                    $line->save();
                    $updatedCount++;

                    $results[] = [
                        'phone' => $line->phone_number,
                        'last_invoice' => $line->last_invoice_date,
                        'expiry' => $expiryDate->format('Y-m-d'),
                        'old_status' => $oldStatus,
                        'new_status' => $newStatus,
                    ];
                }
            } catch (\Exception $e) {
                $this->error("⚠️ فشل تحليل تاريخ الفاتورة للخط {$line->phone_number}: " . $e->getMessage());
            }
        }

        if (count($results) > 0) {
            $this->table(
                ['رقم الهاتف', 'تاريخ آخر فاتورة', 'تاريخ الانتهاء', 'الحالة السابقة', 'الحالة الجديدة'],
                $results
            );
            $this->info("✅ تم تحديث حالات {$updatedCount} خطوط بنجاح!");
        } else {
            $this->info("ℹ️ جميع حالات الخطوط متوافقة ومحدثة بالفعل مع تواريخ فواتيرها.");
        }

        $this->comment("📊 إحصائيات الخطوط التي تم فحصها: نشط ({$activeCount}) | غير نشط ({$inactiveCount})");
    }
}
