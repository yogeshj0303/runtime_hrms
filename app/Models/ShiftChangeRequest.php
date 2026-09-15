<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftChangeRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(User::class,'employee_id');
    }

    public function currentShift()
    {
        return $this->belongsTo(Shift::class,'current_shift_id');
    }

    public function requestedShift()
    {
        return $this->belongsTo(Shift::class,'requested_shift_id');
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