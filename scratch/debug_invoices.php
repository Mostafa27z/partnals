<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Invoice;

$invoices = Invoice::where('line_id', 10)->orderBy('id', 'desc')->take(5)->get();
foreach ($invoices as $inv) {
    echo "ID: " . $inv->id . "\n";
    echo "Amount: " . $inv->amount . "\n";
    echo "Calculated Profit: " . $inv->calculated_profit . "\n";
    echo "Line ID: " . $inv->line_id . "\n";
    if ($inv->line) {
        echo "  Plan ID: " . ($inv->line->plan_id ?? 'NULL') . "\n";
        if ($inv->line->plan) {
            echo "  Provider Price: " . $inv->line->plan->provider_price . "\n";
            echo "  Calc: " . ($inv->amount - $inv->line->plan->provider_price) . "\n";
        } else {
            echo "  Plan NOT FOUND\n";
        }
    } else {
        echo "  Line NOT FOUND\n";
    }
    echo "-------------------\n";
}
