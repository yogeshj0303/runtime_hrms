<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_components', function (Blueprint $table) {

            $table->id();

            $table->foreignId('business_id')
                    ->constrained('businesses')
                    ->cascadeOnDelete();

            $table->foreignId('auth_id')
                    ->constrained('users')
                    ->cascadeOnDelete();

            $table->string('name');

            $table->string('short_name');

            $table->enum('unit_type', [
                'Paid Days',
                'Paid Hours',
                'Fixed Salary',
                'Daily Salary',
                'Rate Based',
                'Variable'
            ]);

            $table->boolean('active')->default(true);

            $table->boolean('exclude_from_gross_salary')->default(false);

            $table->boolean('hide_in_ctc_reports')->default(false);

            $table->boolean('not_payable')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_components');
    }
};