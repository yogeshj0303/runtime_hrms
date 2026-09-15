<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {

            $table->id();

            $table->string('table_name');
            $table->unsignedBigInteger('table_id')->nullable();

            $table->string('title');
            $table->longText('description')->nullable();

            $table->ipAddress('ip')->nullable();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_type')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};