<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryComponent extends Model
{
    protected $fillable = [
        'business_id',
        'auth_id',
        'name',
        'short_name',
        'unit_type',
        'active',
        'exclude_from_gross_salary',
        'hide_in_ctc_reports',
        'not_payable'
    ];

    protected $casts = [
        'active' => 'boolean',
        'exclude_from_gross_salary' => 'boolean',
        'hide_in_ctc_reports' => 'boolean',
        'not_payable' => 'boolean'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'auth_id');
    }
    public function structureRules()
    {
        return $this->hasMany(
            SalaryStructureRule::class,
            'salary_component_id'
        );
    }

    public function esiBusinesses()
    {
        return $this->belongsToMany(Business::class, 'business_esi_components', 'salary_component_id', 'business_id')->withTimestamps();
    }

    public function epfBusinesses()
    {
        return $this->belongsToMany(Business::class, 'business_epf_components', 'salary_component_id', 'business_id')->withTimestamps();
    }

    public function ptaxBusinesses()
    {
        return $this->belongsToMany(Business::class, 'business_ptax_components', 'salary_component_id', 'business_id')->withTimestamps();
    }

    public function lwfBusinesses()
    {
        return $this->belongsToMany(Business::class, 'business_lwf_components', 'salary_component_id', 'business_id')->withTimestamps();
    }

    public function salaryTaxMappings()
    {
        return $this->hasMany(SalaryTaxMapping::class);
    }
}