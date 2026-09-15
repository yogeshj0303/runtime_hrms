<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrikeRule extends Model
{
    protected $fillable = [
        'user_id',
        'business_id',
        'rule_type',
        'from_occurrences',
        'to_occurrences',
        'strike_color',
        'deduction_type',
        'deduction_value',
        'warning_letter',
        'is_active',
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'deduction_value' => 'decimal:2',
    ];

    /**
     * Strike colour → Bootstrap badge class mapping.
     */
    public function getColorBadgeAttribute(): string
    {
        return match ($this->strike_color) {
            'Yellow' => 'bg-warning text-dark',
            'Orange' => 'bg-orange text-white',
            'Red'    => 'bg-danger',
            'Blue'   => 'bg-primary',
            'Green'  => 'bg-success',
            default  => 'bg-secondary',
        };
    }

    /**
     * Human-readable occurrence range label.
     * e.g. "1 – 3" or "4 onwards"
     */
    public function getOccurrenceLabelAttribute(): string
    {
        if ($this->to_occurrences === 0) {
            return $this->from_occurrences . ' onwards';
        }
        return $this->from_occurrences . ' – ' . $this->to_occurrences;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
