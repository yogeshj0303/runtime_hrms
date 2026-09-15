<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeRule extends Model
{
    protected $fillable = [
        'user_id',
        'business_id',
        'time_basis',
        'from_hours',
        'from_minutes',
        'to_hours',
        'to_minutes',
        'mark_only_if_present',
        'is_active',
        'warning_occurrences',
        'warning_letter',
        'attendance_occurrences',
        'attendance_status',
        'attendance_update_type',
    ];

    protected $casts = [
        'mark_only_if_present' => 'boolean',
        'is_active' => 'boolean',
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
