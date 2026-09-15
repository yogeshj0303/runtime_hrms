<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $fillable = [
    'user_id',
    'business_name',
    'business_code',
    'pan_number',
    'address',
    'city',
    'pincode',
    'state',
    'business_constitution',
    'current_step',
    'is_completed',
    'status',   'district',
    'max_employees',
];

    protected static function booted()
    {
        static::created(function ($business) {
            $code = 'BUS' . str_pad($business->id, 4, '0', STR_PAD_LEFT);
            $business->update(['business_code' => $code]);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function businessSessions()
{
    return $this->hasMany(UserBusinessSession::class);
}
public function businessUnits()
{
    return $this->hasMany(BusinessUnit::class);
}
public function employeeShiftHistories()
{
    return $this->hasMany(EmployeeShiftHistory::class);
}
public function salaryStructures()
{
    return $this->hasMany(
        SalaryStructure::class
    );
}
public function overtimePolicies()
{
    return $this->hasMany(
        OvertimePolicy::class
    );
}

public function salaryClaims()
    {
        return $this->hasMany(
            SalaryClaim::class
        );
    }

public function shifts()
    {
        return $this->hasMany(Shift::class);
    }

public function shiftPolicies()
    {
        return $this->hasMany(ShiftPolicy::class);
    }

public function timeRules()
    {
        return $this->hasMany(TimeRule::class);
    }

public function weekOffPolicies()
    {
        return $this->hasMany(WeekOffPolicy::class);
    }

public function attendanceSetting()
    {
        return $this->hasOne(AttendanceSetting::class);
    }

    public function tds24QInfo()
    {
        return $this->hasOne(Tds24QInfo::class);
    }

    public function form16Info()
    {
        return $this->hasOne(Form16Info::class);
    }

public function leaveTypes()
    {
        return $this->hasMany(LeaveType::class);
    }

public function compOffRule()
    {
        return $this->hasOne(CompOffRule::class);
    }

public function strikeRules()
    {
        return $this->hasMany(StrikeRule::class);
    }

    public function maternityLeavePolicies()
    {
        return $this->hasMany(MaternityLeavePolicy::class);
    }

    public function esiSettings()
    {
        return $this->hasMany(EsiSetting::class);
    }

    public function epfSettings()
    {
        return $this->hasMany(EpfSetting::class);
    }

    public function ptaxSetting()
    {
        return $this->hasOne(PtaxSetting::class);
    }

    public function lwfSettings()
    {
        return $this->hasMany(LwfSetting::class);
    }

    public function lwfSalaryComponents()
    {
        return $this->belongsToMany(SalaryComponent::class, 'business_lwf_components', 'business_id', 'salary_component_id')->withTimestamps();
    }

    public function ptaxComponents()
    {
        return $this->belongsToMany(SalaryComponent::class, 'business_ptax_components', 'business_id', 'salary_component_id')->withTimestamps();
    }

    public function ptaxSlabs()
    {
        return $this->hasMany(PtaxSlab::class);
    }

    public function financialYears()
    {
        return $this->hasMany(FinancialYear::class);
    }

    public function taxSlabs()
    {
        return $this->hasMany(TaxSlab::class);
    }

    public function salaryTaxMappings()
    {
        return $this->hasMany(SalaryTaxMapping::class);
    }

    public function incomeTaxSettings()
    {
        return $this->hasMany(IncomeTaxSetting::class);
    }
}