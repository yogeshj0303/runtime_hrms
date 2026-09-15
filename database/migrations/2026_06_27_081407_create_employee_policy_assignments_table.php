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
        Schema::create('employee_policy_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->unsignedBigInteger('shift_policy_id')->nullable();
            $table->unsignedBigInteger('weekoff_policy_id')->nullable();
            $table->unsignedBigInteger('overtime_policy_id')->nullable();
            $table->unsignedBigInteger('leave_policy_id')->nullable();
            $table->date('effective_from')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_policy_assignments');
    }
};
