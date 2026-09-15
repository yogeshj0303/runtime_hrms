<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryRevision extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'allowances' => 'array',
        'contributions' => 'array',
        'salary_options' => 'array',
        'custom_components' => 'array',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function salaryStructure()
    {
        return $this->belongsTo(SalaryStructure::class, 'salary_structure_id');
    }
}
