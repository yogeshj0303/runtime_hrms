<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

class WorkProfileController extends Controller
{
    public function index(Request $request)
    {
        $employee = Employee::with(['workProfiles' => function($q) {
            $q->orderBy('effective_from', 'desc');
        }])->findOrFail($request->id);

        $businessId = $employee->business_id;

        $businessUnits = \App\Models\BusinessUnit::where('business_id', $businessId)->get();
        $locations = \App\Models\Location::where('business_id', $businessId)->get();
        $costCenters = \App\Models\CostCenter::where('business_id', $businessId)->get();
        $departments = \App\Models\Department::where('business_id', $businessId)->get();
        $grades = \App\Models\Grade::where('business_id', $businessId)->get();
        $designations = \App\Models\Designation::where('business_id', $businessId)->get();
        $managers = Employee::where('id', '!=', $employee->id)->where('business_id', $businessId)->get();

        return view('admin.employee.profile.work-profile', compact(
            'employee', 'businessUnits', 'locations', 'costCenters', 
            'departments', 'grades', 'designations', 'managers'
        ));
    }

    public function store(Request $request)
    {
        $employee = Employee::findOrFail($request->id);

        // Mark previous as not current
        $employee->workProfiles()->update(['is_current' => false]);

        // Parse month-year for effective_from
        $effectiveFrom = \Carbon\Carbon::parse($request->effective_from . '-01')->format('Y-m-d');
        
        $isPromotion = $request->has('is_promotion');

        // Create new active work profile
        $employee->workProfiles()->create([
            'business_unit_id' => $request->business_unit_id,
            'location_id' => $request->location_id,
            'cost_center_id' => $request->cost_center_id,
            'department_id' => $request->department_id,
            'grade_id' => $request->grade_id,
            'designation_id' => $request->designation_id,
            'reporting_manager_id' => $request->reporting_manager_id,
            'hr_manager_id' => $request->hr_manager_id,
            'indirect_manager_id' => $request->indirect_manager_id,
            'effective_from' => $effectiveFrom,
            'is_current' => true,
            'is_promotion' => $isPromotion,
        ]);
        
        if ($isPromotion) {
            $designation = \App\Models\Designation::find($request->designation_id);
            $designationName = $designation ? $designation->name : 'a new role';
            
            \App\Models\WallPost::create([
                'business_id' => $employee->business_id,
                'type' => 'general',
                'content' => '🎉 Congratulations to ' . $employee->first_name . ' ' . $employee->last_name . ' on their promotion to ' . $designationName . '!',
                'target_employee_id' => $employee->id,
            ]);
        }

        return redirect()->back()->with('success', 'Profile successfully updated');
    }

    public function update(Request $request, $work_profile_id)
    {
        $employee = Employee::findOrFail($request->id);
        $workProfile = $employee->workProfiles()->findOrFail($work_profile_id);

        $effectiveFrom = \Carbon\Carbon::parse($request->effective_from . '-01')->format('Y-m-d');
        
        $isPromotion = $request->has('is_promotion');

        $workProfile->update([
            'business_unit_id' => $request->business_unit_id,
            'location_id' => $request->location_id,
            'cost_center_id' => $request->cost_center_id,
            'department_id' => $request->department_id,
            'grade_id' => $request->grade_id,
            'designation_id' => $request->designation_id,
            'reporting_manager_id' => $request->reporting_manager_id,
            'hr_manager_id' => $request->hr_manager_id,
            'indirect_manager_id' => $request->indirect_manager_id,
            'effective_from' => $effectiveFrom,
            'is_promotion' => $isPromotion,
        ]);

        return redirect()->back()->with('success', 'Work profile revision updated successfully');
    }

    public function destroy(Request $request, $work_profile_id)
    {
        $employee = Employee::findOrFail($request->id);
        $workProfile = $employee->workProfiles()->findOrFail($work_profile_id);
        
        $workProfile->delete();

        // Ensure there's a current profile if we deleted the current one
        if ($workProfile->is_current) {
            $latest = $employee->workProfiles()->orderBy('effective_from', 'desc')->first();
            if ($latest) {
                $latest->update(['is_current' => true]);
            }
        }

        return redirect()->back()->with('success', 'Work profile revision deleted successfully');
    }

    public function removeManager(Request $request, $work_profile_id, $type)
    {
        $employee = Employee::findOrFail($request->id);
        $workProfile = $employee->workProfiles()->findOrFail($work_profile_id);

        if ($type === 'reporting') {
            $workProfile->update(['reporting_manager_id' => null]);
        } elseif ($type === 'hr') {
            $workProfile->update(['hr_manager_id' => null]);
        } elseif ($type === 'indirect') {
            $workProfile->update(['indirect_manager_id' => null]);
        }

        return redirect()->back()->with('success', ucfirst($type) . ' Manager removed successfully');
    }
}
