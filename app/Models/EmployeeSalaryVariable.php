<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Business;
use App\Models\Employee;

class EmployeeSalaryVariable extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'employee_id',
        'salary_component_id',
        'payroll_month',
        'amount',
        'is_arrear',
        'comments'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function salaryComponent()
    {
        return $this->belongsTo(SalaryComponent::class);
    }

    public function details()
    {
        return $this->hasMany(EmployeeSalaryVariableDetail::class);
    }
}
