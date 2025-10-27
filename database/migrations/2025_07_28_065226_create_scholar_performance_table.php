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
        Schema::create('scholar_performance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->constrained('scholars')->onDelete('cascade');
            $table->string('semester');
            $table->string('school_year');
            $table->decimal('gwa', 3, 2); // Grade Weighted Average
            $table->integer('units_enrolled');
            $table->integer('units_completed');
            $table->integer('units_failed');
            $table->integer('subjects_enrolled');
            $table->integer('subjects_passed');
            $table->integer('subjects_failed');
            $table->integer('subjects_dropped');
            $table->text('academic_remarks')->nullable();
            $table->string('academic_status')->default('good'); // good, warning, probation
            $table->boolean('meets_retention_requirements')->default(true);
            $table->date('submitted_at');
            $table->date('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamps();

            // Unique constraint to prevent duplicate entries per semester
            $table->unique(['scholar_id', 'semester', 'school_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholar_performance');
    }
};
