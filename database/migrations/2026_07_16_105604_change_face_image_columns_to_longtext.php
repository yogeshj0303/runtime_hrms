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
            $table->longText('face_image')->nullable()->change();
        });

        Schema::table('attendance_daily_details', function (Blueprint $table) {
            $table->longText('punch_in_face')->nullable()->change();
            $table->longText('punch_out_face')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('face_image')->nullable()->change();
        });

        Schema::table('attendance_daily_details', function (Blueprint $table) {
            $table->string('punch_in_face')->nullable()->change();
            $table->string('punch_out_face')->nullable()->change();
        });
    }
};
