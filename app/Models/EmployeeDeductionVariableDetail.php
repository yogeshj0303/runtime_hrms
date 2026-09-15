<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDeductionVariableDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_deduction_variable_id',
        'amount',
        'comments'
    ];

    public function deductionVariable()
    {
        return $this->belongsTo(EmployeeDeductionVariable::class, 'employee_deduction_variable_id');
    }
}
