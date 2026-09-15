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
        Schema::create('strike_rules', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('business_id');

            // Which attendance event triggers this strike
            $table->enum('rule_type', [
                'Early Coming',
                'Late Coming',
                'Early Going',
                'Late Going',
                'Late Lunch',
            ]);

            // Occurrence window: from occurrence # to occurrence #
            // to_occurrences = 0 means "onwards" (open-ended)
            $table->integer('from_occurrences')->default(1);
            $table->integer('to_occurrences')->default(0);

            // Visual strike severity colour
            $table->enum('strike_color', [
                'Yellow',
                'Orange',
                'Red',
                'Blue',
                'Green',
            ])->default('Yellow');

            // What action to take on strike
            $table->enum('deduction_type', [
                'None',
                'Fixed',
                'Per Day',
                'Per Hour',
                'Half Day',
                'Full Day',
            ])->default('None');

            $table->decimal('deduction_value', 8, 2)->nullable();

            // Optional warning letter to issue
            $table->string('warning_letter')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strike_rules');
    }
};
