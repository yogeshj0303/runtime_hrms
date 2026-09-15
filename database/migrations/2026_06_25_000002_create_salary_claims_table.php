<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_claims', function (Blueprint $table) {

            $table->id();

            $table->foreignId('business_id')
                    ->constrained('businesses')
                    ->cascadeOnDelete();

            $table->foreignId('user_id')
                    ->constrained('users')
                    ->cascadeOnDelete();

            $table->foreignId('claim_component_id')
                    ->constrained('claim_components')
                    ->cascadeOnDelete();

            $table->unsignedBigInteger('claim_approver')->nullable();

            $table->boolean('enable_claim_request')->default(false);

            $table->unsignedInteger('request_limit')->default(0);

            $table->unsignedInteger('monthly_limit')->default(0);

            $table->unsignedInteger('employee_limit')->default(0);

            $table->boolean('allow_all_grades')->default(false);

            $table->json('allowed_grades')->nullable();

            $table->string('status')->default('active');

            $table->timestamps();

            $table->softDeletes();

            $table->index('business_id');
            $table->index('claim_component_id');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_claims');
    }
};
