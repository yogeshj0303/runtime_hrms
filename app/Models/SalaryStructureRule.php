<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryStructureRule extends Model
{
    protected $fillable = [

        'salary_structure_id',

        'business_id',

        'auth_id',

        'salary_component_id',

        'order_no',

        'condition_component',

        'condition_operator',

        'condition_value',

        'calculate_percentage',

        'base_component',

        'minimum_amount',

        'maximum_amount',

        'do_not_exceed_gross_salary'

    ];


    protected $casts = [

        'do_not_exceed_gross_salary' => 'boolean'
    ];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function structure()
    {
        return $this->belongsTo(
            SalaryStructure::class,
            'salary_structure_id'
        );
    }


    public function component()
    {
        return $this->belongsTo(
            SalaryComponent::class,
            'salary_component_id'
        );
    }


    public function business()
    {
        return $this->belongsTo(
            Business::class
        );
    }


    public function user()
    {
        return $this->belongsTo(
            User::class,
            'auth_id'
        );
    }
}