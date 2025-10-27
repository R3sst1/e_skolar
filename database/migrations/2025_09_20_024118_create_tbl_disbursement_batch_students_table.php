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
        Schema::create('tbl_disbursement_batch_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disbursement_batch_id')->constrained('tbl_disbursement_batches')->onDelete('cascade');
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected', 'disbursed'])->default('pending');
            $table->decimal('requested_amount', 15, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();

            // Add indexes for foreign keys
            $table->index('disbursement_batch_id');
            $table->index('application_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_disbursement_batch_students');
    }
};