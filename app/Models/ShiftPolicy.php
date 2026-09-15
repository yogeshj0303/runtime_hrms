<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftPolicy extends Model
{
    protected $fillable = [
        'user_id',
        'business_id',
        'name',
        'description',
        'is_default',
        'default_shift_id',
        'monday_shift_id',
        'tuesday_shift_id',
        'wednesday_shift_id',
        'thursday_shift_id',
        'friday_shift_id',
        'saturday_shift_id',
        'sunday_shift_id',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function defaultShift()
    {
        return $this->belongsTo(Shift::class, 'default_shift_id');
    }

    public function mondayShift()
    {
        return $this->belongsTo(Shift::class, 'monday_shift_id');
    }

    public function tuesdayShift()
    {
        return $this->belongsTo(Shift::class, 'tuesday_shift_id');
    }

    public function wednesdayShift()
    {
        return $this->belongsTo(Shift::class, 'wednesday_shift_id');
    }

    public function thursdayShift()
    {
        return $this->belongsTo(Shift::class, 'thursday_shift_id');
    }

    public function fridayShift()
    {
        return $this->belongsTo(Shift::class, 'friday_shift_id');
    }

    public function saturdayShift()
    {
        return $this->belongsTo(Shift::class, 'saturday_shift_id');
    }

    public function sundayShift()
    {
        return $this->belongsTo(Shift::class, 'sunday_shift_id');
    }
}
