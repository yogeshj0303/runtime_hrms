<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBusinessSession extends Model
{
    protected $fillable = [
        'user_id',
        'business_id',
        'status','user_agent','ip_address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}