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
        Schema::create('lwf_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('business_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('state')->nullable();
            $table->date('effective_from')->nullable();
            $table->string('employee_contribution_type')->nullable();
            $table->decimal('employee_contribution_amount', 12, 2)->nullable();
            $table->decimal('employee_contribution_rate', 8, 4)->nullable();
            $table->string('employer_contribution_type')->nullable();
            $table->decimal('employer_contribution_amount', 12, 2)->nullable();
            $table->decimal('employer_contribution_rate', 8, 4)->nullable();
            $table->decimal('salary_limit', 12, 2)->nullable();
            $table->string('deduction_frequency')->nullable();
            $table->string('deduction_month')->nullable();
            $table->string('calculation_method')->nullable();
            $table->string('rounding_method')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
            
            // Unique constraint as requested
            $table->unique(['business_id', 'effective_from', 'state'], 'lwf_settings_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lwf_settings');
    }
};
