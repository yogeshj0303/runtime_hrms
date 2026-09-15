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
        Schema::table('employee_policy_assignments', function (Blueprint $table) {
            $table->dropColumn('leave_policy_id');
            $table->json('leave_policy_ids')->nullable()->after('overtime_policy_id');
            $table->boolean('auto_shift_selection')->default(false)->after('shift_policy_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_policy_assignments', function (Blueprint $table) {
            $table->dropColumn('auto_shift_selection');
            $table->dropColumn('leave_policy_ids');
            $table->unsignedBigInteger('leave_policy_id')->nullable();
        });
    }
};
