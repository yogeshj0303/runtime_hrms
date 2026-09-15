<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeItExemption extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'financial_year',
        'additional_exemptions'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
