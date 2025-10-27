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
        Schema::table('tbl_disbursement_batches', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_disbursement_batches', 'scholarship_program_id')) {
                $table->foreignId('scholarship_program_id')->nullable()->constrained('tbl_scholarship_programs')->onDelete('set null');
            }

            if (!Schema::hasColumn('tbl_disbursement_batches', 'budget_allocated')) {
                $table->decimal('budget_allocated', 15, 2)->nullable();
            }
        });

        // Adjust status enum only if current column set differs
        Schema::table('tbl_disbursement_batches', function (Blueprint $table) {
            if (Schema::hasColumn('tbl_disbursement_batches', 'status')) {
                // Drop and recreate status to include approved/rejected
                $table->dropColumn('status');
            }
        });

        Schema::table('tbl_disbursement_batches', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_disbursement_batches', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected', 'disbursed'])->default('pending');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_disbursement_batches', function (Blueprint $table) {
            if (Schema::hasColumn('tbl_disbursement_batches', 'scholarship_program_id')) {
                $table->dropForeign(['scholarship_program_id']);
                $table->dropColumn('scholarship_program_id');
            }
            if (Schema::hasColumn('tbl_disbursement_batches', 'budget_allocated')) {
                $table->dropColumn('budget_allocated');
            }
        });

        Schema::table('tbl_disbursement_batches', function (Blueprint $table) {
            if (Schema::hasColumn('tbl_disbursement_batches', 'status')) {
                $table->dropColumn('status');
            }
        });

        Schema::table('tbl_disbursement_batches', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_disbursement_batches', 'status')) {
                $table->enum('status', ['pending', 'reviewed', 'disbursed'])->default('pending');
            }
        });
    }
};


