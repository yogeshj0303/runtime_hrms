<?php

namespace App\Services;

use App\Models\AlertRule;
use App\Models\Alert;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmployeeLetterMail;

class AlertEngineService
{
    public function evaluateAllRules()
    {
        $rules = AlertRule::where('is_active', true)->get();

        foreach ($rules as $rule) {
            try {
                if ($rule->category === 'Employee') {
                    $this->evaluateEmployeeRule($rule);
                } elseif ($rule->category === 'Attendance') {
                    $this->evaluateAttendanceRule($rule);
                } elseif ($rule->category === 'Leave') {
                    $this->evaluateLeaveRule($rule);
                }
            } catch (\Exception $e) {
                Log::error("AlertEngineService Error processing rule {$rule->id}: " . $e->getMessage());
            }
        }
    }

    protected function evaluateLeaveRule(AlertRule $rule)
    {
        $today = Carbon::today();
        
        if ($rule->name === 'Leave Request Pending Approval') {
            // Respect the setup configuration for how many days pending before sending alert
            $targetDate = clone $today;
            $targetDate->subDays($rule->reminder_days_before ?? 0);

            // Get pending leaves created ON or BEFORE the target date (based on setup condition)
            $pendingLeaves = \App\Models\LeaveRequest::with('employee')
                ->where('status', 'pending')
                ->whereDate('created_at', '<=', $targetDate)
                ->get();

            $admin = \App\Models\User::first(); // Assuming HR Admin gets these approvals

            foreach ($pendingLeaves as $leave) {
                if ($leave->employee) {
                    $message = "Leave Request from {$leave->employee->name} is pending approval.";
                    // We send the alert to the Admin/Manager
                    $this->createAlert($rule, clone $leave->employee, $message, $leave, $admin->id);
                }
            }
        }
    }

    protected function evaluateAttendanceRule(AlertRule $rule)
    {
        $today = Carbon::today();

        if ($rule->name === 'Attendance Missing Punch') {
            // Check past attendance based on setup configuration
            $targetDate = clone $today;
            $targetDate->subDays($rule->reminder_days_before ?? 1); // default lookback 1 day

            // A missing punch could be represented by missing total_working_time or specific status
            $missingPunches = \App\Models\AttendanceDaily::with('employee')
                ->whereDate('attendance_date', $targetDate)
                ->whereNull('total_working_time') // E.g., clocked in but didn't clock out
                ->get();

            foreach ($missingPunches as $att) {
                if ($att->employee) {
                    $message = "You have a missing punch for " . Carbon::parse($att->attendance_date)->format('M d') . ". Please regularize.";
                    // Sent to the employee themselves
                    $this->createAlert($rule, clone $att->employee, $message, $att);
                }
            }
        }

        if ($rule->name === 'Late Coming Warning') {
            $targetDate = clone $today;
            $targetDate->subDays($rule->reminder_days_before ?? 1);

            $latePunches = \App\Models\AttendanceDaily::with('employee')
                ->whereDate('attendance_date', $targetDate)
                ->where('is_late', true)
                ->get();

            foreach ($latePunches as $att) {
                if ($att->employee) {
                    $message = "Warning: You were marked late on " . Carbon::parse($att->attendance_date)->format('M d') . " (" . $att->late_minutes . " minutes late).";
                    // Send to employee
                    $this->createAlert($rule, clone $att->employee, $message, $att);
                }
            }
        }
    }

    public function evaluateRealtimeAttendance(\App\Models\AttendanceDaily $att)
    {
        if ($att->is_late && $att->employee) {
            $rule = AlertRule::where('name', 'Late Coming Warning')->where('is_active', true)->first();
            if ($rule) {
                $message = "Warning: You were marked late on " . Carbon::parse($att->attendance_date)->format('M d') . " (" . $att->late_minutes . " minutes late).";
                $this->createAlert($rule, $att->employee, $message, $att);
            }

            // Check if we need to generate a warning letter
            $lateCount = \App\Models\AttendanceDaily::where('employee_id', $att->employee_id)
                ->whereMonth('attendance_date', Carbon::parse($att->attendance_date)->month)
                ->whereYear('attendance_date', Carbon::parse($att->attendance_date)->year)
                ->where('is_late', true)
                ->count();

            $templateId = null;
            $timeRule = \App\Models\TimeRule::where('business_id', $att->employee->business_id)
                ->where('is_active', true)
                ->where('warning_occurrences', '<=', $lateCount)
                ->whereNotNull('warning_letter')
                ->orderBy('warning_occurrences', 'desc')
                ->first();

            if ($timeRule) {
                $templateId = $timeRule->warning_letter;
            } else {
                $strikeRule = \App\Models\StrikeRule::where('business_id', $att->employee->business_id)
                    ->where('is_active', true)
                    ->where('from_occurrences', '<=', $lateCount)
                    ->where(function($q) use ($lateCount) {
                        $q->where('to_occurrences', '>=', $lateCount)
                          ->orWhere('to_occurrences', 0);
                    })
                    ->whereNotNull('warning_letter')
                    ->first();
                if ($strikeRule) {
                    $templateId = $strikeRule->warning_letter;
                }
            }

            if ($templateId) {
                $this->generateDraftLetter($att->employee, $templateId, $att);
            }
        }
    }

