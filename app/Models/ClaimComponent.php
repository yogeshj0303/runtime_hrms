<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClaimComponent extends Model
{
    protected $fillable = [
        'business_id',
        'auth_id',
        'name',
        'short_name',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'auth_id');
    }

    public function salaryClaims()
    {
        return $this->hasMany(SalaryClaim::class);
    }
}
