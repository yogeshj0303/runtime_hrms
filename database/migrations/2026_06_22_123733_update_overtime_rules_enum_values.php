<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE overtime_rules
            MODIFY attendance_type ENUM(
                'Present',
                'Absent',
                'Holiday',
                'Week Off'
            )
        ");

        DB::statement("
            ALTER TABLE overtime_rules
            MODIFY time_basis ENUM(
                'Early Coming',
                'Late Going',
                'Net Late',
                'Total In Minutes'
            )
        ");

        DB::statement("
            ALTER TABLE overtime_rules
            MODIFY calculation_method ENUM(
                'Exclusive',
                'Progressive'
            )
        ");

        DB::statement("
            ALTER TABLE overtime_rules
            MODIFY overtime_min_type ENUM(
                'Actual',
                'Above',
                'Fixed'
            )
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE overtime_rules
            MODIFY attendance_type ENUM(
                'PRESENT',
                'ABSENT',
                'HOLIDAY',
                'WEEK_OFF'
            )
        ");

        DB::statement("
            ALTER TABLE overtime_rules
            MODIFY time_basis ENUM(
                'EARLY_COMING',
                'LATE_GOING',
                'NET_LATE',
                'TOTAL_IN_MINUTES'
            )
        ");

        DB::statement("
            ALTER TABLE overtime_rules
            MODIFY calculation_method ENUM(
                'EXCLUSIVE',
                'PROGRESSIVE'
            )
        ");

        DB::statement("
            ALTER TABLE overtime_rules
            MODIFY overtime_min_type ENUM(
                'ACTUAL',
                'ABOVE',
                'FIXED'
            )
        ");
    }
};