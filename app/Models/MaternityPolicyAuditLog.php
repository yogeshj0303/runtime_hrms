<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaternityPolicyAuditLog extends Model
{
    protected $fillable = [
        'business_id',
        'user_id',
        'child_type',
        'field_name',
        'old_value',
        'new_value',
        'updated_by',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
