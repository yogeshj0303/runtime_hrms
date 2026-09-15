<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualAttendanceHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'manual_attendance_id',
        'changed_by',
        'action',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function manualAttendance()
    {
        return $this->belongsTo(ManualAttendance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
