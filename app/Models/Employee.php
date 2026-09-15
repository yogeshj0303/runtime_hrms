<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_id',
        'employee_code',
        'business_code',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'dob',
        'email',
        'phone',
        'address',
        'joining_date',
        'confirmation_date',
        'designation',
        'department',
        'salary',
        'created_by',
        'auth_id',
        'exit_date',
        'exit_reason_id',
        'status',
        'flight_risk_status'
    ];

    protected $casts = [
        'dob' => 'date',
        'joining_date' => 'date',
        'confirmation_date' => 'date',
        'exit_date' => 'date',
        'salary' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($employee) {
            if ($employee->business_id && empty($employee->business_code)) {
                $business = Business::find($employee->business_id);
                if ($business) {
                    $employee->business_code = $business->business_code;
                }
            }
        });
    }

    public function exitReason()
    {
        return $this->belongsTo(ExitReason::class, 'exit_reason_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function authUser()
    {
        return $this->belongsTo(User::class, 'auth_id');
    }

    public function profile()
    {
        return $this->hasOne(EmployeeProfile::class);
    }



    public function addresses()
    {
        return $this->hasMany(EmployeeAddress::class);
    }

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function assets()
    {
        return $this->hasMany(EmployeeAsset::class);
    }

    public function salaryRevisions()
    {
        return $this->hasMany(EmployeeSalaryRevision::class);
    }

    public function workProfiles()
    {
        return $this->hasMany(EmployeeWorkProfile::class);
    }

    public function currentWorkProfile()
    {
        return $this->hasOne(EmployeeWorkProfile::class)->where('is_current', true);
    }

    public function policyAssignment()
    {
        return $this->hasOne(EmployeePolicyAssignment::class);
    }

    public function policy()
    {
        return $this->hasOne(EmployeePolicy::class)->where('is_current', true);
    }

    public function policies()
    {
        return $this->hasMany(EmployeePolicy::class);
    }

    public function policyAssignments()
    {
        return $this->hasMany(EmployeePolicyAssignment::class);
    }

    public function familyMembers()
    {
        return $this->hasMany(EmployeeFamilyMember::class);
    }

    public function identity()
    {
        return $this->hasOne(EmployeeIdentity::class);
    }

    public function permission()
    {
        return $this->hasOne(EmployeePermission::class);
    }

    public function loginAccess()
    {
        return $this->hasOne(EmployeeLoginAccess::class);
    }

    public function additionalInformation()
    {
        return $this->hasOne(EmployeeAdditionalInformation::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(EmployeeActivityLog::class);
    }

    public function designationInfo()
    {
        // Assuming 'designation' column stores the ID of the designation
        return $this->belongsTo(Designation::class, 'designation', 'id');
    }
}