<?php

use Illuminate\Contracts\Console\Kernel;
use App\Models\Employee;
use App\Models\Business;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\MissingPunchRequest;
use App\Models\LeaveRequest;
use App\Models\HelpdeskPermissionRequest;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$business = Business::first();
if (!$business) {
    die("No business found.\n");
}
$businessId = $business->id;

// We will just use the business_id 2 or 3 that the user has. Let's find an employee.
// We know employee_id = 3 was used in postman. Let's try to fetch user_id 3.
$employee = Employee::where('user_id', 3)->first();
if (!$employee) {
    // Just find any employee
    $employee = Employee::first();
}

if (!$employee) {
    die("No employee found.\n");
}

$businessId = $employee->business_id ?: $business->id;
$userId = $employee->user_id;

echo "Using Employee ID: {$userId} and Business ID: {$businessId}\n";

// 1. Create a Leave Type if it doesn't exist
$leaveType = LeaveType::where('business_id', $businessId)->first();
if (!$leaveType) {
    $leaveType = LeaveType::create([
        'business_id' => $businessId,
        'name' => 'Annual Leave',
        'short_name' => 'AL',
        'is_paid' => 1,
        'status' => 'active'
    ]);
    echo "Created Leave Type: Annual Leave\n";
}

// 2. Grant Leave Balance
$leaveBalance = LeaveBalance::updateOrCreate(
    ['employee_id' => $userId, 'leave_type_id' => $leaveType->id],
    ['business_id' => $businessId, 'balance' => 15, 'used' => 0]
);
echo "Granted 15 days Leave Balance for Leave Type: {$leaveType->name}\n";

// 3. Create Leave Request
LeaveRequest::create([
    'business_id' => $businessId,
    'employee_id' => $userId,
    'leave_type_id' => $leaveType->id,
    'from_date' => now()->addDays(2)->format('Y-m-d'),
    'to_date' => now()->addDays(4)->format('Y-m-d'),
    'total_days' => 3,
    'reason' => 'Family Vacation (Test)',
    'status' => 'pending'
]);
echo "Created pending Leave Request.\n";

// 4. Create Missing Punch Request
MissingPunchRequest::create([
    'business_id' => $businessId,
    'employee_id' => $userId,
    'attendance_date' => now()->subDays(1)->format('Y-m-d'),
    'requested_punch_in' => now()->subDays(1)->format('Y-m-d 09:00:00'),
    'requested_punch_out' => now()->subDays(1)->format('Y-m-d 18:00:00'),
    'request_type' => 'both',
    'reason' => 'Forgot ID Card (Test)',
    'status' => 'pending'
]);
echo "Created pending Missing Punch Request.\n";

// 5. Create Helpdesk Request
HelpdeskPermissionRequest::create([
    'business_id' => $businessId,
    'employee_id' => $userId,
    'reason' => 'Need to view tickets for cross-department project (Test)',
    'status' => 'pending'
]);
echo "Created pending Helpdesk Permission Request.\n";

echo "All test data inserted successfully!\n";
