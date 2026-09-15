<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TdsReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'financial_year',
        'quarter',
        'receipt_number'
    ];
}
