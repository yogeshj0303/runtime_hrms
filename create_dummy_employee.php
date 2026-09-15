

$user = \App\Models\User::first() ?? \App\Models\User::factory()->create();

// Create Business if not exists
$business = \App\Models\Business::first() ?? \App\Models\Business::create([
    'user_id' => $user->id,
    'business_name' => 'Demo Company',
    'district' => 'Demo District',
    'current_step' => 1,
    'is_completed' => 1,
    'status' => 'active'
]);

$employee = \App\Models\Employee::create([
    'user_id' => $user->id,
    'business_id' => $business->id,
    'employee_code' => 'EMP001',
    'first_name' => 'John',
    'last_name' => 'Doe',
    'email' => 'john@example.com',
    'designation' => 'Software Engineer',
    'department' => 'IT',
    'joining_date' => now()
]); 

$employee->profile()->create([
    'joining_date' => now(), 
    'gender' => 'Male',
    'dob' => now()->subYears(30),
    'notice_period' => 30
]); 

echo "Employee created: " . $employee->id . "\n";
