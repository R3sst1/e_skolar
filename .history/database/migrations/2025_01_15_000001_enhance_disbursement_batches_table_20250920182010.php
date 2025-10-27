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
            // Add scholarship_program_id foreign key
            $table->foreignId('scholarship_program_id')->nullable()->constrained('tbl_scholarship_programs')->onDelete('set null');
            
            // Add budget_allocated field
            $table->decimal('budget_allocated', 15, 2)->nullable();
            
            // Update status enum to include approved and rejected
            $table->dropColumn('status');
        });
        
        Schema::table('tbl_disbursement_batches', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected', 'disbursed'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_disbursement_batches', function (Blueprint $table) {
            $table->dropForeign(['scholarship_program_id']);
            $table->dropColumn(['scholarship_program_id', 'budget_allocated']);
            
            // Revert status enum
            $table->dropColumn('status');
        });
        
        Schema::table('tbl_disbursement_batches', function (Blueprint $table) {
            $table->enum('status', ['pending', 'reviewed', 'disbursed'])->default('pending');
        });
    }
};
