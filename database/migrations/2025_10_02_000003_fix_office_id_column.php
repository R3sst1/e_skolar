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
        // Check if table exists and add office_id column
        if (Schema::hasTable('allocation_logs')) {
            if (!Schema::hasColumn('allocation_logs', 'office_id')) {
                DB::statement('ALTER TABLE allocation_logs ADD COLUMN office_id BIGINT UNSIGNED DEFAULT 6 AFTER id');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('allocation_logs')) {
            if (Schema::hasColumn('allocation_logs', 'office_id')) {
                Schema::table('allocation_logs', function (Blueprint $table) {
                    $table->dropColumn('office_id');
                });
            }
        }
    }
};
