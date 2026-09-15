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
        Schema::create('employee_salary_unit_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_salary_unit_id');
            $table->decimal('quantity', 10, 2);
            $table->text('comment')->nullable();
            $table->date('capture_date')->nullable();
            $table->timestamps();

            $table->foreign('employee_salary_unit_id', 'emp_sal_unit_det_fk')
                  ->references('id')
                  ->on('employee_salary_units')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_salary_unit_details');
    }
};
