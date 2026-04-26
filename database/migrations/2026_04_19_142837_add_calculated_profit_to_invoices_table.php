<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('calculated_profit', 10, 2)->default(0)->after('amount');
        });

        // Update existing invoices with the profit logic based on current plan prices.
        // profit = invoice amount - provider_price
        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                UPDATE invoices i
                JOIN `lines` l ON i.line_id = l.id
                JOIN plans p ON l.plan_id = p.id
                SET i.calculated_profit = i.amount - p.provider_price
            ");
        }
    }

    public function down(): void {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('calculated_profit');
        });
    }
};
