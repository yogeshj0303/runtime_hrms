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
        Schema::create('tds_24q_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('user_id');

            // General Info
            $table->string('deductor_type')->nullable();
            $table->string('section_code')->nullable();
            $table->string('state')->nullable();
            $table->string('ministry')->nullable();
            $table->string('ministry_name')->nullable();
            $table->string('ain_number')->nullable();
            $table->string('pao_code')->nullable();
            $table->string('pao_registration_number')->nullable();
            $table->string('ddo_code')->nullable();
            $table->string('ddo_registration_number')->nullable();

            // Employer Details
            $table->string('employer_name')->nullable();
            $table->string('branch_division')->nullable();
            $table->string('emp_address_line_1')->nullable();
            $table->string('emp_address_line_2')->nullable();
            $table->string('emp_address_line_3')->nullable();
            $table->string('emp_address_line_4')->nullable();
            $table->string('emp_address_line_5')->nullable();
            $table->string('emp_state')->nullable();
            $table->string('emp_pin')->nullable();
            $table->string('emp_pan')->nullable();
            $table->string('emp_tan')->nullable();
            $table->string('emp_email')->nullable();
            $table->string('emp_std_code')->nullable();
            $table->string('emp_phone')->nullable();
            $table->string('emp_alternate_email')->nullable();
            $table->string('emp_alternate_std_code')->nullable();
            $table->string('emp_alternate_phone')->nullable();
            $table->string('emp_gst_number')->nullable();

            // Responsible Person Details
            $table->string('resp_name')->nullable();
            $table->string('resp_designation')->nullable();
            $table->string('resp_address_line_1')->nullable();
            $table->string('resp_address_line_2')->nullable();
            $table->string('resp_address_line_3')->nullable();
            $table->string('resp_address_line_4')->nullable();
            $table->string('resp_address_line_5')->nullable();
            $table->string('resp_state')->nullable();
            $table->string('resp_pin')->nullable();
            $table->string('resp_pan')->nullable();
            $table->string('resp_mobile')->nullable();
            $table->string('resp_email')->nullable();
            $table->string('resp_std_code')->nullable();
            $table->string('resp_phone')->nullable();
            $table->string('resp_alternate_email')->nullable();
            $table->string('resp_alternate_std_code')->nullable();
            $table->string('resp_alternate_phone')->nullable();

            $table->timestamps();

            $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tds_24q_infos');
    }
};
