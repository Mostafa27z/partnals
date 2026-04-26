<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Request as RequestModel;
use App\Models\Line;
use App\Models\RequestChangePlan;
use App\Models\Plan;

// Get a change_plan request
$request = RequestModel::where('request_type', 'change_plan')->latest()->first();

if (!$request) {
    echo "No change_plan request found.\n";
    exit;
}

echo "Found request ID: {$request->id}, Status: {$request->status}\n";

$data = RequestChangePlan::where('request_id', $request->id)->first();
if (!$data) {
    echo "No RequestChangePlan found for this request.\n";
} else {
    echo "RequestChangePlan data: new_plan_id = {$data->new_plan_id}\n";
}

$line = $request->line;
echo "Current Line plan_id: {$line->plan_id}\n";

if ($data && $data->new_plan_id) {
    echo "Updating line...\n";
    $result = $line->update([
        'plan_id' => $data->new_plan_id,
    ]);
    echo "Update result: " . ($result ? "true" : "false") . "\n";
    
    // Refresh line to see if it persisted
    $line->refresh();
    echo "Line plan_id after refresh: {$line->plan_id}\n";
}
