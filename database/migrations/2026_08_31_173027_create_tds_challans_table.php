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
        Schema::create('tds_challans', function (Blueprint $table) {
            $table->id();
            $table->string('financial_year'); // e.g., "2026-27"
            $table->string('month'); // e.g., "APR-2026"
            $table->string('bsr_code')->nullable();
            $table->date('deposit_date')->nullable();
            $table->string('challan_serial')->nullable();
            $table->timestamps();

            $table->unique(['financial_year', 'month'], 'tds_challan_month_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tds_challans');
    }
};
