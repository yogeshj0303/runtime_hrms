<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MissingPunchRequest extends Model
{
    protected $fillable = [
        'business_id',
        'employee_id',
        'attendance_date',
        'requested_punch_in',
        'requested_punch_out',
        'request_type',
        'reason',
        'status',
        'approved_by',
        'admin_remark',
        'approved_at'
    ];

    public function employee()
    {
        return $this->belongsTo(User::class,'employee_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class,'approved_by');
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}