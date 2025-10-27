<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('tbl_scholarship_programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('eligibility_criteria')->nullable();
            $table->text('benefits')->nullable();
            $table->date('deadline')->nullable();
            $table->string('image')->nullable();

            // 🔽 New fields for Budgeted vs Unbudgeted
            $table->enum('type', ['budgeted', 'unbudgeted'])->default('unbudgeted');
            $table->decimal('allocated_budget', 12, 2)->nullable();   // for budgeted programs
            $table->decimal('per_scholar_amount', 12, 2)->nullable(); // per scholar allowance
            $table->boolean('auto_close')->default(false);            // auto close when limit reached

            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('tbl_scholarship_programs');
    }
};
