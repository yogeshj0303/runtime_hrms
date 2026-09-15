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
        Schema::create('employee_extra_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('payroll_month'); // Format: YYYY-MM
            $table->decimal('extra_days', 8, 2)->default(0);
            $table->decimal('arrear_days', 8, 2)->default(0);
            $table->decimal('ot_days', 8, 2)->default(0);
            $table->string('comments')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            
            // Ensure one entry per employee per month
            $table->unique(['employee_id', 'payroll_month'], 'emp_extra_days_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_extra_days');
    }
};
