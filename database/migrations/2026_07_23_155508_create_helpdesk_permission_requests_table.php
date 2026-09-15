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
        Schema::create('helpdesk_permission_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('business_id');
            $table->integer('employee_id');
            $table->text('reason')->nullable();
            $table->string('status', 20)->default('Pending'); // Pending, Approved, Rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('helpdesk_permission_requests');
    }
};
