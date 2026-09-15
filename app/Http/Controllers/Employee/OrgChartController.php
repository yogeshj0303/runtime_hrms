<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class OrgChartController extends Controller
{
    public function index(Request $request)
    {
        $businessId = Auth::user()->active_business_id;
        $managerIdFilter = $request->input('manager_id');

        // Fetch all active employees with their work profiles and designations
        $allEmployees = Employee::where('business_id', $businessId)
            ->where('status', 'active')
            ->with(['workProfiles.designation', 'workProfiles.department'])
            ->get();

        // Get list of all employees for the dropdown
        $managers = $allEmployees->map(function($emp) {
            return [
                'id' => $emp->id,
                'name' => trim($emp->first_name . ' ' . $emp->last_name) . ' (' . $emp->employee_code . ')'
            ];
        });

        $employees = collect();
        if ($managerIdFilter) {
            // Find all subordinates recursively
            $employeesToKeep = [$managerIdFilter];
            $added = true;
            
            while ($added) {
                $added = false;
                foreach ($allEmployees as $emp) {
                    $workProfile = $emp->workProfiles->first();
                    $repId = $workProfile ? (string)$workProfile->reporting_manager_id : null;
                    if ($repId && in_array($repId, $employeesToKeep) && !in_array((string)$emp->id, $employeesToKeep)) {
                        $employeesToKeep[] = (string)$emp->id;
                        $added = true;
                    }
                }
            }
            
            $employees = $allEmployees->whereIn('id', $employeesToKeep);
        } else {
            $employees = $allEmployees;
        }

        // Format data for d3-org-chart
        $chartData = [];
        
        foreach ($employees as $employee) {
            $name = trim($employee->first_name . ' ' . $employee->last_name);
            $workProfile = $employee->workProfiles->first();
            $designation = $workProfile && $workProfile->designation ? $workProfile->designation->name : 'No Designation';
            $department = $workProfile && $workProfile->department ? $workProfile->department->name : '';
            
            $reportingManagerId = $workProfile && $workProfile->reporting_manager_id ? (string) $workProfile->reporting_manager_id : '';
            
            // If filtering by manager, the selected manager becomes the root (no parent)
            if ($managerIdFilter && $employee->id == $managerIdFilter) {
                $reportingManagerId = '';
            }

            $avatarUrl = $employee->face_image ? asset('storage/' . $employee->face_image) : asset('build/images/users/user-dummy-img.jpg');

            $chartData[] = [
                'id' => (string) $employee->id,
                'parentId' => $reportingManagerId,
                'name' => $name,
                'positionName' => $designation,
                'department' => $department,
                'imageUrl' => $avatarUrl,
                'empCode' => $employee->employee_code
            ];
        }

        return view('admin.employee.org-chart', compact('chartData', 'managers', 'managerIdFilter'));
    }
}
