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
        Schema::create('time_rules', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('business_id');

            $table->enum('time_basis', [
                'Late Coming', 
                'Early Going', 
                'Total Time In', 
                'Net Late', 
                'Early Coming', 
                'Late Going', 
                'Late Lunch'
            ]);

            $table->integer('from_hours')->default(0);
            $table->integer('from_minutes')->default(0);
            
            $table->integer('to_hours')->default(0);
            $table->integer('to_minutes')->default(0);

            $table->boolean('mark_only_if_present')->default(false);
            $table->boolean('is_active')->default(true);

            $table->string('warning_occurrences')->nullable();
            $table->string('warning_letter')->nullable();

            $table->string('attendance_occurrences')->nullable();
            $table->enum('attendance_status', [
                'Present',
                'Absent',
                'Holiday',
                'Week Off',
                'Comp Off',
                'Casual Leave',
                'Unpaid Leave',
                'Tour Training'
            ])->nullable();
            $table->enum('attendance_update_type', [
                'Full Day',
                'Half Day'
            ])->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_rules');
    }
};
