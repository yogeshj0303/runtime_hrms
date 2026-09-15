<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PtaxSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'user_id',
        'is_enabled',
        'calculation_basis'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
