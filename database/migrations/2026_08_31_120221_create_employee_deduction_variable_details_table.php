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
        Schema::create('employee_deduction_variable_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_deduction_variable_id');
            $table->decimal('amount', 10, 2);
            $table->string('comments')->nullable();
            $table->timestamps();

            $table->foreign('employee_deduction_variable_id', 'fk_emp_ded_var_id')->references('id')->on('employee_deduction_variables')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_deduction_variable_details');
    }
};
