<?php

use App\Models\Line;
use Carbon\Carbon;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$month = 1;
$year = 2026;

$lines = Line::withoutGlobalScope('distributor')->where(function($q) {
    $q->whereNotNull('attached_at')->orWhere('is_sold', true);
})->get();

echo "DEBUG: Found " . $lines->count() . " potentially profitable lines.\n";

$total = 0;
foreach ($lines as $line) {
    try {
        $profit = $line->calculateProfit($month, $year);
        if ($profit != 0) {
            echo "Line: {$line->phone_number} | Profit: {$profit} | Plan: " . ($line->plan->name ?? 'N/A') . " | Attached: {$line->attached_at} | Sold: " . ($line->is_sold ? 'Yes' : 'No') . "\n";
            $total += $profit;
        }
    } catch (\Exception $e) {
        echo "Error calculating profit for line {$line->id}: " . $e->getMessage() . "\n";
    }
}

echo "Total Calculated Profit for CLI: $total\n";
