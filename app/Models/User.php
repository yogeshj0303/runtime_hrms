<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar','active_business_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token','fcm_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function businesses()
{
    return $this->hasMany(Business::class);
}
public function businessSessions()
{
    return $this->hasMany(UserBusinessSession::class);
}
public function businessUnits()
{
    return $this->hasMany(BusinessUnit::class);
}
public function employee()
{
    return $this->hasOne(Employee::class);
}

public function tds24qInfos()
{
    return $this->hasMany(Tds24QInfo::class);
}

public function form16Infos()
{
    return $this->hasMany(Form16Info::class);
}
public function employeeDocuments()
{
    return $this->hasMany(EmployeeDocument::class, 'employee_id');
}
public function shiftHistories()
{
    return $this->hasMany(EmployeeShiftHistory::class, 'employee_id');
}

public function assignedShiftHistories()
{
    return $this->hasMany(EmployeeShiftHistory::class, 'assigned_by');
}
public function salaryStructures()
{
    return $this->hasMany(
        SalaryStructure::class,
        'auth_id'
    );
}
public function overtimePolicies()
{
    return $this->hasMany(
        OvertimePolicy::class,
        'auth_id'
    );
}

public function salaryClaims()
    {
        return $this->hasMany(SalaryClaim::class);
    }

public function claimComponents()
    {
        return $this->hasMany(ClaimComponent::class, 'auth_id');
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

    public function financialYears()
    {
        return $this->hasMany(FinancialYear::class);
    }

    public function taxSlabs()
    {
        return $this->hasMany(TaxSlab::class);
    }
}
