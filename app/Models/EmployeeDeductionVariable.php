<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDeductionVariable extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'salary_deduction_id',
        'payroll_month',
        'total_amount'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function salaryDeduction()
    {
        return $this->belongsTo(SalaryDeduction::class);
    }

    public function details()
    {
        return $this->hasMany(EmployeeDeductionVariableDetail::class, 'employee_deduction_variable_id');
    }
}
