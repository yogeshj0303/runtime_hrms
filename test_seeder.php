<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$business = \App\Models\Business::first();

if ($business) {
    $businessId = $business->id;

    $userId = $business->user_id;

    $leaveType = \App\Models\LeaveType::firstOrCreate([
        'business_id' => $businessId,
        'name' => 'Sick Leave',
    ], [
        'code' => 'SL',
        'color' => '#f56954'
    ]);

    \App\Models\LeavePolicy::firstOrCreate([
        'business_id' => $businessId,
        'leave_type_id' => $leaveType->id
    ], [
        'user_id' => $userId,
        'policy_description' => '12 days sick leave per year.',
        'grant_leaves' => 1,
        'lapse_leaves' => 0
    ]);

    \App\Models\ShiftPolicy::firstOrCreate([
        'business_id' => $businessId,
        'name' => 'Standard Shift',
    ], [
        'user_id' => $userId,
        'description' => '9:00 AM to 6:00 PM',
        'is_default' => 1
    ]);

    \App\Models\WeekOffPolicy::firstOrCreate([
        'business_id' => $businessId,
        'name' => 'Standard Week Off',
    ], [
        'user_id' => $userId,
        'description' => 'Saturday & Sunday',
        'is_default' => 1
    ]);

    \App\Models\OvertimePolicy::firstOrCreate([
        'business_id' => $businessId,
        'policy_name' => 'Standard Overtime',
    ], [
        'auth_id' => $userId,
        'salary_treatment' => 'Include',
        'days_in_month' => '30 Days',
        'hours_in_day' => 8
    ]);

    \App\Models\MaternityLeavePolicy::firstOrCreate([
        'business_id' => $businessId,
        'child_type' => 'first_child',
    ], [
        'user_id' => $userId,
        'normal_leave_weeks' => 26,
        'adoption_leave_weeks' => 12,
        'miscarriage_leave_weeks' => 6,
        'tubectomy_leave_weeks' => 2,
        'status' => 'active'
    ]);

    echo "Inserted test policies successfully!\n";
} else {
    echo "No business found to insert policies for.\n";
}
