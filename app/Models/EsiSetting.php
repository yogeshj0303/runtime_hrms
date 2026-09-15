<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EsiSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_id',
        'effective_from',
        'employee_contribution',
        'employer_contribution',
        'gross_wage_ceiling',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'effective_from' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
