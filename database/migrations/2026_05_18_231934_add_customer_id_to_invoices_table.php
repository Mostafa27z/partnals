<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->after('line_id')->constrained('customers')->onDelete('set null');
        });

        // Safe and fast data back-migration to populate existing invoices with their correct line's customer_id
        // We use backticks because 'lines' is a reserved keyword in MySQL/MariaDB
        DB::statement('UPDATE `invoices` SET `customer_id` = (SELECT `customer_id` FROM `lines` WHERE `lines`.`id` = `invoices`.`line_id`)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
        });
    }
};
