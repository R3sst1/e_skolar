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
        Schema::table('scholar_renewals', function (Blueprint $table) {
            // These columns already exist in the original migration, so we don't need to add them again
            // $table->foreignId('scholar_id')->constrained()->onDelete('cascade');
            // $table->string('renewal_number')->unique();
            // $table->string('semester'); // First, Second, Summer
            // $table->string('school_year');
            // $table->decimal('gwa', 5, 2); // Grade Weighted Average
            // $table->string('academic_status'); // Good Standing, Probation, etc.
            // $table->text('academic_remarks')->nullable();
            // $table->text('admin_remarks')->nullable();
            // $table->timestamp('submitted_at')->nullable();
            // $table->timestamp('reviewed_at')->nullable();
            // $table->timestamp('approved_at')->nullable();
            // $table->timestamp('rejected_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No columns to drop since we're not adding any
    }
};
