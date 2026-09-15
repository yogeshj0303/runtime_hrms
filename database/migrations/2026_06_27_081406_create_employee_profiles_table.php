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
        Schema::create('employee_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('dob')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Transgender'])->nullable();
            $table->enum('marital_status', ['Unmarried', 'Married', 'Widow'])->nullable();
            $table->date('joining_date')->nullable();
            $table->date('confirmation_date')->nullable();
            $table->string('official_email')->nullable();
            $table->string('personal_email')->nullable();
            $table->string('office_phone')->nullable();
            $table->string('personal_phone')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('biometric_code')->nullable();
            $table->integer('notice_period')->default(0);
            $table->string('profile_photo')->nullable();
            $table->string('registered_face')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_profiles');
    }
};
