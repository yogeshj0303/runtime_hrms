<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertTemplate extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'alert_rule_id',
        'channel',
        'subject',
        'body'
    ];

    public function rule()
    {
        return $this->belongsTo(AlertRule::class, 'alert_rule_id');
    }
}
