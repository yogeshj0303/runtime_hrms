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
        Schema::create('shift_policies', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('business_id');

            $table->string('name');
            $table->string('description')->nullable();
            
            $table->boolean('is_default')->default(false);

            $table->unsignedBigInteger('default_shift_id')->nullable();

            // Weekly Rotating Shifts
            $table->unsignedBigInteger('monday_shift_id')->nullable();
            $table->unsignedBigInteger('tuesday_shift_id')->nullable();
            $table->unsignedBigInteger('wednesday_shift_id')->nullable();
            $table->unsignedBigInteger('thursday_shift_id')->nullable();
            $table->unsignedBigInteger('friday_shift_id')->nullable();
            $table->unsignedBigInteger('saturday_shift_id')->nullable();
            $table->unsignedBigInteger('sunday_shift_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_policies');
    }
};
