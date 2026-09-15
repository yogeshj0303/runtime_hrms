<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRelaxation extends Model
{
    protected $fillable = [

        'business_id',

        'employee_id',

        'shift_id',

        'attendance_date',

        'relaxation_minutes',

        'reason',

        'status',

        'approved_by',

        'approved_at'
    ];

    protected $casts = [

        'attendance_date'=>'date',

        'approved_at'=>'datetime'
    ];

    public function employee()
    {
        return $this->belongsTo(
            User::class,
            'employee_id'
        );
    }

    public function shift()
    {
        return $this->belongsTo(
            Shift::class
        );
    }
}