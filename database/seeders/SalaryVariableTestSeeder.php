<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Business;
use App\Models\User;
use App\Models\BusinessUnit;
use App\Models\Location;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeWorkProfile;
use App\Models\SalaryComponent;
use App\Models\EmployeeSalaryVariable;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SalaryVariableTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create User
        $user = User::firstOrCreate(
            ['email' => 'admin_test@example.com'],
            [
                'name' => 'Test Admin',
                'password' => Hash::make('password')
            ]
        );

        // 2. Create Business
        $business = Business::firstOrCreate(
            ['business_name' => 'Test Business Solutions'],
            [
                'user_id' => $user->id,
                'address' => 'Test Address',
                'city' => 'Test City',
                'pincode' => '123456',
                'state' => 'Test State',
                'business_constitution' => 'Private Limited'
            ]
        );

        // 3. Create Business Unit
        $businessUnit = BusinessUnit::firstOrCreate(
            ['unit_name' => 'HQ Unit', 'business_id' => $business->id],
            ['user_id' => $user->id, 'report_title' => 'HQ Report']
        );

        // 4. Create Location
        $location = Location::firstOrCreate(
            ['name' => 'New York Office', 'business_id' => $business->id],
            ['user_id' => $user->id, 'state' => 'NY']
        );

        // 5. Create Department
        $department = Department::firstOrCreate(
            ['name' => 'Engineering', 'business_id' => $business->id],
            ['user_id' => $user->id]
        );

        // 6. Create Salary Components (Payable & Non-Payable)
        $payableComponent = SalaryComponent::firstOrCreate(
            ['name' => 'Variable Bonus', 'business_id' => $business->id],
            [
                'auth_id' => $user->id,
                'short_name' => 'VB',
                'unit_type' => 'Variable',
                'active' => true,
                'not_payable' => false
            ]
        );

        $nonPayableComponent = SalaryComponent::firstOrCreate(
            ['name' => 'Food Allowance (Non-Cash)', 'business_id' => $business->id],
            [
                'auth_id' => $user->id,
                'short_name' => 'FANC',
                'unit_type' => 'Variable',
                'active' => true,
                'not_payable' => true
            ]
        );

        // 7. Create Employees
        $employeeData = [
            ['first_name' => 'Alice', 'last_name' => 'Smith', 'code' => 'EMP001'],
            ['first_name' => 'Bob', 'last_name' => 'Jones', 'code' => 'EMP002'],
            ['first_name' => 'Charlie', 'last_name' => 'Brown', 'code' => 'EMP003']
        ];

        foreach ($employeeData as $data) {
            $empUser = User::firstOrCreate(
                ['email' => strtolower($data['first_name']) . '@example.com'],
                [
                    'name' => $data['first_name'] . ' ' . $data['last_name'],
                    'password' => Hash::make('password')
                ]
            );

            $employee = Employee::firstOrCreate(
                ['employee_code' => $data['code']],
                [
                    'user_id' => $empUser->id,
                    'business_id' => $business->id,
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                ]
            );

            // Create Work Profile for location, department etc
            EmployeeWorkProfile::updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'business_unit_id' => $businessUnit->id,
                    'location_id' => $location->id,
                    'department_id' => $department->id,
                    'is_current' => true,
                    'effective_from' => Carbon::now()->subYear()
                ]
            );

            // Add some existing Non-Cash items
            $parent = EmployeeSalaryVariable::firstOrCreate([
                'employee_id' => $employee->id,
                'salary_component_id' => $nonPayableComponent->id,
                'payroll_month' => date('Y-m'),
                'is_arrear' => false
            ], [
                'business_id' => $business->id,
                'amount' => 500,
                'comments' => 'Initial test food allowance'
            ]);

            // Add dummy details for the parent
            if ($parent->details()->count() === 0) {
                $parent->details()->create([
                    'amount' => 200,
                    'comments' => 'First week food allowance'
                ]);
                
                $parent->details()->create([
                    'amount' => 300,
                    'comments' => 'Second week food allowance'
                ]);
            }
        }
    }
}
