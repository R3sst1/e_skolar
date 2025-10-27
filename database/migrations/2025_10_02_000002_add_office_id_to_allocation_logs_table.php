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
        Schema::table('allocation_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('allocation_logs', 'office_id')) {
                $table->unsignedBigInteger('office_id')->default(6)->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('allocation_logs', function (Blueprint $table) {
            if (Schema::hasColumn('allocation_logs', 'office_id')) {
                $table->dropColumn('office_id');
            }
        });
    }
};
