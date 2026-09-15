<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeekOffPolicy extends Model
{
    protected $fillable = [
        'user_id',
        'business_id',
        'name',
        'description',
        'is_default',
        'is_payable',
        'sunday_weeks',
        'monday_weeks',
        'tuesday_weeks',
        'wednesday_weeks',
        'thursday_weeks',
        'friday_weeks',
        'saturday_weeks',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_payable' => 'boolean',
        'sunday_weeks' => 'array',
        'monday_weeks' => 'array',
        'tuesday_weeks' => 'array',
        'wednesday_weeks' => 'array',
        'thursday_weeks' => 'array',
        'friday_weeks' => 'array',
        'saturday_weeks' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
