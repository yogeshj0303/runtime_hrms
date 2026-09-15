<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertRule extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'company',
        'name',
        'category',
        'is_active',
        'priority',
        'reminder_days_before',
        'repeat_frequency_days',
        'auto_close_days',
        'escalation_enabled',
        'effective_date',
        'expiry_date',
        'channels'
    ];
    
    protected $casts = [
        'channels' => 'array',
        'is_active' => 'boolean',
        'escalation_enabled' => 'boolean'
    ];

    public function filters()
    {
        return $this->hasMany(AlertRuleFilter::class);
    }

    public function templates()
    {
        return $this->hasMany(AlertTemplate::class);
    }
}
