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
        Schema::create('employee_it_declaration_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('financial_year', 20);
            $table->foreignId('it_declaration_item_id')->constrained('it_declaration_items')->onDelete('cascade');
            $table->decimal('declared_amount', 15, 2)->default(0);
            $table->decimal('verified_amount', 15, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->string('proof_path')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'financial_year', 'it_declaration_item_id'], 'emp_fy_item_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_it_declaration_details');
    }
};
