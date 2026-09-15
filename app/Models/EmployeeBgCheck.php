<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeBgCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'check_type',
        'agency_name',
        'status',
        'remarks'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
