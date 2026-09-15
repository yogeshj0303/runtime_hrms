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
        Schema::create('shift_rosters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id')->nullable();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->date('roster_date');
            $table->unsignedBigInteger('assigned_by')->nullable();
            $table->boolean('is_published')->default(true);
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->unique(['employee_id', 'roster_date']); // Only one shift per day for an employee
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_rosters');
    }
};
