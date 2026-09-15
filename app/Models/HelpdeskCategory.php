<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpdeskCategory extends Model
{
    protected $fillable = [

        'user_id',
        'business_id',
        'category_name',
        'primary_approver',
        'backup_approver',
        'is_active'
    ];
}