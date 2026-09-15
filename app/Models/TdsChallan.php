<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TdsChallan extends Model
{
    use HasFactory;

    protected $fillable = [
        'financial_year',
        'month',
        'bsr_code',
        'deposit_date',
        'challan_serial'
    ];
}
