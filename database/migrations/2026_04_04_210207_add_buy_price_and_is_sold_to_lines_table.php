<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lines', function (Blueprint $table) {
            $table->decimal('buy_price', 15, 2)->nullable()->after('for_sale');
            $table->boolean('is_sold')->default(false)->after('sale_price');
        });
    }

    public function down(): void
    {
        Schema::table('lines', function (Blueprint $table) {
            $table->dropColumn(['buy_price', 'is_sold']);
        });
    }
};
