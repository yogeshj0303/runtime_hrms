<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $fillable = [
        'user_id',
        'business_id',
        'name',
        'short_name',
        'color',
        'description',
        'status',
        'is_paid_leave',
        'maintain_leave_balance',
        'allow_leave_requests',
        'allow_future_requests',
        'probation_rule',
        'advance_leave_days',
        'past_request_days',
        'monthly_limit',
        'track_balance',
        'request_limit',
    ];

    protected $casts = [
        'is_paid_leave' => 'boolean',
        'maintain_leave_balance' => 'boolean',
        'allow_leave_requests' => 'boolean',
        'allow_future_requests' => 'boolean',
        'track_balance' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'leave_type_id');
    }

    public function policies()
    {
        return $this->hasMany(LeavePolicy::class);
    }
}