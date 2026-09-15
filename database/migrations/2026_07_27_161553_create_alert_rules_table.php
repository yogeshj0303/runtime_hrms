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
        Schema::create('alert_rules', function (Blueprint $table) {
            $table->id();
            $table->string('company')->nullable();
            $table->string('name');
            $table->string('category')->index(); // Employee, Attendance, Leave, Payroll, Compliance, Document
            $table->boolean('is_active')->default(true);
            $table->string('priority')->default('normal'); // critical, high, normal, low
            $table->integer('reminder_days_before')->default(0);
            $table->integer('repeat_frequency_days')->default(0);
            $table->integer('auto_close_days')->default(0); // 0 = never
            $table->boolean('escalation_enabled')->default(false);
            $table->date('effective_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->json('channels')->nullable(); // ["email", "sms", "dashboard"]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_rules');
    }
};
