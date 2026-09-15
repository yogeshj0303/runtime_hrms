<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryTaxMapping extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'user_id',
        'salary_component_id',
        'basic',
        'hra',
        'profit',
        'perk',
        'entire_taxable',
        'exempt',
        'new_exempt'
    ];

    protected $casts = [
        'basic' => 'boolean',
        'hra' => 'boolean',
        'profit' => 'boolean',
        'perk' => 'boolean',
        'entire_taxable' => 'boolean',
        'exempt' => 'boolean',
        'new_exempt' => 'boolean'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salaryComponent()
    {
        return $this->belongsTo(SalaryComponent::class);
    }
}
