<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'department_id',
        'creator_id',
        'assignee_id',
        'subject',
        'description',
        'priority',
        'status',
        'closed_at'
    ];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(Employee::class, 'creator_id');
    }

    public function assignee()
    {
        return $this->belongsTo(Employee::class, 'assignee_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }

    public function histories()
    {
        return $this->hasMany(TicketHistory::class);
    }
}
