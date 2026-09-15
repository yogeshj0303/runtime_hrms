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
        Schema::create('alert_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company')->nullable();
            $table->boolean('enable_dashboard')->default(true);
            $table->boolean('enable_email')->default(true);
            $table->boolean('enable_sms')->default(false);
            $table->boolean('enable_whatsapp')->default(false);
            $table->boolean('enable_push')->default(false);
            $table->string('escalation_manager_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_settings');
    }
};
