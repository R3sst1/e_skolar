<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('constituent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('office_id')->nullable()->constrained('offices')->nullOnDelete();
            $table->string('item_name')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('item_cost', 15, 2)->nullable();
            $table->decimal('requested_amount', 15, 2)->nullable();
            $table->text('description')->nullable();
            $table->text('purpose')->nullable();
            $table->string('barangay')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'disbursed'])->default('pending');
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('disbursement_batch_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};


