<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OvertimePolicy extends Model
{
    protected $fillable = [

        'business_id',

        'auth_id',

        'policy_name',

        'salary_treatment',

        'days_in_month',

        'hours_in_day'

    ];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function rules()
    {
        return $this->hasMany(
            OvertimeRule::class,
            'overtime_policy_id'
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