<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'user_id',
        'business_id',
        'code',
        'name',
        'start_time',
        'duration_hours',
        'duration_minutes',
        'end_time',
        'payable_hours',
        'payable_minutes',
        'is_default',
        'shift_type',
        'break_time_minutes',
        'grace_time_minutes',
        'min_working_hours',
        'max_working_hours',
        'color',
        'status'
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function employeeShiftHistories()
    {
        return $this->hasMany(EmployeeShiftHistory::class);
    }

    public function shiftPolicies()
    {
        return $this->hasMany(ShiftPolicy::class, 'default_shift_id');
    }
}