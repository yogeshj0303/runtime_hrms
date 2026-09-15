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
        Schema::create('ptax_slabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('state');
            $table->date('effective_date');
            $table->decimal('salary_from', 10, 2)->default(0);
            $table->decimal('salary_to', 10, 2)->nullable();
            $table->decimal('tax_amount', 8, 2)->default(0);
            $table->string('month')->default('all');
            $table->string('gender')->default('all');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ptax_slabs');
    }
};
