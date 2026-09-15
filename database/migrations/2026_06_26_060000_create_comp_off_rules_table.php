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
        Schema::create('comp_off_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('business_id');

            // ── Weekly Off Section ────────────────────────────────────────
            $table->boolean('wo_auto_grant_comp_off')->default(false);
            $table->integer('wo_half_day_min_hours')->default(4);
            $table->integer('wo_half_day_min_minutes')->default(0);
            $table->integer('wo_full_day_min_hours')->default(8);
            $table->integer('wo_full_day_min_minutes')->default(0);
            $table->enum('wo_grant_comp_off', ['same_day', 'next_day', 'any_day'])->default('same_day');
            $table->boolean('wo_add_to_extra_days')->default(false);

            // ── Holiday Section ───────────────────────────────────────────
            $table->boolean('ho_auto_grant_comp_off')->default(false);
            $table->integer('ho_half_day_min_hours')->default(4);
            $table->integer('ho_half_day_min_minutes')->default(0);
            $table->integer('ho_full_day_min_hours')->default(8);
            $table->integer('ho_full_day_min_minutes')->default(0);
            $table->enum('ho_grant_comp_off', ['same_day', 'next_day', 'any_day'])->default('same_day');
            $table->boolean('ho_add_to_extra_days')->default(false);

            // ── Lapse Rules ───────────────────────────────────────────────
            $table->integer('lapse_after_days')->default(30);
            $table->boolean('lapse_at_month_end')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comp_off_rules');
    }
};
