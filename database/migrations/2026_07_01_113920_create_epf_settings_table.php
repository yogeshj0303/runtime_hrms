<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('epf_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('business_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('is_enabled')->default(false);
            $table->date('effective_from');
            $table->decimal('employee_contribution_rate', 8, 2)->default(0);
            $table->decimal('employer_contribution_rate', 8, 2)->default(0);
            $table->decimal('pension_contribution_rate', 8, 2)->default(0);
            $table->decimal('edli_contribution_rate', 8, 2)->default(0);
            $table->decimal('admin_charges_rate', 8, 2)->default(0);
            $table->decimal('wage_ceiling', 10, 2)->default(0);
            $table->integer('senior_citizen_age')->nullable();
            $table->decimal('senior_employee_contribution_rate', 8, 2)->nullable();
            $table->decimal('senior_employer_contribution_rate', 8, 2)->nullable();
            $table->decimal('senior_pension_contribution_rate', 8, 2)->nullable();
            $table->string('calculation_method')->nullable();
            $table->string('rounding_method')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('epf_settings');
    }
};
