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
            // Add release status tracking
            if (!Schema::hasColumn('tbl_disbursement_batch_students', 'release_status')) {
                $table->enum('release_status', ['unreleased', 'released'])->default('unreleased')->after('status');
            }
            
            // Add released timestamp with timezone support
            if (!Schema::hasColumn('tbl_disbursement_batch_students', 'released_at')) {
                $table->timestamp('released_at')->nullable()->useCurrent()->after('release_status');
            }
            
            // Add release remarks
            if (!Schema::hasColumn('tbl_disbursement_batch_students', 'release_remarks')) {
                $table->text('release_remarks')->nullable()->after('released_at');
            }
            
            // Add actual amount field
            if (!Schema::hasColumn('tbl_disbursement_batch_students', 'actual_amount')) {
                $table->decimal('actual_amount', 12, 2)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_disbursement_batch_students', function (Blueprint $table) {
            $table->dropColumn(['release_status', 'released_at', 'release_remarks', 'actual_amount']);
        });
    }
};
