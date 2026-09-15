<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryVariableDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_salary_variable_id',
        'amount',
        'comments'
    ];

    public function employeeSalaryVariable()
    {
        return $this->belongsTo(EmployeeSalaryVariable::class);
    }
}
