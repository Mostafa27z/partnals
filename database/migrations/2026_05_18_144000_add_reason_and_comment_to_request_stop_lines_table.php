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
        Schema::table('request_stop_lines', function (Blueprint $table) {
            $table->string('reason')->nullable()->after('request_id');
            $table->text('comment')->nullable()->after('reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_stop_lines', function (Blueprint $table) {
            $table->dropColumn(['reason', 'comment']);
        });
    }
};
