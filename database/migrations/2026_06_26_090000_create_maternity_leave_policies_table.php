<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maternity_leave_policies', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('user_id');

            $table->enum('child_type', [
                'first_child',
                'second_child',
                'third_child',
                'subsequent_child',
            ]);

            // Leave weeks for each scenario
            $table->integer('normal_leave_weeks')->default(0);
            $table->integer('adoption_leave_weeks')->default(0);
            $table->integer('surrogacy_leave_weeks')->default(0);
            $table->integer('tubectomy_leave_weeks')->default(0);
            $table->integer('miscarriage_leave_weeks')->default(0);

            // Miscarriage: apply policy to all cases above threshold
            $table->boolean('miscarriage_over_and_above')->default(false);

            // Extension
            $table->integer('maternity_extension_days')->default(0);
            $table->boolean('allow_extension')->default(false);

            // Eligibility
            $table->integer('minimum_working_days')->default(0);

            // Split leave into multiple periods
            $table->boolean('leave_split')->default(false);

            // Audit trail
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // One row per child type per business
            $table->unique(['business_id', 'child_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maternity_leave_policies');
    }
};
