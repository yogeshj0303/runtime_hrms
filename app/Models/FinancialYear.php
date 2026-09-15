<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'user_id',
        'year',
        'start_date',
        'end_date',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function taxSlabs()
    {
        return $this->hasMany(TaxSlab::class);
    }

    public function incomeTaxSetting()
    {
        return $this->hasOne(IncomeTaxSetting::class);
    }
}
