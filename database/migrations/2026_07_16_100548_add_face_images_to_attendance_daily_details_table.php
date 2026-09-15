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
        Schema::table('attendance_daily_details', function (Blueprint $table) {
            $table->string('punch_in_face')->nullable()->after('punch_in_longitude');
            $table->string('punch_out_face')->nullable()->after('punch_out_longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_daily_details', function (Blueprint $table) {
            $table->dropColumn(['punch_in_face', 'punch_out_face']);
        });
    }
};
