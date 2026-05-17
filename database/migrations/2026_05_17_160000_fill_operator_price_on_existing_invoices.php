<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Invoice;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fetch all invoices that have null or 0.00 operator price, and populate them with the plan's provider price if available
        $invoices = Invoice::with('line.plan')->where(function($query) {
            $query->whereNull('operator_price')
                  ->orWhere('operator_price', 0);
        })->get();

        foreach ($invoices as $invoice) {
            $line = $invoice->line;
            if ($line && $line->plan) {
                $providerPrice = (float) $line->plan->provider_price;
                $invoice->operator_price = $providerPrice;
                $invoice->calculated_profit = (float) $invoice->amount - $providerPrice;
                $invoice->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse action needed
    }
};
