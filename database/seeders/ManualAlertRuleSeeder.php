<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ManualAlertRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\AlertRule::updateOrCreate(
            ['name' => 'HR Custom Broadcast'],
            [
                'category' => 'System',
                'is_active' => true,
                'priority' => 'normal',
                'channels' => ['dashboard'] // default
            ]
        );
    }
}
