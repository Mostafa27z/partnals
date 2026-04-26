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
        Schema::table('request_change_distributors', function (Blueprint $table) {
            $table->dropColumn('old_distributor');
            $table->dropColumn('new_distributor');
            $table->foreignId('old_distributor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('new_distributor_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_change_distributors', function (Blueprint $table) {
            $table->dropForeign(['old_distributor_id']);
            $table->dropForeign(['new_distributor_id']);
            $table->dropColumn('old_distributor_id');
            $table->dropColumn('new_distributor_id');
            $table->string('old_distributor')->nullable();
            $table->string('new_distributor')->nullable();
        });
    }
};
