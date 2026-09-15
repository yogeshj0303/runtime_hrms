<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalarySetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $businessId = 1; // Assuming default business or grab first
        $business = \App\Models\Business::first();
        if ($business) {
            $businessId = $business->id;
        }

        $authId = 1;

        // 1. Create Standard Components (Included in Gross)
        $basic = \App\Models\SalaryComponent::firstOrCreate(
            ['name' => 'Basic Pay', 'business_id' => $businessId],
            [
                'auth_id' => $authId,
                'short_name' => 'BASIC',
                'active' => true,
                'exclude_from_gross_salary' => false
            ]
        );

        $hra = \App\Models\SalaryComponent::firstOrCreate(
            ['name' => 'House Rent Allowance', 'business_id' => $businessId],
            [
                'auth_id' => $authId,
                'short_name' => 'HRA',
                'active' => true,
                'exclude_from_gross_salary' => false
            ]
        );

        $special = \App\Models\SalaryComponent::firstOrCreate(
            ['name' => 'Special Allowance', 'business_id' => $businessId],
            [
                'auth_id' => $authId,
                'short_name' => 'SA',
                'active' => true,
                'exclude_from_gross_salary' => false
            ]
        );

        // 2. Create Standard Salary Structure
        $structure = \App\Models\SalaryStructure::firstOrCreate(
            ['structure_name' => 'Runtime Default Structure', 'business_id' => $businessId],
            ['auth_id' => $authId]
        );

        // 3. Map Components to Structure as Rules
        // Basic: 50% of CTC
        \App\Models\SalaryStructureRule::firstOrCreate(
            ['salary_structure_id' => $structure->id, 'salary_component_id' => $basic->id],
            [
                'business_id' => $businessId,
                'auth_id' => $authId,
                'order_no' => 1,
                'calculate_percentage' => 50.00
            ]
        );

        // HRA: 20% of CTC
        \App\Models\SalaryStructureRule::firstOrCreate(
            ['salary_structure_id' => $structure->id, 'salary_component_id' => $hra->id],
            [
                'business_id' => $businessId,
                'auth_id' => $authId,
                'order_no' => 2,
                'calculate_percentage' => 20.00
            ]
        );

        // Special Allowance: 30% of CTC
        \App\Models\SalaryStructureRule::firstOrCreate(
            ['salary_structure_id' => $structure->id, 'salary_component_id' => $special->id],
            [
                'business_id' => $businessId,
                'auth_id' => $authId,
                'order_no' => 3,
                'calculate_percentage' => 30.00
            ]
        );

        // 4. Create Statutory Settings to test deductions
        \App\Models\EpfSetting::firstOrCreate(
            ['business_id' => $businessId],
            [
                'user_id' => $authId,
                'effective_from' => '2026-04-01',
                'is_enabled' => true,
                'wage_ceiling' => 15000,
                'employee_contribution_rate' => 12.00,
                'employer_contribution_rate' => 3.67,
                'pension_contribution_rate' => 8.33,
                'edli_contribution_rate' => 0.50
            ]
        );

        \App\Models\EsiSetting::firstOrCreate(
            ['business_id' => $businessId],
            [
                'user_id' => $authId,
                'effective_from' => '2026-04-01',
                'is_enabled' => true,
                'gross_wage_ceiling' => 21000,
                'employee_contribution' => 0.75,
                'employer_contribution' => 3.25
            ]
        );

        $this->command->info('Standard Salary Setup seeded successfully! (Runtime Default Structure)');
    }
}
