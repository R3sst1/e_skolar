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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('siblings_boy')->nullable()->after('phone_number');
            $table->integer('siblings_girl')->nullable()->after('siblings_boy');
            $table->string('mother_maiden_name')->nullable()->after('siblings_girl');
            $table->string('father_name')->nullable()->after('mother_maiden_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['siblings_boy', 'siblings_girl', 'mother_maiden_name', 'father_name']);
        });
    }
};
