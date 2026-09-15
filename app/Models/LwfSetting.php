<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LwfSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_id',
        'state',
        'effective_from',
        'employee_contribution_type',
        'employee_contribution_amount',
        'employee_contribution_rate',
        'employer_contribution_type',
        'employer_contribution_amount',
        'employer_contribution_rate',
        'salary_limit',
        'deduction_frequency',
        'deduction_month',
        'calculation_method',
        'rounding_method',
        'notes',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'effective_from' => 'date',
        'employee_contribution_amount' => 'decimal:2',
        'employee_contribution_rate' => 'decimal:4',
        'employer_contribution_amount' => 'decimal:2',
        'employer_contribution_rate' => 'decimal:4',
        'salary_limit' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
