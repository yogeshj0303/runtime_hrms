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
        Schema::create('manual_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->string('month_year', 7); // Format: YYYY-MM
            $table->decimal('present_days', 4, 1)->default(0);
            $table->decimal('absent_days', 4, 1)->default(0);
            $table->decimal('holiday_days', 4, 1)->default(0);
            $table->decimal('week_off_days', 4, 1)->default(0);
            $table->json('leave_days')->nullable(); // Store JSON mapping for specific leave types
            $table->timestamps();
            
            $table->unique(['employee_id', 'month_year']); // Ensure one manual record per employee per month
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manual_attendances');
    }
};
