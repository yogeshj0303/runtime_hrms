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
        Schema::create('tds_returns', function (Blueprint $table) {
            $table->id();
            $table->string('financial_year'); // e.g., "2026-27"
            $table->string('quarter'); // e.g., "Q1"
            $table->string('receipt_number')->nullable();
            $table->timestamps();

            $table->unique(['financial_year', 'quarter'], 'tds_returns_quarter_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tds_returns');
    }
};
