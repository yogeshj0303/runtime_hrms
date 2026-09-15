<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeExtraDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'payroll_month',
        'extra_days',
        'arrear_days',
        'ot_days',
        'comments'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
