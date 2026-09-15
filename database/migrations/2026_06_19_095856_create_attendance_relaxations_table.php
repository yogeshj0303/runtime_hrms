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
        Schema::create('attendance_relaxations', function (Blueprint $table) {

    $table->id();

    $table->unsignedBigInteger('business_id');

    $table->unsignedBigInteger('employee_id');

    $table->unsignedBigInteger('shift_id')->nullable();

    $table->date('attendance_date');

    $table->integer('relaxation_minutes');

    $table->string('reason');

    $table->enum(
        'status',
        [
            'pending',
            'approved',
            'rejected'
        ]
    )->default('pending');

    $table->unsignedBigInteger('approved_by')
          ->nullable();

    $table->timestamp('approved_at')
          ->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_relaxations');
    }
};
