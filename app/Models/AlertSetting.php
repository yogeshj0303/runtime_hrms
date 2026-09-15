<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertSetting extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'company',
        'enable_dashboard',
        'enable_email',
        'enable_sms',
        'enable_whatsapp',
        'enable_push',
        'escalation_manager_id'
    ];
}
