<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpdeskPermissionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'employee_id',
        'reason',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
