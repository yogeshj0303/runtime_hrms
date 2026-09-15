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
        Schema::create('leave_requests', function (Blueprint $table) {

    $table->id();

    $table->unsignedBigInteger('business_id');

    $table->unsignedBigInteger('employee_id');

    $table->unsignedBigInteger('leave_type_id');

    $table->date('from_date');

    $table->date('to_date');

    $table->integer('total_days')->default(1);

    $table->text('reason');

    $table->enum('status', [
        'pending',
        'approved',
        'rejected',
        'cancelled'
    ])->default('pending');

    $table->text('remarks')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
