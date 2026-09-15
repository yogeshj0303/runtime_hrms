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
        Schema::table('employee_permissions', function (Blueprint $table) {
            $table->boolean('selfie_at_all_locations')->default(false)->after('wall_access');
            $table->boolean('missed_punch')->default(false);
            $table->boolean('web_chat_punch')->default(false);
            $table->boolean('time_relaxation')->default(false);
            $table->boolean('scan_at_all_locations')->default(false);
            $table->boolean('ignore_time_strikes')->default(false);
            $table->boolean('auto_punch_in_out')->default(false);
            
            $table->integer('missed_punch_limit')->default(0);
            $table->integer('strike_exemption_limit')->default(0);
            
            $table->boolean('visit_punch')->default(false);
            $table->boolean('visit_punch_approval')->default(false);
            $table->boolean('visit_punch_attendance')->default(false);
            $table->boolean('live_travel')->default(false);
            $table->boolean('live_travel_attendance')->default(false);
            
            $table->boolean('give_badges')->default(false);
            $table->boolean('give_rewards')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_permissions', function (Blueprint $table) {
            $table->dropColumn([
                'selfie_at_all_locations',
                'missed_punch',
                'web_chat_punch',
                'time_relaxation',
                'scan_at_all_locations',
                'ignore_time_strikes',
                'auto_punch_in_out',
                'missed_punch_limit',
                'strike_exemption_limit',
                'visit_punch',
                'visit_punch_approval',
                'visit_punch_attendance',
                'live_travel',
                'live_travel_attendance',
                'give_badges',
                'give_rewards'
            ]);
        });
    }
};
