<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeShiftHistory extends Model
{
    use HasFactory;

    public function business()
{
    return $this->belongsTo(Business::class);
}

public function employee()
{
    return $this->belongsTo(User::class, 'employee_id');
}

public function shift()
{
    return $this->belongsTo(Shift::class);
}

public function assigner()
{
    return $this->belongsTo(User::class, 'assigned_by');
}
}
