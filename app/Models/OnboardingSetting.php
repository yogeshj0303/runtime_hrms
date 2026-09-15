<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'key',
        'is_required',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];
}
