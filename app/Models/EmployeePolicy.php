<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeePolicy extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    
    protected $casts = [
        'effective_from' => 'date',
        'auto_shift_selection' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function shiftPolicy()
    {
        return $this->belongsTo(ShiftPolicy::class);
    }

    public function weekOffPolicy()
    {
        return $this->belongsTo(WeekOffPolicy::class);
    }

    public function overtimePolicy()
    {
        return $this->belongsTo(OvertimePolicy::class);
    }

    public function leavePolicies()
    {
        return $this->belongsToMany(LeavePolicy::class, 'employee_leave_policies', 'employee_policy_id', 'leave_policy_id')
                    ->withTimestamps();
    }

    public function timeRules()
    {
        return $this->belongsToMany(TimeRule::class, 'employee_time_rules', 'employee_policy_id', 'time_rule_id')
                    ->withTimestamps();
    }
}
