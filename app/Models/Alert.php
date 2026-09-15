<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'alert_rule_id',
        'user_id',
        'employee_id',
        'reference_type',
        'reference_id',
        'message',
        'status',
        'read_at',
        'resolved_at',
        'resolved_by'
    ];
    
    protected $casts = [
        'read_at' => 'datetime',
        'resolved_at' => 'datetime'
    ];

    public function rule()
    {
        return $this->belongsTo(AlertRule::class, 'alert_rule_id');
    }
    
    public function targetUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function reference()
    {
        return $this->morphTo();
    }
    
    public function logs()
    {
        return $this->hasMany(AlertLog::class);
    }
}
