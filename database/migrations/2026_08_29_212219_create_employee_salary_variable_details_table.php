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
        Schema::create('employee_salary_variable_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_salary_variable_id')->constrained('employee_salary_variables', 'id', 'emp_sal_var_details_var_id_foreign')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_salary_variable_details');
    }
};
