<?php
$employees = App\Models\Employee::all();
$ceo = $employees[0];
$managers = [$employees[1], $employees[2]];
$staff = [$employees[3], $employees[4], $employees[5], $employees[6]];

App\Models\EmployeeWorkProfile::updateOrCreate(
    ['employee_id' => $ceo->id],
    ['reporting_manager_id' => null, 'is_current' => 1]
);
foreach($managers as $m) {
    App\Models\EmployeeWorkProfile::updateOrCreate(
        ['employee_id' => $m->id],
        ['reporting_manager_id' => $ceo->id, 'is_current' => 1]
    );
}
App\Models\EmployeeWorkProfile::updateOrCreate(['employee_id' => $staff[0]->id], ['reporting_manager_id' => $managers[0]->id, 'is_current' => 1]);
App\Models\EmployeeWorkProfile::updateOrCreate(['employee_id' => $staff[1]->id], ['reporting_manager_id' => $managers[0]->id, 'is_current' => 1]);
App\Models\EmployeeWorkProfile::updateOrCreate(['employee_id' => $staff[2]->id], ['reporting_manager_id' => $managers[1]->id, 'is_current' => 1]);
App\Models\EmployeeWorkProfile::updateOrCreate(['employee_id' => $staff[3]->id], ['reporting_manager_id' => $managers[1]->id, 'is_current' => 1]);

echo "Done seeding!";
