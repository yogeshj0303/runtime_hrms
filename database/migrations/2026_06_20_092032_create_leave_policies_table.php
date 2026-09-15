<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_policies', function (Blueprint $table) {
            $table->id();

            $table->foreignId('business_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('leave_type_id')
                ->constrained('leave_types')
                ->cascadeOnDelete();

            $table->string('policy_description')->nullable();

            // Grant Leave
            $table->boolean('grant_leaves')->default(false);
            $table->integer('minimum_presence_days')->default(0);

            // Monthly grant
            $table->integer('jan_grant')->default(0);
            $table->integer('feb_grant')->default(0);
            $table->integer('mar_grant')->default(0);
            $table->integer('apr_grant')->default(0);
            $table->integer('may_grant')->default(0);
            $table->integer('jun_grant')->default(0);
            $table->integer('jul_grant')->default(0);
            $table->integer('aug_grant')->default(0);
            $table->integer('sep_grant')->default(0);
            $table->integer('oct_grant')->default(0);
            $table->integer('nov_grant')->default(0);
            $table->integer('dec_grant')->default(0);

            $table->boolean('reset_negative_balance')->default(false);

            // Lapse Leave
            $table->boolean('lapse_leaves')->default(false);

            $table->integer('jan_lapse')->default(0);
            $table->integer('feb_lapse')->default(0);
            $table->integer('mar_lapse')->default(0);
            $table->integer('apr_lapse')->default(0);
            $table->integer('may_lapse')->default(0);
            $table->integer('jun_lapse')->default(0);
            $table->integer('jul_lapse')->default(0);
            $table->integer('aug_lapse')->default(0);
            $table->integer('sep_lapse')->default(0);
            $table->integer('oct_lapse')->default(0);
            $table->integer('nov_lapse')->default(0);
            $table->integer('dec_lapse')->default(0);

            // Other Options
            $table->boolean('during_probation')->default(false);
            $table->boolean('after_probation')->default(false);
            $table->boolean('auto_apply')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_policies');
    }
};