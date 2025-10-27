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
        Schema::create('allocation_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('office_id')->default(6); // Scholarship Office ID
            $table->foreignId('allocated_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('disbursement_batch_id')->nullable()->constrained('tbl_disbursement_batches')->onDelete('cascade');
            $table->enum('transaction_type', ['allocation', 'disbursement', 'adjustment'])->default('disbursement');
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->string('reference_number')->nullable(); // For tracking purposes
            $table->timestamps();
            
            $table->index(['office_id', 'transaction_type']);
            $table->index(['allocated_by', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allocation_logs');
    }
};
