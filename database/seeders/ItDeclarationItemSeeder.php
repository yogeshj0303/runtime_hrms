<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItDeclarationItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['section' => '80C', 'name' => 'Life Insurance Premium (LIC)', 'max_limit' => 150000],
            ['section' => '80C', 'name' => 'Public Provident Fund (PPF)', 'max_limit' => 150000],
            ['section' => '80C', 'name' => 'Equity Linked Savings Scheme (ELSS)', 'max_limit' => 150000],
            ['section' => '80C', 'name' => 'National Savings Certificate (NSC)', 'max_limit' => 150000],
            ['section' => '80C', 'name' => 'Children Tuition Fee', 'max_limit' => 150000],
            ['section' => '80C', 'name' => 'Housing Loan Principal Repayment', 'max_limit' => 150000],
            ['section' => '80D', 'name' => 'Medical Insurance Premium (Self/Family)', 'max_limit' => 25000],
            ['section' => '80D', 'name' => 'Medical Insurance Premium (Parents)', 'max_limit' => 50000],
            ['section' => '80D', 'name' => 'Preventive Health Checkup', 'max_limit' => 5000],
            ['section' => '80E', 'name' => 'Interest on Education Loan', 'max_limit' => null],
            ['section' => '80G', 'name' => 'Donations to Approved Funds', 'max_limit' => null],
            ['section' => 'Section 24', 'name' => 'Interest on Housing Loan', 'max_limit' => 200000],
            ['section' => 'HRA', 'name' => 'House Rent Allowance (Rent Paid)', 'max_limit' => null],
        ];

        foreach ($items as $item) {
            \App\Models\ItDeclarationItem::updateOrCreate(
                ['section' => $item['section'], 'name' => $item['name']],
                ['max_limit' => $item['max_limit'], 'is_active' => true]
            );
        }
    }
}
