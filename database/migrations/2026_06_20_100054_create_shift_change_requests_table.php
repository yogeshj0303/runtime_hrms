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
        Schema::create('shift_change_requests', function (Blueprint $table) {

    $table->id();

    $table->foreignId('business_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('employee_id')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->foreignId('current_shift_id')
        ->constrained('shifts')
        ->cascadeOnDelete();

    $table->foreignId('requested_shift_id')
        ->constrained('shifts')
        ->cascadeOnDelete();

    $table->date('effective_from');

    $table->text('reason')->nullable();

    $table->enum('status', [
        'pending',
        'approved',
        'rejected'
    ])->default('pending');

    $table->foreignId('approved_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->timestamp('approved_at')->nullable();

    $table->text('remarks')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_change_requests');
    }
};
