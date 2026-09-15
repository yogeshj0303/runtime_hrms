<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxSlab extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'user_id',
        'financial_year_id',
        'scheme',
        'age_category',
        'income_from',
        'income_to',
        'fixed_tax',
        'tax_percentage',
        'cess',
        'surcharge',
        'rebate',
        'sort_order'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function financialYear()
    {
        return $this->belongsTo(FinancialYear::class);
    }
}
