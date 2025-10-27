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
            if (!Schema::hasColumn('tbl_disbursement_batch_students', 'student_id')) {
                $table->foreignId('student_id')->nullable()->constrained('scholars')->onDelete('cascade');
            }
        });

        // Normalize status enum to include disbursed (as used in code)
        Schema::table('tbl_disbursement_batch_students', function (Blueprint $table) {
            if (Schema::hasColumn('tbl_disbursement_batch_students', 'status')) {
                $table->dropColumn('status');
            }
        });

        Schema::table('tbl_disbursement_batch_students', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_disbursement_batch_students', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected', 'disbursed'])->default('pending');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_disbursement_batch_students', function (Blueprint $table) {
            if (Schema::hasColumn('tbl_disbursement_batch_students', 'student_id')) {
                $table->dropForeign(['student_id']);
                $table->dropColumn('student_id');
            }
        });

        Schema::table('tbl_disbursement_batch_students', function (Blueprint $table) {
            if (Schema::hasColumn('tbl_disbursement_batch_students', 'status')) {
                $table->dropColumn('status');
            }
        });

        Schema::table('tbl_disbursement_batch_students', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_disbursement_batch_students', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            }
        });
    }
};


