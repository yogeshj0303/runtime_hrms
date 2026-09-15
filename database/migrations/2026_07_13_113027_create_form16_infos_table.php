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
        Schema::create('form16_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Section 1: Person Responsible for Deduction of Tax
            $table->string('full_name')->nullable();
            $table->string('designation')->nullable();
            $table->string('father_name')->nullable();
            $table->string('signature_image')->nullable();
            
            // Section 2: Employer Information
            $table->string('employer_name')->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('address_line_3')->nullable();
            $table->string('place_of_issue')->nullable();
            
            // Section 3: CIT (TDS) Information
            $table->string('cit_name')->nullable();
            $table->string('cit_address_line_1')->nullable();
            $table->string('cit_address_line_2')->nullable();
            $table->string('cit_address_line_3')->nullable();
            
            $table->timestamps();
            
            // Ensuring one record per business
            $table->unique('business_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form16_infos');
    }
};
