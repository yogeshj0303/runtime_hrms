<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeTaxSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'user_id',
        'enable_tds_deduction',
        'declaration_window_open',
        'financial_year_id',
        'window_start_date',
        'window_end_date'
    ];

    protected $casts = [
        'enable_tds_deduction' => 'boolean',
        'declaration_window_open' => 'boolean',
        'window_start_date' => 'date',
        'window_end_date' => 'date'
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
