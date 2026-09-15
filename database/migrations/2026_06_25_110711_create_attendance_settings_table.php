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
        Schema::create('attendance_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('business_id');

            $table->enum('default_attendance', ['Present', 'Absent'])->default('Present');
            $table->boolean('mark_out_every_second_punch')->default(false);
            $table->boolean('enable_manual_attendance')->default(false);

            $table->boolean('holiday_sandwich_rule')->default(false);
            $table->boolean('holiday_absent_both_days')->default(false);
            $table->boolean('holiday_restrict_one_day')->default(false);

            $table->boolean('week_off_sandwich_rule')->default(false);
            $table->boolean('week_off_absent_both_days')->default(false);
            $table->boolean('week_off_restrict_one_day')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_settings');
    }
};
