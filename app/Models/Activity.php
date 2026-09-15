<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'table_name',
        'table_id',
        'title',
        'description',
        'ip',
        'user_id',
        'user_type',
    ];
}