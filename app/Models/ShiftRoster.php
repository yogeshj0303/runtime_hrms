<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftRoster extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'employee_id',
        'shift_id',
        'roster_date',
        'assigned_by',
        'is_published',
        'remarks',
    ];

    protected $casts = [
        'roster_date' => 'date',
        'is_published' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
