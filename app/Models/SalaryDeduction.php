<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryDeduction extends Model
{
    protected $fillable = [

        'business_id',

        'auth_id',

        'name',

        'short_name',

        'deduction_type',

        'active'
    ];

    protected $casts = [

        'active'=>'boolean'
    ];
}