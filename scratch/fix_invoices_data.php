<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

echo "Starting data fix for invoices...\n";

// Update paid invoices where calculated_profit is 0
$updatedCount = DB::table('invoices as i')
    ->join('lines as l', 'i.line_id', '=', 'l.id')
    ->join('plans as p', 'l.plan_id', '=', 'p.id')
    ->where('i.is_paid', true)
    ->where('i.calculated_profit', 0)
    ->update([
        'i.calculated_profit' => DB::raw('i.amount - p.provider_price')
    ]);

echo "Successfully updated $updatedCount invoices.\n";
