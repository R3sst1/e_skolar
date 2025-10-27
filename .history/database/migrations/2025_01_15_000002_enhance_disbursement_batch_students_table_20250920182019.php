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
            // Add student_id foreign key to scholars table
            $table->foreignId('student_id')->nullable()->constrained('scholars')->onDelete('cascade');
            
            // Update status enum to match new requirements
            $table->dropColumn('status');
        });
        
        Schema::table('tbl_disbursement_batch_students', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_disbursement_batch_students', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropColumn('student_id');
            
            // Revert status enum
            $table->dropColumn('status');
        });
        
        Schema::table('tbl_disbursement_batch_students', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected', 'disbursed'])->default('pending');
        });
    }
};
