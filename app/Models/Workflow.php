<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    protected $fillable = [

        'user_id',
        'business_id',
        'workflow_name',
        'description',
        'is_active'
    ];
}