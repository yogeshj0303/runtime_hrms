<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryClaim extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'business_id',
        'user_id',
        'claim_component_id',
        'claim_approver',
        'enable_claim_request',
        'request_limit',
        'monthly_limit',
        'employee_limit',
        'allow_all_grades',
        'allowed_grades',
        'status',
    ];

    protected $casts = [
        'enable_claim_request' => 'boolean',
        'allow_all_grades'     => 'boolean',
        'allowed_grades'       => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function claimComponent()
    {
        return $this->belongsTo(ClaimComponent::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'claim_approver');
    }
}
