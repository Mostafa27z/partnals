<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Line;
use App\Models\Invoice;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoiceErrorsExport;
use Carbon\Carbon;

echo "Starting creation of 3 sample Excel files...\n";

// Helper function to create Excel
function exportExcel($filename, $data) {
    Excel::store(new InvoiceErrorsExport($data), $filename, 'public');
    echo "Generated: public/storage/$filename\n";
}

// 1. SUCCESS (Adding normally)
// Find a line that has a plan and attached date
$lineAdd = Line::whereNotNull('attached_at')->whereHas('plan')->inRandomOrder()->first();
if ($lineAdd) {
    $plan = $lineAdd->plan;
    $startMonthDate = $lineAdd->last_invoice_date 
        ? Carbon::parse($lineAdd->last_invoice_date)->addMonth() 
        : Carbon::parse($lineAdd->attached_at);
        
    $dataAdd = [
        [
            $lineAdd->phone_number,
            str_pad($startMonthDate->month, 2, '0', STR_PAD_LEFT), // Start Month
            $startMonthDate->year, // Year
            '3', // Months Count
            (string)($plan->price * 3), // Total Amount
            (string)($plan->provider_price * 3), // Total Cost
        ]
    ];
    exportExcel('sample_1_success.xlsx', $dataAdd);
}

// 2. UPDATING (Overwriting existing invoice)
// Find a line that already has paid invoices
$invUpdate = Invoice::where('is_paid', true)->whereHas('line.plan')->inRandomOrder()->first();
if ($invUpdate) {
    $lineUpdate = $invUpdate->line;
    $plan = $lineUpdate->plan;
    $invoiceDate = Carbon::parse($invUpdate->invoice_month);
    
    $dataUpdate = [
        [
            $lineUpdate->phone_number,
            str_pad($invoiceDate->month, 2, '0', STR_PAD_LEFT),
            $invoiceDate->year,
            '1',
            (string)($plan->price + 50), // We added 50 artificially to show it updates
            (string)($plan->provider_price),
        ]
    ];
    exportExcel('sample_2_update.xlsx', $dataUpdate);
}

// 3. ERROR (Gap in months)
$lineError = Line::whereNotNull('attached_at')->whereHas('plan')->inRandomOrder()->first();
if ($lineError) {
    $plan = $lineError->plan;
    $startMonthDate = $lineError->last_invoice_date 
        ? Carbon::parse($lineError->last_invoice_date)->addMonths(4) // Jumping 4 months ahead = GAP ERROR
        : Carbon::parse($lineError->attached_at)->addMonths(4);
        
    $dataError = [
        [
            $lineError->phone_number,
            str_pad($startMonthDate->month, 2, '0', STR_PAD_LEFT),
            $startMonthDate->year,
            '2',
            (string)($plan->price * 2),
            (string)($plan->provider_price * 2),
        ]
    ];
    exportExcel('sample_3_error_gap.xlsx', $dataError);
}

echo "Done! The files are in the public/storage directory.\n";
