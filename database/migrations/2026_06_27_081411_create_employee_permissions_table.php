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
        Schema::create('employee_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->boolean('selfie_punch')->default(false);
            $table->boolean('face_recognition')->default(false);
            $table->boolean('web_login')->default(false);
            $table->boolean('travel')->default(false);
            $table->boolean('reward')->default(false);
            $table->boolean('remote_punch')->default(false);
            $table->boolean('wall_access')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_permissions');
    }
};
