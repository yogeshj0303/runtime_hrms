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
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'flight_risk_score')) {
                $table->integer('flight_risk_score')->nullable()->after('flight_risk_status');
            }
            if (!Schema::hasColumn('employees', 'last_risk_calculated_at')) {
                $table->timestamp('last_risk_calculated_at')->nullable()->after('flight_risk_score');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['flight_risk_score', 'last_risk_calculated_at']);
        });
    }
};
