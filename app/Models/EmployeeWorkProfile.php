<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeWorkProfile extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function businessUnit()
    {
        return $this->belongsTo(\App\Models\BusinessUnit::class, 'business_unit_id');
    }

    public function location()
    {
        return $this->belongsTo(\App\Models\Location::class, 'location_id');
    }

    public function department()
    {
        return $this->belongsTo(\App\Models\Department::class, 'department_id');
    }

    public function designation()
    {
        return $this->belongsTo(\App\Models\Designation::class, 'designation_id');
    }

    public function costCenter()
    {
        return $this->belongsTo(\App\Models\CostCenter::class, 'cost_center_id');
    }

    public function reportingManager()
    {
        return $this->belongsTo(Employee::class, 'reporting_manager_id');
    }

    public function hrManager()
    {
        return $this->belongsTo(Employee::class, 'hr_manager_id');
    }

    public function indirectManager()
    {
        return $this->belongsTo(Employee::class, 'indirect_manager_id');
    }

    public function grade()
    {
        return $this->belongsTo(\App\Models\Grade::class, 'grade_id');
    }
}
