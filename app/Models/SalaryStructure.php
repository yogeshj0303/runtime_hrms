<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryStructure extends Model
{
    protected $fillable = [

        'business_id',

        'auth_id',

        'structure_name'

    ];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function rules()
    {
        return $this->hasMany(
            SalaryStructureRule::class
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