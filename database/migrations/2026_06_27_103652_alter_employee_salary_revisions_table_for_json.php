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
            $table->json('allowances')->nullable()->after('hra');
            $table->json('contributions')->nullable()->after('net_salary');
            $table->json('salary_options')->nullable()->after('ctc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_salary_revisions', function (Blueprint $table) {
            $table->dropColumn(['allowances', 'contributions', 'salary_options']);
        });
    }
};
