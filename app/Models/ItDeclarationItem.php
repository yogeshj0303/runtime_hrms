<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItDeclarationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'name',
        'max_limit',
        'is_active',
    ];
}
