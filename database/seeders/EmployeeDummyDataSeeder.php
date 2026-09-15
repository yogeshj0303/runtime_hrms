<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeDummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employeeId = 4;
        $employee = \App\Models\Employee::find($employeeId) ?? \App\Models\Employee::first();

        if (!$employee) {
            $this->command->error('No employees found in the database. Please create an employee first.');
            return;
        }

        $businessId = $employee->business_id ?? 1;
        $month = date('Y-m');

        // Create dummy earning component
        $bonusComponent = \App\Models\SalaryComponent::firstOrCreate(
            ['name' => 'Performance Bonus', 'business_id' => $businessId],
            ['active' => true, 'short_name' => 'BONUS', 'auth_id' => 1]
        );

        // Create dummy deduction component
        $canteenComponent = \App\Models\SalaryDeduction::firstOrCreate(
            ['name' => 'Canteen Deduction', 'business_id' => $businessId],
            ['active' => true, 'short_name' => 'CANTEEN', 'auth_id' => 1]
        );

        // Create dummy unit component
        $unitComponent = \App\Models\SalaryComponent::firstOrCreate(
            ['name' => 'Production Units', 'business_id' => $businessId],
            ['active' => true, 'short_name' => 'UNITS', 'auth_id' => 1]
        );

        // 1. Seed Salary Variable (Earnings)
        $varEarning = \App\Models\EmployeeSalaryVariable::updateOrCreate(
            ['employee_id' => $employee->id, 'salary_component_id' => $bonusComponent->id, 'payroll_month' => $month],
            ['business_id' => $businessId, 'amount' => 5000, 'is_arrear' => false, 'comments' => 'Excellent performance']
        );
        $varEarning->details()->updateOrCreate(
            ['employee_salary_variable_id' => $varEarning->id],
            ['amount' => 5000, 'comments' => 'Full amount']
        );

        // 2. Seed Deduction Variable
        $varDeduction = \App\Models\EmployeeDeductionVariable::updateOrCreate(
            ['employee_id' => $employee->id, 'salary_deduction_id' => $canteenComponent->id, 'payroll_month' => $month],
            ['total_amount' => 500]
        );
        $varDeduction->details()->updateOrCreate(
            ['employee_deduction_variable_id' => $varDeduction->id],
            ['amount' => 500, 'comments' => 'Monthly deduction']
        );

        // 3. Seed Extra Days
        \App\Models\EmployeeExtraDay::updateOrCreate(
            ['employee_id' => $employee->id, 'payroll_month' => $month],
            ['extra_days' => 2, 'arrear_days' => 0, 'ot_days' => 0, 'comments' => 'Weekend work']
        );

        // 4. Seed OT Hours
        \App\Models\EmployeeOtHour::updateOrCreate(
            ['employee_id' => $employee->id, 'payroll_month' => $month],
            ['hours' => 10, 'minutes' => 30]
        );

        // 5. Seed IT Exemptions
        \App\Models\EmployeeItExemption::updateOrCreate(
            ['employee_id' => $employee->id, 'financial_year' => '2026-2027'],
            ['additional_exemptions' => 1500]
        );

        // 6. Seed Salary Units
        $unit = \App\Models\EmployeeSalaryUnit::updateOrCreate(
            ['employee_id' => $employee->id, 'salary_component_id' => $unitComponent->id, 'payroll_month' => $month],
            ['total_units' => 150, 'is_arrear' => false]
        );
        $unit->details()->updateOrCreate(
            ['employee_salary_unit_id' => $unit->id],
            ['capture_date' => date('Y-m-d'), 'quantity' => 150, 'comment' => 'Completed units']
        );

        $this->command->info("Dummy Data Capture records generated successfully for Employee {$employee->first_name} (ID: {$employee->id}) for month {$month}.");
    }
}
