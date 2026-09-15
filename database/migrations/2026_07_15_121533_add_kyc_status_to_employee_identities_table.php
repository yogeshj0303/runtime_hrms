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
            $table->boolean('is_kyc_done')->default(false)->after('esi_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_identities', function (Blueprint $table) {
            $table->dropColumn('is_kyc_done');
        });
    }
};
