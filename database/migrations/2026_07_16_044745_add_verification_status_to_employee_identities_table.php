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
        Schema::table('employee_identities', function (Blueprint $table) {
            $table->boolean('is_bank_verified')->default(false)->after('ifsc');
            $table->boolean('is_aadhaar_verified')->default(false)->after('aadhaar_number');
            $table->boolean('is_pan_verified')->default(false)->after('pan_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_identities', function (Blueprint $table) {
            $table->dropColumn(['is_bank_verified', 'is_aadhaar_verified', 'is_pan_verified']);
        });
    }
};