    protected function generateDraftLetter($employee, $templateId, $att)
    {
        $template = \App\Models\LetterTemplate::find($templateId);
        if (!$template) return;
        
        // Prevent duplicate letter for the same employee, template, and month
        $existing = \App\Models\EmployeeLetter::where('employee_id', $employee->id)
            ->where('letter_template_id', $templateId)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->exists();
            
        if ($existing) return;
        
        $content = $template->content;
        
        $designation = 'Employee';
        $department = 'N/A';
        $location = 'N/A';
        if ($employee->workProfiles && $employee->workProfiles->first()) {
            $wp = $employee->workProfiles->first();
            if ($wp->designation) $designation = $wp->designation->name;
            if ($wp->department) $department = $wp->department->name;
            if ($wp->location) $location = $wp->location->name;
        }

        $placeholders = [
            '{{employee_name}}' => $employee->first_name . ' ' . $employee->last_name,
            '{{employee_code}}' => $employee->employee_code ?? 'N/A',
            '{{designation}}' => $designation,
            '{{department}}' => $department,
            '{{location}}' => $location,
            '{{joining_date}}' => $employee->joining_date ? $employee->joining_date->format('d M, Y') : 'N/A',
            '{{salary}}' => $employee->salary ? number_format($employee->salary, 2) : 'N/A',
            '{{date}}' => now()->format('d M, Y'),
            '{{business_name}}' => $employee->business->name ?? 'Company',
        ];
        
        foreach ($placeholders as $key => $value) {
            $content = str_replace($key, $value, $content);
        }

        $letter = \App\Models\EmployeeLetter::create([
            'employee_id' => $employee->id,
            'letter_template_id' => $templateId,
            'issued_date' => Carbon::today(),
            'generated_html' => $content,
            'status' => 'Published'
        ]);

        if ($employee->email) {
            try {
                Mail::to($employee->email)->send(new EmployeeLetterMail($letter));
            } catch (\Exception $e) {
                Log::error("Failed to auto-send warning letter email to {$employee->email}: " . $e->getMessage());
            }
        }
    }

    protected function evaluateEmployeeRule(AlertRule $rule)
    {
        $today = Carbon::today();
        
        // Example: Birthday Alert
        if ($rule->name === 'Birthday') {
            // Find employees with birthday today or in X days
            $targetDate = $today->copy()->addDays($rule->reminder_days_before);
            
            $employees = Employee::whereHas('profile', function($q) use ($targetDate) {
                $q->whereMonth('dob', $targetDate->month)
                  ->whereDay('dob', $targetDate->day);
            })->where('status', 'active')->get();
            
            foreach ($employees as $emp) {
                // Send alert to the Admin (User ID 1) so HR knows
                $admin = \App\Models\User::first();
                $this->createAlert($rule, $emp, "It's {$emp->first_name}'s Birthday on {$targetDate->format('M d')}!", null, $admin->id);
            }
        }

        // Example: Work Anniversary
        if ($rule->name === 'Work Anniversary') {
            $targetDate = $today->copy()->addDays($rule->reminder_days_before);
            
            $employees = Employee::whereMonth('joining_date', $targetDate->month)
                                 ->whereDay('joining_date', $targetDate->day)
                                 ->where('status', 'active')
                                 ->get();
            
            foreach ($employees as $emp) {
                $years = $targetDate->year - Carbon::parse($emp->joining_date)->year;
                if ($years > 0) {
                    $this->createAlert($rule, $emp, "Happy {$years} Year Work Anniversary to {$emp->first_name}!");
                }
            }
        }

        // Example: Probation Ending
        if ($rule->name === 'Probation Ending') {
            $targetDate = $today->copy()->addDays($rule->reminder_days_before ?? 0);
            
            // Checking probation end date if it matches target date
            $employees = Employee::whereDate('probation_end_date', $targetDate->format('Y-m-d'))
                                 ->where('status', 'active')->get();
                                 
            foreach ($employees as $emp) {
                // Send alert to HR / Admin and Employee
                $this->createAlert($rule, $emp, "{$emp->first_name}'s probation ends on {$targetDate->format('M d')}.");
            }
        }
    }

    protected function createAlert(AlertRule $rule, $employee, $message, $reference = null, $targetUserIdOverride = null)
    {
        // Prevent duplicate alert for the same rule & employee on the same day
        $existing = Alert::where('alert_rule_id', $rule->id)
            ->where('employee_id', $employee->id)
            ->whereDate('created_at', Carbon::today())
            ->exists();
            
        if ($existing) {
            return;
        }

        // Find targets (e.g. managers, HR, or the employee themselves) based on filters
        // For simplicity, we send to the employee's user_id or a general HR admin.
        $targetUserId = $targetUserIdOverride ?? $employee->user_id; 

        $alert = Alert::create([
            'alert_rule_id' => $rule->id,
            'user_id' => $targetUserId,
            'employee_id' => $employee->id,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference ? $reference->id : null,
            'message' => $message,
            'status' => 'pending'
        ]);

        // Dispatch notifications based on configured channels
        $this->dispatchNotifications($alert, $rule);
    }

    protected function dispatchNotifications(Alert $alert, AlertRule $rule)
    {
        try {
            $targetUser = \App\Models\User::find($alert->user_id);
            if ($targetUser) {
                $targetUser->notify(new \App\Notifications\GenericAlertNotification($alert, $rule));
            }
        } catch (\Exception $e) {
            Log::error("Failed to dispatch alert notification for alert ID {$alert->id}: " . $e->getMessage());
        }
    }
}
