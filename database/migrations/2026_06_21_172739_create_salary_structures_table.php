<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_structures', function (Blueprint $table) {

            $table->id();

            $table->foreignId('business_id')
                  ->constrained('businesses')
                  ->cascadeOnDelete();

            $table->foreignId('auth_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->string('structure_name');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_structures');
    }
};