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
        Schema::table('onboarding_forms', function (Blueprint $table) {
            $table->string('token')->nullable()->after('status')->unique();
            $table->json('part_a_data')->nullable(); // Name, Email, Mobile, PAN, Aadhaar, Bank
            $table->json('part_b_data')->nullable(); // Attached policies
            $table->json('part_c_data')->nullable(); // Salary structure, offer letter
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('onboarding_forms', function (Blueprint $table) {
            $table->dropColumn(['token', 'part_a_data', 'part_b_data', 'part_c_data']);
        });
    }
};
