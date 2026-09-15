<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maternity_policy_audit_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('user_id');        // owner of the policy
            $table->string('child_type');
            $table->string('field_name');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->unsignedBigInteger('updated_by');     // who made the change

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maternity_policy_audit_logs');
    }
};
