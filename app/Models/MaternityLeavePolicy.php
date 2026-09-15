<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaternityLeavePolicy extends Model
{
    use SoftDeletes;

    // ── Ordered display labels ──────────────────────────────────
    public const CHILD_TYPES = [
        'first_child'      => 'First Child',
        'second_child'     => 'Second Child',
        'third_child'      => 'Third Child',
        'subsequent_child' => 'Subsequent Child',
    ];

    protected $fillable = [
        'business_id',
        'user_id',
        'child_type',
        'normal_leave_weeks',
        'adoption_leave_weeks',
        'surrogacy_leave_weeks',
        'tubectomy_leave_weeks',
        'miscarriage_leave_weeks',
        'miscarriage_over_and_above',
        'maternity_extension_days',
        'allow_extension',
        'minimum_working_days',
        'leave_split',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'miscarriage_over_and_above' => 'boolean',
        'allow_extension'            => 'boolean',
        'leave_split'                => 'boolean',
    ];

    // ── Accessor: human-readable child type label ───────────────
    public function getChildTypeLabelAttribute(): string
    {
        return self::CHILD_TYPES[$this->child_type] ?? ucfirst(str_replace('_', ' ', $this->child_type));
    }

    // ── Relations ───────────────────────────────────────────────
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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
