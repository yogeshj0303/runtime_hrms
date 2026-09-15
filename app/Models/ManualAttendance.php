<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'employee_id',
        'month_year',
        'present_days',
        'absent_days',
        'holiday_days',
        'week_off_days',
        'leave_days',
    ];

    protected $casts = [
        'leave_days' => 'array',
        'present_days' => 'decimal:1',
        'absent_days' => 'decimal:1',
        'holiday_days' => 'decimal:1',
        'week_off_days' => 'decimal:1',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function histories()
    {
        return $this->hasMany(ManualAttendanceHistory::class);
    }
}
