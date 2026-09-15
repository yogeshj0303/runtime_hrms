<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceDailyDetail extends Model
{
    use HasFactory;

    protected $table = 'attendance_daily_details';

    protected $fillable = [
        'attendance_daily_id',
        'business_id',
        'employee_id',
        'punch_in_time',
        'punch_out_time',
        'punch_in_location',
        'punch_out_location',
        'punch_in_latitude',
        'punch_in_longitude',
        'punch_out_latitude',
        'punch_out_longitude',
        'device_name',
        'ip_address',
        'total_working_time',
        'status_daily',
        'remark',
    ];

    protected $casts = [
        'punch_in_time' => 'datetime',
        'punch_out_time' => 'datetime',
        'punch_in_latitude' => 'decimal:8',
        'punch_in_longitude' => 'decimal:8',
        'punch_out_latitude' => 'decimal:8',
        'punch_out_longitude' => 'decimal:8',
    ];

    /**
     * Attendance Daily
     */
    public function attendanceDaily()
    {
        return $this->belongsTo(
            AttendanceDaily::class,
            'attendance_daily_id'
        );
    }

    /**
     * Employee
     */
    public function employee()
    {
        return $this->belongsTo(
            User::class,
            'employee_id'
        );
    }

    /**
     * Business
     */
    public function business()
    {
        return $this->belongsTo(
            Business::class,
            'business_id'
        );
    }
}