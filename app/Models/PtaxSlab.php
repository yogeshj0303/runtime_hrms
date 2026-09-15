<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class PtaxSlab extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'business_id',
        'user_id',
        'state',
        'effective_date',
        'salary_from',
        'salary_to',
        'tax_amount',
        'month',
        'gender'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
