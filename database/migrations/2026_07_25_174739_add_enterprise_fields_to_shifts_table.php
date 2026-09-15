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
        Schema::table('shifts', function (Blueprint $table) {
            $table->string('shift_type')->default('General')->after('name');
            $table->integer('break_time_minutes')->default(0)->after('duration_minutes');
            $table->integer('grace_time_minutes')->default(0)->after('break_time_minutes');
            $table->integer('min_working_hours')->default(0)->after('payable_minutes');
            $table->integer('max_working_hours')->default(0)->after('min_working_hours');
            $table->string('color')->default('#556ee6')->after('is_default'); // default primary color
            $table->string('status')->default('active')->after('color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->dropColumn([
                'shift_type',
                'break_time_minutes',
                'grace_time_minutes',
                'min_working_hours',
                'max_working_hours',
                'color',
                'status'
            ]);
        });
    }
};
