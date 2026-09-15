<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlertTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::first();
        if (!$user) {
            return;
        }

        // Create some Rules
        $rule1 = \App\Models\AlertRule::updateOrCreate(
            ['name' => 'Attendance Missing Punch'],
            ['category' => 'Attendance', 'priority' => 'high', 'is_active' => true, 'channels' => ['dashboard', 'email']]
        );

        $rule2 = \App\Models\AlertRule::updateOrCreate(
            ['name' => 'Leave Request Pending Approval'],
            ['category' => 'Leave', 'priority' => 'normal', 'is_active' => true, 'channels' => ['dashboard']]
        );

        $rule3 = \App\Models\AlertRule::updateOrCreate(
            ['name' => 'Document Expiring Soon'],
            ['category' => 'Document', 'priority' => 'critical', 'is_active' => true, 'channels' => ['dashboard', 'sms']]
        );

        // Create some Alerts for the first user
        \App\Models\Alert::create([
            'alert_rule_id' => $rule1->id,
            'user_id' => $user->id,
            'message' => 'You have a missing punch out on 24-Jul-2026. Please regularize your attendance.',
            'status' => 'pending',
            'created_at' => now()->subHours(2)
        ]);

        \App\Models\Alert::create([
            'alert_rule_id' => $rule2->id,
            'user_id' => $user->id,
            'message' => 'Leave request from John Doe is waiting for your approval.',
            'status' => 'pending',
            'created_at' => now()->subHours(5)
        ]);

        \App\Models\Alert::create([
            'alert_rule_id' => $rule3->id,
            'user_id' => $user->id,
            'message' => 'Your Visa document is expiring in 15 days! Please upload the renewed document.',
            'status' => 'pending',
            'created_at' => now()->subDays(1)
        ]);

        \App\Models\Alert::create([
            'alert_rule_id' => $rule1->id,
            'user_id' => $user->id,
            'message' => 'You had a missing punch out on 20-Jul-2026. (Resolved)',
            'status' => 'resolved',
            'resolved_at' => now(),
            'created_at' => now()->subDays(4)
        ]);
    }
}
