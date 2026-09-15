<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSetting extends Model
{
    protected $fillable = [
        'user_id',
        'business_id',
        'default_attendance',
        'mark_out_every_second_punch',
        'manual_attendance_enabled',
        'enable_manual_attendance',
        'holiday_sandwich_rule',
        'holiday_absent_both_days',
        'holiday_restrict_one_day',
        'week_off_sandwich_rule',
        'week_off_absent_both_days',
        'week_off_restrict_one_day',
    ];

    protected $casts = [
        'mark_out_every_second_punch' => 'boolean',
        'manual_attendance_enabled' => 'boolean',
        'enable_manual_attendance' => 'boolean',
        'holiday_sandwich_rule' => 'boolean',
        'holiday_absent_both_days' => 'boolean',
        'holiday_restrict_one_day' => 'boolean',
        'week_off_sandwich_rule' => 'boolean',
        'week_off_absent_both_days' => 'boolean',
        'week_off_restrict_one_day' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
