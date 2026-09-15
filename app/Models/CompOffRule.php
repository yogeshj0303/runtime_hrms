<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompOffRule extends Model
{
    protected $fillable = [
        'user_id',
        'business_id',

        // Weekly Off
        'wo_auto_grant_comp_off',
        'wo_half_day_min_hours',
        'wo_half_day_min_minutes',
        'wo_full_day_min_hours',
        'wo_full_day_min_minutes',
        'wo_grant_comp_off',
        'wo_add_to_extra_days',

        // Holiday
        'ho_auto_grant_comp_off',
        'ho_half_day_min_hours',
        'ho_half_day_min_minutes',
        'ho_full_day_min_hours',
        'ho_full_day_min_minutes',
        'ho_grant_comp_off',
        'ho_add_to_extra_days',

        // Lapse
        'lapse_after_days',
        'lapse_at_month_end',
    ];

    protected $casts = [
        'wo_auto_grant_comp_off' => 'boolean',
        'wo_add_to_extra_days'   => 'boolean',
        'ho_auto_grant_comp_off' => 'boolean',
        'ho_add_to_extra_days'   => 'boolean',
        'lapse_at_month_end'     => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
