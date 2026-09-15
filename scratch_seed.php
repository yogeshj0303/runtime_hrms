$employees = \App\Models\Employee::where("status", "Active")->take(5)->get();
$leaveTypePaid = \App\Models\LeaveType::where("is_paid_leave", 1)->first() ?? \App\Models\LeaveType::first();
$leaveTypeUnpaid = \App\Models\LeaveType::where("is_paid_leave", 0)->first() ?? \App\Models\LeaveType::first();

foreach($employees as $index => $emp) {
    for($i = 1; $i <= (3 + $index * 2); $i++) {
        $date = \Carbon\Carbon::now()->subDays($i * 5);
        \App\Models\AttendanceDaily::create([
            "business_id" => $emp->business_id,
            "employee_id" => $emp->user_id,
            "attendance_date" => $date->format("Y-m-d"),
            "day" => $date->format("l"),
            "status" => "Absent",
            "is_late" => false
        ]);
    }
    
    for($i = 1; $i <= (4 + $index); $i++) {
        $date = \Carbon\Carbon::now()->subDays(($i * 4) + 1);
        \App\Models\AttendanceDaily::create([
            "business_id" => $emp->business_id,
            "employee_id" => $emp->user_id,
            "attendance_date" => $date->format("Y-m-d"),
            "day" => $date->format("l"),
            "status" => "Present",
            "is_late" => true,
            "punch_in_time" => "10:30:00",
            "punch_out_time" => "18:00:00"
        ]);
    }

    for($i = 1; $i <= (2 + $index); $i++) {
        $date = \Carbon\Carbon::now()->subDays(($i * 6) + 2);
        \App\Models\AttendanceDaily::create([
            "business_id" => $emp->business_id,
            "employee_id" => $emp->user_id,
            "attendance_date" => $date->format("Y-m-d"),
            "day" => $date->format("l"),
            "status" => "Present",
            "is_late" => false,
            "punch_in_time" => "09:00:00",
            "punch_out_time" => "14:00:00"
        ]);
    }
}
echo "Dummy data seeded!";
