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
        Schema::create('scholar_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholar_id')->constrained('scholars')->onDelete('cascade');
            $table->enum('category', ['academic', 'support', 'financial', 'general']);
            $table->integer('rating')->comment('1-5 star rating');
            $table->string('title');
            $table->text('message');
            $table->boolean('anonymous')->default(false);
            $table->enum('status', ['submitted', 'reviewed', 'resolved'])->default('submitted');
            $table->text('admin_response')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['scholar_id', 'created_at']);
            $table->index(['status', 'created_at']);
            $table->index(['category', 'rating']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholar_feedback');
    }
};
