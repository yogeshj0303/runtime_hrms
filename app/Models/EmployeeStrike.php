<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeStrike extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'employee_id',
        'strike_rule_id',
        'strike_date',
        'reason',
        'status',
    ];

    protected $casts = [
        'strike_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function strikeRule()
    {
        return $this->belongsTo(StrikeRule::class);
    }
}
