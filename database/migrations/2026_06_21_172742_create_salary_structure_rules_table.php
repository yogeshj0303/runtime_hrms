<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_structure_rules', function (Blueprint $table) {

            $table->id();

            $table->foreignId('salary_structure_id')
                  ->constrained('salary_structures')
                  ->cascadeOnDelete();

            $table->foreignId('business_id')
                  ->constrained('businesses')
                  ->cascadeOnDelete();

            $table->foreignId('auth_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('salary_component_id')
                  ->constrained('salary_components')
                  ->cascadeOnDelete();

            $table->integer('order_no')->default(1);

            $table->string('condition_component')->nullable();

            $table->enum('condition_operator', [
                '>',
                '<',
                '=',
                '>=',
                '<='
            ])->nullable();

            $table->decimal('condition_value',15,2)->nullable();

            $table->decimal('calculate_percentage',8,2)->default(0);

            $table->string('base_component')->nullable();

            $table->decimal('minimum_amount',15,2)->default(0);

            $table->decimal('maximum_amount',15,2)->default(0);

            $table->boolean('do_not_exceed_gross_salary')
                  ->default(false);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_structure_rules');
    }
};