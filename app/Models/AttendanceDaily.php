<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceDaily extends Model
{
    use HasFactory;

    protected $table = 'attendance_dailies';

    protected $fillable = [
        'business_id',
        'employee_id',
        'attendance_date',
        'day',
        'status',
        'working_time_for_day',
        'total_working_time',
        'is_late',
        'late_minutes',
        'remark',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'is_late' => 'boolean',
    ];

    /**
     * Business Relationship
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Employee Relationship
     */
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    /**
     * Attendance Details Relationship
     */
    public function details()
    {
        return $this->hasMany(
            AttendanceDailyDetail::class,
            'attendance_daily_id'
        );
    }
}