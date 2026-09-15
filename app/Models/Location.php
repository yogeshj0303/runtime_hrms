<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [

        'user_id',
        'business_id',

        'name',
        'state',

        'site_head',
        'deputy_head',

        'is_default',
        
        'latitude',
        'longitude',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function workProfiles()
    {
        return $this->hasMany(EmployeeWorkProfile::class, 'location_id');
    }
}