<?php

use App\Models\Line;
use Carbon\Carbon;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$month = 1;
$year = 2026;

$lines = Line::whereNotNull('attached_at')->orWhere('is_sold', true)->get();

echo "Profit breakdown for $month/$year:\n";
echo str_repeat("-", 50) . "\n";
$total = 0;

foreach ($lines as $line) {
    if ($line->is_sold) {
        $updatedAt = Carbon::parse($line->updated_at);
        if ($updatedAt->year == $year && $updatedAt->month == $month) {
            $profit = $line->calculateProfit($month, $year);
            echo "Line: " . $line->phone_number . " (SOLD) | Profit: " . $profit . "\n";
            $total += $profit;
        }
    } else {
        $attachedAt = Carbon::parse($line->attached_at);
        if ($attachedAt->year < $year || ($attachedAt->year == $year && $attachedAt->month <= $month)) {
            $profit = $line->calculateProfit($month, $year);
            if ($profit > 0) {
                $status = ($attachedAt->year == $year && $attachedAt->month == $month) ? "NEW ASSIGNMENT" : "RECURRING";
                echo "Line: " . $line->phone_number . " ($status) | Profit: $profit (Revenue: " . ($line->plan->price ?? 0) . " - Cost: " . ($line->plan->provider_price ?? 0) . ")\n";
                $total += $profit;
            }
        }
    }
}

echo str_repeat("-", 50) . "\n";
echo "Total Calculated Profit: $total\n";
