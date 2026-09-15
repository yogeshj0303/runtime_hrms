<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExitReason extends Model
{
    protected $fillable = [

        'user_id',
        'business_id',
        'reason_name',
        'esi_mapping',
    ];
}