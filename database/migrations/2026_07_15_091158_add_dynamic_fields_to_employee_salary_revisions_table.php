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
        Schema::table('employee_salary_revisions', function (Blueprint $table) {
            $table->foreignId('salary_structure_id')->nullable()->constrained('salary_structures')->nullOnDelete();
            $table->json('custom_components')->nullable()->after('salary_options');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_salary_revisions', function (Blueprint $table) {
            $table->dropForeign(['salary_structure_id']);
            $table->dropColumn(['salary_structure_id', 'custom_components']);
        });
    }
};
