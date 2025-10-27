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
        Schema::table('tbl_disbursement_batch_students', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_disbursement_batch_students', 'requested_amount')) {
                $table->decimal('requested_amount', 15, 2)->default(0)->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_disbursement_batch_students', function (Blueprint $table) {
            if (Schema::hasColumn('tbl_disbursement_batch_students', 'requested_amount')) {
                $table->dropColumn('requested_amount');
            }
        });
    }
};
