<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePolicyAssignment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'leave_policy_ids' => 'array',
        'auto_shift_selection' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
