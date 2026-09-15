<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryUnitDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_salary_unit_id',
        'quantity',
        'comment',
        'capture_date',
    ];

    public function employeeSalaryUnit()
    {
        return $this->belongsTo(EmployeeSalaryUnit::class, 'employee_salary_unit_id');
    }
}
