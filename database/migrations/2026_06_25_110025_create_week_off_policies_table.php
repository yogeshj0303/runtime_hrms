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
        Schema::create('week_off_policies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('business_id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_payable')->default(true);

            $table->json('sunday_weeks')->nullable();
            $table->json('monday_weeks')->nullable();
            $table->json('tuesday_weeks')->nullable();
            $table->json('wednesday_weeks')->nullable();
            $table->json('thursday_weeks')->nullable();
            $table->json('friday_weeks')->nullable();
            $table->json('saturday_weeks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('week_off_policies');
    }
};
