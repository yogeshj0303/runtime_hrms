<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\WallPost;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GenerateWallEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-wall-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate automated wall posts for birthdays, work anniversaries, and new joinees';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        $this->info('Starting wall event generation for ' . $today->toDateString());

        // 1. Birthdays
        $birthdayEmployees = Employee::whereHas('profile', function ($query) use ($today) {
            $query->whereMonth('dob', $today->month)
                  ->whereDay('dob', $today->day);
        })->get();

        foreach ($birthdayEmployees as $employee) {
            $this->createSystemPost('birthday', $employee, "Happy Birthday, {$employee->first_name}! Wishing you a fantastic day ahead.");
        }

        // 2. Work Anniversaries
        $anniversaryEmployees = Employee::whereMonth('joining_date', $today->month)
                                        ->whereDay('joining_date', $today->day)
                                        ->whereYear('joining_date', '<', $today->year)
                                        ->get();

        foreach ($anniversaryEmployees as $employee) {
            $years = $today->year - Carbon::parse($employee->joining_date)->year;
            $this->createSystemPost('anniversary', $employee, "Happy {$years} Year Work Anniversary, {$employee->first_name}! Thank you for your dedication.");
        }

        // 3. New Joinees
        $newJoinees = Employee::whereDate('joining_date', $today->toDateString())->get();

        foreach ($newJoinees as $employee) {
            $this->createSystemPost('new_joinee', $employee, "Welcome to the team, {$employee->first_name}! We are excited to have you on board.");
        }

        $this->info('Wall event generation completed successfully.');
    }

    private function createSystemPost($type, $employee, $defaultContent)
    {
        // Check if we already created this type of post today for this employee
        $exists = WallPost::where('type', $type)
            ->where('target_employee_id', $employee->id)
            ->whereDate('created_at', Carbon::today())
            ->exists();

        if (!$exists) {
            WallPost::create([
                'business_id' => $employee->business_id,
                'employee_id' => null, // System post has no author
                'target_employee_id' => $employee->id,
                'type' => $type,
                'content' => $defaultContent,
            ]);
            $this->info("Created {$type} post for employee ID: {$employee->id}");
        }
    }
}
