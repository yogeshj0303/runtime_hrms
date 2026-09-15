<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSeparation extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'resignation_date',
        'exit_date',
        'exit_reason',
        'retention_attempted',
        'status',
        'remarks',
    ];

    protected $casts = [
        'resignation_date' => 'date',
        'exit_date' => 'date',
        'retention_attempted' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
