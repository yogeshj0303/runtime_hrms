<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('claim_components', function (Blueprint $table) {

            $table->id();

            $table->foreignId('business_id')
                    ->constrained('businesses')
                    ->cascadeOnDelete();

            $table->foreignId('auth_id')
                    ->constrained('users')
                    ->cascadeOnDelete();

            $table->string('name');

            $table->string('short_name')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claim_components');
    }
};
