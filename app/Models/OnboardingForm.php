<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'form_name',
        'status',
        'token',
        'part_a_data',
        'part_b_data',
        'part_c_data',
    ];

    protected $casts = [
        'part_a_data' => 'array',
        'part_b_data' => 'array',
        'part_c_data' => 'array',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
