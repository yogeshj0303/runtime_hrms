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
        Schema::create('salary_deductions', function (Blueprint $table) {

    $table->id();

    $table->foreignId('business_id')
            ->constrained('businesses')
            ->cascadeOnDelete();

    $table->foreignId('auth_id')
            ->constrained('users')
            ->cascadeOnDelete();

    $table->string('name');

    $table->string('short_name');

    $table->enum('deduction_type',[

        'Fixed',
        'Variable',
        'Percent Based',
        'Strike Based',
        'Attendance Based',
        'Time Based'

    ]);

    $table->boolean('active')->default(true);

    $table->timestamps();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_deductions');
    }
};
