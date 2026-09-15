<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OvertimeRule extends Model
{
    protected $fillable = [

        'overtime_policy_id',

        'business_id',

        'auth_id',

        'attendance_type',

        'time_basis',

        'from_hours',

        'from_minutes',

        'to_hours',

        'to_minutes',

        'calculation_method',

        'multiplier',

        'overtime_min_type',

        'overtime_minutes'

    ];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function policy()
    {
        return $this->belongsTo(
            OvertimePolicy::class,
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
