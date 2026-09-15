<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('overtime_policies', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('business_id');

            $table->unsignedBigInteger('auth_id');

            $table->string('policy_name');

            $table->enum(
                'salary_treatment',
                [
                    'INCLUDE',
                    'EXCLUDE'
                ]
            )->default('INCLUDE');

            $table->enum(
                'days_in_month',
                [
                    'ACTUAL_DAYS',
                    'FIXED_30_DAYS'
                ]
            )->default('ACTUAL_DAYS');

            $table->integer('hours_in_day')->default(9);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('overtime_policies');
    }
};