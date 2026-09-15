<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeWorkProfile;
use App\Models\BusinessUnit;
use App\Models\Location;
use App\Models\CostCenter;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class BulkWorkProfileUploadController extends Controller
{
    public function index()
    {
        return view('admin.employee.bulk-upload-work-profile');
    }

    public function downloadTemplate()
    {
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=employee_work_profile_template.csv',
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];

        $columns = [
            'Employee Code', 
            'Business Unit', 
            'Location', 
            'Cost Center', 
            'Department', 
            'Designation', 
            'Grade', 
            'Reporting Manager Code', 
            'HR Manager Code', 
            'Indirect Manager Code',
            'Effective Date'
        ];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Example row
            fputcsv($file, [
                'EMP001', 
                'Headquarters', 
                'New York Office', 
                'Marketing CC', 
                'Marketing', 
                'Marketing Manager', 
                'A1', 
                'EMP002', 
                'EMP003', 
                'EMP004',
                '2026-08-01'
            ]);
            
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $csvData = array_map('str_getcsv', file($file->getRealPath()));
        
        $header = array_shift($csvData);
        // Normalize headers
        $header = array_map(function($h) { return trim(strtolower($h)); }, $header);

        $businessId = Auth::user()->active_business_id;

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($csvData as $index => $row) {
                // Ensure row matches header length
                if (count($header) !== count($row)) {
                    continue; // Skip invalid rows
                }
                
                $row = array_combine($header, $row);
                
                $employeeCode = trim($row['employee code'] ?? '');
                
                if (empty($employeeCode)) {
                    $errorCount++;
                    $errors[] = "Row " . ($index + 2) . ": Missing required field (Employee Code).";
                    continue;
                }

                // Lookup Employee
                $employee = Employee::where('employee_code', $employeeCode)
                    ->where('business_id', $businessId)
                    ->first();

                if (!$employee) {
                    $errorCount++;
                    $errors[] = "Row " . ($index + 2) . ": Employee code '$employeeCode' not found.";
                    continue;
                }

                $updateData = [];

                // Resolve Relations by Name
                if (!empty(trim($row['business unit'] ?? ''))) {
                    $bu = BusinessUnit::where('unit_name', trim($row['business unit']))->first();
                    if ($bu) $updateData['business_unit_id'] = $bu->id;
                    else $errors[] = "Row " . ($index + 2) . ": Business Unit '" . trim($row['business unit']) . "' not found. Skipped field.";
                }

                if (!empty(trim($row['location'] ?? ''))) {
                    $loc = Location::where('name', trim($row['location']))->first();
                    if ($loc) $updateData['location_id'] = $loc->id;
                    else $errors[] = "Row " . ($index + 2) . ": Location '" . trim($row['location']) . "' not found. Skipped field.";
                }

                if (!empty(trim($row['cost center'] ?? ''))) {
                    $cc = CostCenter::where('name', trim($row['cost center']))->first();
                    if ($cc) $updateData['cost_center_id'] = $cc->id;
                    else $errors[] = "Row " . ($index + 2) . ": Cost Center '" . trim($row['cost center']) . "' not found. Skipped field.";
                }

                if (!empty(trim($row['department'] ?? ''))) {
                    $dept = Department::where('name', trim($row['department']))->first();
                    if ($dept) $updateData['department_id'] = $dept->id;
                    else $errors[] = "Row " . ($index + 2) . ": Department '" . trim($row['department']) . "' not found. Skipped field.";
                }

                if (!empty(trim($row['designation'] ?? ''))) {
                    $desig = Designation::where('name', trim($row['designation']))->first();
                    if ($desig) $updateData['designation_id'] = $desig->id;
                    else $errors[] = "Row " . ($index + 2) . ": Designation '" . trim($row['designation']) . "' not found. Skipped field.";
                }

                if (!empty(trim($row['grade'] ?? ''))) {
                    $grade = Grade::where('name', trim($row['grade']))->first();
                    if ($grade) $updateData['grade_id'] = $grade->id;
                    else $errors[] = "Row " . ($index + 2) . ": Grade '" . trim($row['grade']) . "' not found. Skipped field.";
                }

                // Resolve Managers by Employee Code
                if (!empty(trim($row['reporting manager code'] ?? ''))) {
                    $rm = Employee::where('employee_code', trim($row['reporting manager code']))
                        ->where('business_id', $businessId)->first();
                    if ($rm) $updateData['reporting_manager_id'] = $rm->id;
                    else $errors[] = "Row " . ($index + 2) . ": Reporting Manager '" . trim($row['reporting manager code']) . "' not found. Skipped field.";
                }

                if (!empty(trim($row['hr manager code'] ?? ''))) {
                    $hrm = Employee::where('employee_code', trim($row['hr manager code']))
                        ->where('business_id', $businessId)->first();
                    if ($hrm) $updateData['hr_manager_id'] = $hrm->id;
                    else $errors[] = "Row " . ($index + 2) . ": HR Manager '" . trim($row['hr manager code']) . "' not found. Skipped field.";
                }

                if (!empty(trim($row['indirect manager code'] ?? ''))) {
                    $im = Employee::where('employee_code', trim($row['indirect manager code']))
                        ->where('business_id', $businessId)->first();
                    if ($im) $updateData['indirect_manager_id'] = $im->id;
                    else $errors[] = "Row " . ($index + 2) . ": Indirect Manager '" . trim($row['indirect manager code']) . "' not found. Skipped field.";
                }

                if (!empty(trim($row['effective date'] ?? ''))) {
                    $updateData['effective_from'] = date('Y-m-d', strtotime(trim($row['effective date'])));
                }

                // Only create/update if we have data to update
                if (!empty($updateData)) {
                    EmployeeWorkProfile::updateOrCreate(
                        ['employee_id' => $employee->id],
                        $updateData
                    );
                }
                
                $successCount++;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk work profile upload failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred during upload. No data was saved. Error: ' . $e->getMessage());
        }

        if ($errorCount > 0 && $successCount == 0) {
            return redirect()->back()->with('error', "Upload failed. Found $errorCount errors.")->with('import_errors', $errors);
        } elseif ($errorCount > 0) {
            return redirect()->back()->with('warning', "Successfully updated work profiles for $successCount employees. Found $errorCount errors/warnings.")->with('import_errors', $errors);
        }

        return redirect()->back()->with('success', "Successfully updated work profiles for $successCount employees.");
    }
}
