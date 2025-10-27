<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('renewal_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('renewal_id')->constrained('scholar_renewals')->onDelete('cascade');
            $table->string('document_type'); // grades, certificate, etc.
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_size');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('renewal_documents');
    }
}; 