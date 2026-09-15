<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EpfSetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'business_id',
        'is_enabled',
        'effective_from',
        'employee_contribution_rate',
        'employer_contribution_rate',
        'pension_contribution_rate',
        'edli_contribution_rate',
        'admin_charges_rate',
        'wage_ceiling',
        'senior_citizen_age',
        'senior_employee_contribution_rate',
        'senior_employer_contribution_rate',
        'senior_pension_contribution_rate',
        'calculation_method',
        'rounding_method',
        'notes',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'effective_from' => 'date',
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
