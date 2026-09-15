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
        Schema::create('employee_shift_histories', function (Blueprint $table) {

    $table->id();

    $table->unsignedBigInteger('business_id');

    $table->unsignedBigInteger('employee_id');

    $table->unsignedBigInteger('shift_id');

    $table->date('effective_from');

    $table->date('effective_to')->nullable();

    $table->boolean('is_active')->default(true);

    $table->unsignedBigInteger('assigned_by');

    $table->text('remarks')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_shift_histories');
    }
};
