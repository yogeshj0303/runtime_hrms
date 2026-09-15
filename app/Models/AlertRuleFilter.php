<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertRuleFilter extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'alert_rule_id',
        'filter_type',
        'filter_value'
    ];

    public function rule()
    {
        return $this->belongsTo(AlertRule::class, 'alert_rule_id');
    }
}
