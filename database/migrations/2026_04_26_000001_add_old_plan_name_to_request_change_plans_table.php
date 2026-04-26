<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('request_change_plans', function (Blueprint $table) {
            $table->string('old_plan_name')->nullable()->after('request_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_change_plans', function (Blueprint $table) {
            $table->dropColumn('old_plan_name');
        });
    }
};
