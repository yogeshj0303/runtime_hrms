<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Employee;
use App\Models\BusinessUnit;
use App\Models\Location;
use App\Models\CostCenter;
use App\Models\Department;

class EmployeeReportController extends Controller
{
    public function employeeRegister(Request $request)
    {
        $businessUnits = BusinessUnit::all();
        $locations = Location::all();
        $costCenters = CostCenter::all();
        $departments = Department::all();

        $allEmployeesList = Employee::select('id', 'first_name', 'last_name', 'employee_code')->orderBy('first_name')->get();

        $query = Employee::with([
            'business',
            'workProfiles.location', 
            'workProfiles.department',
            'workProfiles.designation',
            'workProfiles.reportingManager',
            'workProfiles.grade',
            'profile',
            'addresses',
            'identity',
            'policyAssignment.shift'
        ]);

        // Specific employee filtering
        if ($request->filled('employee_id')) {
            $query->where('id', $request->employee_id);
        }

        // Work profile filtering
        $workProfileFilters = ['business_unit_id', 'location_id', 'cost_center_id', 'department_id'];
        
        foreach ($workProfileFilters as $filter) {
            $requestField = str_replace('_id', '', $filter);
            if ($request->filled($requestField) && $request->$requestField !== 'All') {
                $query->whereHas('workProfiles', function($q) use ($filter, $request, $requestField) {
                    $q->where($filter, $request->$requestField)
                      ->where('is_current', true);
                });
            }
        }

        // Status filtering
        if ($request->filled('status') && $request->status !== 'All') {
            $query->where('status', $request->status === 'Active' ? 'active' : 'inactive');
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'employee_code');
        if (in_array($sortBy, ['employee_code', 'first_name', 'joining_date', 'department'])) {
            $query->orderBy($sortBy);
        }

        // Apply dynamic field selection if columns are explicitly requested, else default.
        // For simplicity, we just pass all to the view, and let the view decide what to show
        // based on checkboxes.
        
        // Add records to include logic
        $recordType = $request->get('record_type', 'active');
        if ($recordType === 'active') {
            $query->where('status', '!=', 'inactive');
        } elseif ($recordType === 'inactive') {
            $query->where('status', 'inactive');
        }

        // Export Logic
        if ($request->has('export') && $request->export === 'excel') {
            $employees = $query->get();
            $filename = "employee_register_" . date('Y_m_d_H_i_s') . ".xls";
            
            $columnsMap = [
                'empCode' => 'Employee Code',
                'empName' => 'Employee Name',
                'gender' => 'Gender',
                'dob' => 'Date of Birth',
                'doj' => 'Date of Joining',
                'exitDate' => 'Date of Exit',
                'empStatus' => 'Employee Status',
                'empType' => 'Employee Type',
                'mobile' => 'Mobile Phone',
                'email' => 'Office E-Mail',
                'personalEmail' => 'Personal E-Mail',
                'currentAddress' => 'Current Address',
                'permanentAddress' => 'Permanent Address',
                'city' => 'City',
                'state' => 'State',
                'pincode' => 'PIN Code',
                'businessUnit' => 'Business Unit',
                'location' => 'Location',
                'costCenter' => 'Cost Center',
                'department' => 'Department',
                'designation' => 'Designation',
                'grade' => 'Grade',
                'manager' => 'Reporting Manager',
                'shiftPolicy' => 'Shift Policy',
                'pan' => 'PAN Number',
                'aadhar' => 'Aadhar Number',
                'pf' => 'PF UAN Number',
                'esi' => 'ESI IP Number',
                'bankName' => 'Bank Name',
                'ifsc' => 'Bank IFSC',
                'account' => 'Bank Account',
            ];

            // Determine which columns to export based on request or defaults
            $selectedColumns = $request->input('columns', ['empCode', 'empName', 'gender', 'doj', 'empStatus', 'mobile', 'email', 'businessUnit', 'location', 'costCenter', 'department', 'designation']);

            return response(view('admin.report.employee_register.excel', compact('employees', 'selectedColumns', 'columnsMap'))->render())
                ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Pragma', 'no-cache')
                ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                ->header('Expires', '0');
        }

        if ($request->has('export') && $request->export === 'pdf') {
            $employees = $query->get();
            $columnsMap = [
                'empCode' => 'Employee Code',
                'empName' => 'Employee Name',
                'gender' => 'Gender',
                'dob' => 'Date of Birth',
                'doj' => 'Date of Joining',
                'exitDate' => 'Date of Exit',
                'empStatus' => 'Employee Status',
                'empType' => 'Employee Type',
                'mobile' => 'Mobile Phone',
                'email' => 'Office E-Mail',
                'personalEmail' => 'Personal E-Mail',
                'currentAddress' => 'Current Address',
                'permanentAddress' => 'Permanent Address',
                'city' => 'City',
                'state' => 'State',
                'pincode' => 'PIN Code',
                'businessUnit' => 'Business Unit',
                'location' => 'Location',
                'costCenter' => 'Cost Center',
                'department' => 'Department',
                'designation' => 'Designation',
                'grade' => 'Grade',
                'manager' => 'Reporting Manager',
                'shiftPolicy' => 'Shift Policy',
                'pan' => 'PAN Number',
                'aadhar' => 'Aadhar Number',
                'pf' => 'PF UAN Number',
                'esi' => 'ESI IP Number',
                'bankName' => 'Bank Name',
                'ifsc' => 'Bank IFSC',
                'account' => 'Bank Account',
            ];
            $selectedColumns = $request->input('columns', ['empCode', 'empName', 'gender', 'doj', 'empStatus', 'mobile', 'email', 'businessUnit', 'location', 'costCenter', 'department', 'designation']);

            // Load view and pass data
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.report.employee_register.pdf', compact('employees', 'selectedColumns', 'columnsMap'));
            
            // Set paper to A4 Landscape
            $pdf->setPaper('a4', 'landscape');

            $filename = "employee_register_" . date('Y_m_d_H_i_s') . ".pdf";
            return $pdf->download($filename);
        }
        
        $perPage = $request->get('per_page', 10);
        $employees = $query->paginate($perPage)->appends($request->all());

        return view('admin.report.employee_register.index', compact(
            'businessUnits',
            'locations',
            'costCenters',
            'departments',
            'employees',
            'allEmployeesList'
        ));
    }

    public function employeeAddresses(Request $request)
    {
        $locations = \App\Models\Location::all();
        $costCenters = \App\Models\CostCenter::all();
        $departments = \App\Models\Department::all();
        $allEmployeesList = Employee::select('id', 'first_name', 'last_name', 'employee_code')->orderBy('first_name')->get();

        $query = \App\Models\EmployeeAddress::with([
            'employee.workProfiles',
            'employee.business'
        ])->whereHas('employee', function($q) {
            $q->where('status', 'active');
        });

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $workProfileFilters = ['location_id', 'cost_center_id', 'department_id'];
        foreach ($workProfileFilters as $filter) {
            if ($request->filled($filter) && $request->$filter !== 'All') {
                $query->whereHas('employee.workProfiles', function($q) use ($filter, $request) {
                    $q->where($filter, $request->$filter)
                      ->where('is_current', true);
                });
            }
        }

        if ($request->has('export') && $request->export === 'excel') {
            $addresses = $query->get();
            $filename = "employee_addresses_" . date('Y_m_d_H_i_s') . ".xls";
            return response(view('admin.report.employee_addresses.excel', compact('addresses'))->render())
                ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Pragma', 'no-cache')
                ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                ->header('Expires', '0');
        }

        if ($request->has('export') && $request->export === 'pdf') {
            $addresses = $query->get();
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.report.employee_addresses.pdf', compact('addresses'));
            $pdf->setPaper('a4', 'landscape');
            return $pdf->download("employee_addresses_" . date('Y_m_d_H_i_s') . ".pdf");
        }

        $addresses = $query->paginate(10)->appends($request->all());

        return view('admin.report.employee_addresses.index', compact(
            'locations',
            'costCenters',
            'departments',
            'addresses',
            'allEmployeesList'
        ));
    }

    public function employeeAssets(Request $request)
    {
        $locations = \App\Models\Location::all();
        $costCenters = \App\Models\CostCenter::all();
        $departments = \App\Models\Department::all();
        $allEmployeesList = \App\Models\Employee::select('id', 'first_name', 'last_name', 'employee_code')->orderBy('first_name')->get();

        $query = \App\Models\EmployeeAsset::with([
            'employee.workProfiles',
            'employee.business'
        ])->whereHas('employee', function($q) {
            $q->where('status', 'active');
        });

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->has('warranty_expired') && $request->warranty_expired == '1') {
            $query->where('expiry_date', '<', \Carbon\Carbon::now()->format('Y-m-d'));
        }

        $workProfileFilters = ['location_id', 'cost_center_id', 'department_id'];
        foreach ($workProfileFilters as $filter) {
            if ($request->filled($filter) && $request->$filter !== 'All') {
                $query->whereHas('employee.workProfiles', function($q) use ($filter, $request) {
                    $q->where($filter, $request->$filter)
                      ->where('is_current', true);
                });
            }
        }

        if ($request->has('export') && $request->export === 'excel') {
            $assets = $query->get();
            $filename = "employee_assets_" . date('Y_m_d_H_i_s') . ".xls";
            return response(view('admin.report.employee_assets.excel', compact('assets'))->render())
                ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Pragma', 'no-cache')
                ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                ->header('Expires', '0');
        }

        if ($request->has('export') && $request->export === 'pdf') {
            $assets = $query->get();
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.report.employee_assets.pdf', compact('assets'));
            $pdf->setPaper('a4', 'landscape');
            return $pdf->download("employee_assets_" . date('Y_m_d_H_i_s') . ".pdf");
        }

        $assets = $query->paginate(10)->appends($request->all());

        return view('admin.report.employee_assets.index', compact(
            'locations',
            'costCenters',
            'departments',
            'assets',
            'allEmployeesList'
        ));
    }

    public function employeeEvents(Request $request)
    {
        $locations = \App\Models\Location::all();
        $costCenters = \App\Models\CostCenter::all();
        $departments = \App\Models\Department::all();

        $fromMonth = $request->input('from_month');
        $toMonth = $request->input('to_month');
        
        $months = [
            'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4,
            'May' => 5, 'June' => 6, 'July' => 7, 'August' => 8,
            'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12
        ];

        $events = collect();

        if ($request->filled('from_month') && $request->filled('to_month')) {
            $fromMonthNum = $months[$fromMonth] ?? 1;
            $toMonthNum = $months[$toMonth] ?? 12;
            
            // Swap if from > to
            if ($fromMonthNum > $toMonthNum) {
                $temp = $fromMonthNum;
                $fromMonthNum = $toMonthNum;
                $toMonthNum = $temp;
            }

            $query = \App\Models\Employee::with(['profile', 'workProfiles.location', 'workProfiles.department', 'workProfiles.designation'])
                ->where('status', 'active');

            $workProfileFilters = ['location_id', 'cost_center_id', 'department_id'];
            foreach ($workProfileFilters as $filter) {
                if ($request->filled($filter) && $request->$filter !== 'All') {
                    $query->whereHas('workProfiles', function($q) use ($filter, $request) {
                        $q->where($filter, $request->$filter)
                          ->where('is_current', true);
                    });
                }
            }

            $employees = $query->get();

            foreach ($employees as $emp) {
                $workProfile = $emp->workProfiles->where('is_current', true)->first();
                $location = $workProfile && $workProfile->location ? $workProfile->location->name : '-';
                $department = $workProfile && $workProfile->department ? $workProfile->department->name : '-';
                $designation = $workProfile && $workProfile->designation ? $workProfile->designation->name : '-';
                
                // Birthdays
                if ($request->has('show_birthdays') && $emp->profile && $emp->profile->dob) {
                    $m = (int)date('n', strtotime($emp->profile->dob));
                    if ($m >= $fromMonthNum && $m <= $toMonthNum) {
                        $events->push([
                            'date' => date('d M', strtotime($emp->profile->dob)),
                            'sort_month' => $m,
                            'sort_day' => (int)date('j', strtotime($emp->profile->dob)),
                            'event_type' => 'Birthday',
                            'employee' => $emp->employee_code . ' - ' . $emp->first_name . ' ' . $emp->last_name,
                            'location' => $location,
                            'department' => $department,
                            'designation' => $designation
                        ]);
                    }
                }

                // Work Anniversaries
                if ($request->has('show_work_anniversaries') && $emp->joining_date) {
                    $m = (int)date('n', strtotime($emp->joining_date));
                    if ($m >= $fromMonthNum && $m <= $toMonthNum) {
                        $events->push([
                            'date' => date('d M', strtotime($emp->joining_date)),
                            'sort_month' => $m,
                            'sort_day' => (int)date('j', strtotime($emp->joining_date)),
                            'event_type' => 'Work Anniversary',
                            'employee' => $emp->employee_code . ' - ' . $emp->first_name . ' ' . $emp->last_name,
                            'location' => $location,
                            'department' => $department,
                            'designation' => $designation
                        ]);
                    }
                }

                // Wedding Anniversaries
                if ($request->has('show_wedding_anniversaries') && $emp->profile && $emp->profile->anniversary_date) {
                    $m = (int)date('n', strtotime($emp->profile->anniversary_date));
                    if ($m >= $fromMonthNum && $m <= $toMonthNum) {
                        $events->push([
                            'date' => date('d M', strtotime($emp->profile->anniversary_date)),
                            'sort_month' => $m,
                            'sort_day' => (int)date('j', strtotime($emp->profile->anniversary_date)),
                            'event_type' => 'Wedding Anniversary',
                            'employee' => $emp->employee_code . ' - ' . $emp->first_name . ' ' . $emp->last_name,
                            'location' => $location,
                            'department' => $department,
                            'designation' => $designation
                        ]);
                    }
                }
            }
            
            // Sort by Month, then Day
            $events = $events->sortBy([
                ['sort_month', 'asc'],
                ['sort_day', 'asc']
            ])->values();
        }

        if ($request->has('export') && $request->export === 'excel') {
            $filename = "employee_events_" . date('Y_m_d_H_i_s') . ".xls";
            return response(view('admin.report.employee_events.excel', compact('events'))->render())
                ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Pragma', 'no-cache')
                ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                ->header('Expires', '0');
        }

        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.report.employee_events.pdf', compact('events'));
            $pdf->setPaper('a4', 'landscape');
            return $pdf->download("employee_events_" . date('Y_m_d_H_i_s') . ".pdf");
        }

        return view('admin.report.employee_events.index', compact(
            'locations',
            'costCenters',
            'departments',
            'events'
        ));
    }

    public function employeeExits(Request $request)
    {
        $locations = \App\Models\Location::all();
        $costCenters = \App\Models\CostCenter::all();
        $departments = \App\Models\Department::all();
        $exitReasons = \App\Models\ExitReason::all();

        $query = \App\Models\Employee::with([
            'workProfiles.location', 
            'workProfiles.department', 
            'workProfiles.designation',
            'exitReason'
        ])->whereNotNull('exit_date');

        if ($request->filled('from_date')) {
            $query->whereDate('exit_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('exit_date', '<=', $request->to_date);
        }

        if ($request->filled('exit_reason_id') && $request->exit_reason_id !== 'All') {
            $query->where('exit_reason_id', $request->exit_reason_id);
        }

        $workProfileFilters = ['location_id', 'cost_center_id', 'department_id'];
        foreach ($workProfileFilters as $filter) {
            if ($request->filled($filter) && $request->$filter !== 'All') {
                $query->whereHas('workProfiles', function($q) use ($filter, $request) {
                    $q->where($filter, $request->$filter)
                      ->where('is_current', true);
                });
            }
        }

        $query->orderBy('exit_date', 'desc');

        if ($request->has('export') && $request->export === 'excel') {
            $exits = $query->get();
            $filename = "employee_exits_" . date('Y_m_d_H_i_s') . ".xls";
            return response(view('admin.report.employee_exits.excel', compact('exits'))->render())
                ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Pragma', 'no-cache')
                ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                ->header('Expires', '0');
        }

        if ($request->has('export') && $request->export === 'pdf') {
            $exits = $query->get();
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.report.employee_exits.pdf', compact('exits'));
            $pdf->setPaper('a4', 'landscape');
            return $pdf->download("employee_exits_" . date('Y_m_d_H_i_s') . ".pdf");
        }

        // Add pagination if not exporting
        $exits = $query->paginate(20)->withQueryString();

        return view('admin.report.employee_exits.index', compact(
            'locations',
            'costCenters',
            'departments',
            'exitReasons',
            'exits'
        ));
    }

    public function employeeJoinings(Request $request)
    {
        $locations = \App\Models\Location::all();
        $costCenters = \App\Models\CostCenter::all();
        $departments = \App\Models\Department::all();
        $grades = \App\Models\Grade::all();

        $query = \App\Models\Employee::with([
            'workProfiles.location', 
            'workProfiles.department', 
            'workProfiles.designation',
            'profile'
        ])->whereNotNull('joining_date');

        if ($request->filled('from_date')) {
            $query->whereDate('joining_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('joining_date', '<=', $request->to_date);
        }

        $workProfileFilters = ['location_id', 'cost_center_id', 'department_id', 'grade_id'];
        foreach ($workProfileFilters as $filter) {
            if ($request->filled($filter) && $request->$filter !== 'All') {
                $query->whereHas('workProfiles', function($q) use ($filter, $request) {
                    $q->where($filter, $request->$filter)
                      ->where('is_current', true);
                });
            }
        }

        $query->orderBy('joining_date', 'desc');

        if ($request->has('export') && $request->export === 'excel') {
            $joinings = $query->get();
            $filename = "employee_joinings_" . date('Y_m_d_H_i_s') . ".xls";
            return response(view('admin.report.employee_joinings.excel', compact('joinings'))->render())
                ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Pragma', 'no-cache')
                ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                ->header('Expires', '0');
        }

        if ($request->has('export') && $request->export === 'pdf') {
            $joinings = $query->get();
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.report.employee_joinings.pdf', compact('joinings'));
            $pdf->setPaper('a4', 'landscape');
            return $pdf->download("employee_joinings_" . date('Y_m_d_H_i_s') . ".pdf");
        }

        $joinings = $query->paginate(20)->withQueryString();

        return view('admin.report.employee_joinings.index', compact(
            'locations',
            'costCenters',
            'departments',
            'grades',
            'joinings'
        ));
    }

    public function employeeLoans(Request $request)
    {
        $locations = \App\Models\Location::all();
        $costCenters = \App\Models\CostCenter::all();
        $departments = \App\Models\Department::all();

        $query = \App\Models\EmployeeLoan::with([
            'employee',
            'employee.workProfiles.location',
            'employee.workProfiles.department',
            'employee.workProfiles.designation',
            'installments'
        ]);

        if ($request->filled('from_date')) {
            $query->whereDate('issue_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('issue_date', '<=', $request->to_date);
        }

        if ($request->filled('employee_search')) {
            $search = $request->employee_search;
            $query->whereHas('employee', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        $workProfileFilters = ['location_id', 'cost_center_id', 'department_id'];
        foreach ($workProfileFilters as $filter) {
            if ($request->filled($filter) && $request->$filter !== 'All') {
                $query->whereHas('employee.workProfiles', function($q) use ($filter, $request) {
                    $q->where($filter, $request->$filter)
                      ->where('is_current', true);
                });
            }
        }

        $query->orderBy('issue_date', 'desc');

        $reportType = $request->input('report_type', 'summary'); // summary or detailed

        if ($request->has('export') && $request->export === 'excel') {
            $loans = $query->get();
            $filename = "employee_loans_" . date('Y_m_d_H_i_s') . ".xls";
            return response(view('admin.report.employee_loans.excel', compact('loans', 'reportType'))->render())
                ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Pragma', 'no-cache')
                ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                ->header('Expires', '0');
        }

        if ($request->has('export') && $request->export === 'pdf') {
            $loans = $query->get();
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.report.employee_loans.pdf', compact('loans', 'reportType'));
            $pdf->setPaper('a4', 'landscape');
            return $pdf->download("employee_loans_" . date('Y_m_d_H_i_s') . ".pdf");
        }

        $loans = $query->paginate(20)->withQueryString();
        $employees = \App\Models\Employee::where('status', 'active')->orderBy('first_name')->get();

        return view('admin.report.employee_loans.index', compact(
            'locations',
            'costCenters',
            'departments',
            'loans',
            'reportType',
            'employees'
        ));
    }
    public function employeeRelatives(Request $request)
    {
        $locations = \App\Models\Location::all();
        $costCenters = \App\Models\CostCenter::all();
        $departments = \App\Models\Department::all();

        $query = \App\Models\EmployeeFamilyMember::with([
            'employee.workProfiles',
            'employee.business'
        ]);

        // Filter by Active Employees Only
        if ($request->has('active_only') && $request->active_only == '1') {
            $query->whereHas('employee', function($q) {
                $q->where('status', 'active');
            });
        }

        // Work profile filters
        $workProfileFilters = ['location_id', 'cost_center_id', 'department_id'];
        foreach ($workProfileFilters as $filter) {
            if ($request->filled($filter) && $request->$filter !== 'All') {
                $query->whereHas('employee.workProfiles', function($q) use ($filter, $request) {
                    $q->where($filter, $request->$filter)
                      ->where('is_current', true);
                });
            }
        }

        if ($request->has('export') && $request->export === 'excel') {
            $relatives = $query->get();
            $filename = "employee_relatives_" . date('Y_m_d_H_i_s') . ".xls";
            return response(view('admin.report.employee_relatives.excel', compact('relatives'))->render())
                ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Pragma', 'no-cache')
                ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                ->header('Expires', '0');
        }

        if ($request->has('export') && $request->export === 'pdf') {
            $relatives = $query->get();
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.report.employee_relatives.pdf', compact('relatives'));
            $pdf->setPaper('a4', 'landscape');
            return $pdf->download("employee_relatives_" . date('Y_m_d_H_i_s') . ".pdf");
        }

        $relatives = $query->paginate(20)->withQueryString();

        return view('admin.report.employee_relatives.index', compact(
            'locations',
            'costCenters',
            'departments',
            'relatives'
        ));
    }

    public function inactiveEmployees(Request $request)
    {
        $locations = \App\Models\Location::all();
        $costCenters = \App\Models\CostCenter::all();
        $departments = \App\Models\Department::all();

        $query = \App\Models\Employee::with([
            'workProfiles.location', 
            'workProfiles.department', 
            'workProfiles.designation',
            'workProfiles.costCenter'
        ])->where('status', '!=', 'active');

        $workProfileFilters = ['location_id', 'cost_center_id', 'department_id'];
        foreach ($workProfileFilters as $filter) {
            if ($request->filled($filter) && $request->$filter !== 'All') {
                $query->whereHas('workProfiles', function($q) use ($filter, $request) {
                    $q->where($filter, $request->$filter)
                      ->where('is_current', true);
                });
            }
        }

        $query->orderBy('exit_date', 'desc')->orderBy('first_name', 'asc');

        if ($request->has('export') && $request->export === 'excel') {
            $employees = $query->get();
            $filename = "inactive_employees_" . date('Y_m_d_H_i_s') . ".xls";
            return response(view('admin.report.inactive_employees.excel', compact('employees'))->render())
                ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Pragma', 'no-cache')
                ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                ->header('Expires', '0');
        }

        if ($request->has('export') && $request->export === 'pdf') {
            $employees = $query->get();
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.report.inactive_employees.pdf', compact('employees'));
            $pdf->setPaper('a4', 'landscape');
            return $pdf->download("inactive_employees_" . date('Y_m_d_H_i_s') . ".pdf");
        }

        // Add pagination if not exporting
        $employees = $query->paginate(20)->withQueryString();

        return view('admin.report.inactive_employees.index', compact(
            'locations',
            'costCenters',
            'departments',
            'employees'
        ));
    }

    public function exportRecords(Request $request)
    {
        $locations = \App\Models\Location::all();
        $costCenters = \App\Models\CostCenter::all();
        $departments = \App\Models\Department::all();

        if ($request->has('export')) {
            $query = \App\Models\Employee::with([
                'workProfiles.location', 
                'workProfiles.department', 
                'workProfiles.designation',
                'workProfiles.costCenter'
            ]);

            // Filter by Record Type
            $recordType = $request->input('record_type', 'active');
            if ($recordType === 'active') {
                $query->where('status', 'active');
            } elseif ($recordType === 'inactive') {
                $query->where('status', '!=', 'active');
            }

            // Work profile filters
            $workProfileFilters = ['location_id', 'cost_center_id', 'department_id'];
            foreach ($workProfileFilters as $filter) {
                if ($request->filled($filter) && $request->$filter !== 'All') {
                    $query->whereHas('workProfiles', function($q) use ($filter, $request) {
                        $q->where($filter, $request->$filter)
                          ->where('is_current', true);
                    });
                }
            }

            $query->orderBy('first_name', 'asc');
            $employees = $query->get();

            $filename = "employee_export_" . date('Y_m_d_H_i_s') . ".xls";
            return response(view('admin.report.export_records.excel', compact('employees'))->render())
                ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Pragma', 'no-cache')
                ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                ->header('Expires', '0');
        }

        return view('admin.report.export_records.index', compact(
            'locations',
            'costCenters',
            'departments'
        ));
    }

    public function incrementAgeing(Request $request)
    {
        $locations = \App\Models\Location::all();
        $costCenters = \App\Models\CostCenter::all();
        $departments = \App\Models\Department::all();
        $grades = \App\Models\Grade::all();

        $query = \App\Models\Employee::with([
            'workProfiles.location', 
            'workProfiles.department', 
            'workProfiles.designation',
            'workProfiles.costCenter',
            'workProfiles.grade',
            'salaryRevisions' => function($q) {
                $q->orderBy('effective_from', 'desc');
            }
        ])->where('status', 'active');

        // Apply filters
        $workProfileFilters = ['location_id', 'cost_center_id', 'department_id', 'grade_id'];
        foreach ($workProfileFilters as $filter) {
            if ($request->filled($filter) && $request->$filter !== 'All') {
                $query->whereHas('workProfiles', function($q) use ($filter, $request) {
                    $q->where($filter, $request->$filter)
                      ->where('is_current', true);
                });
            }
        }

        $allEmployees = $query->get();

        // Calculate ageing and filter
        $ageingFilter = $request->input('ageing');
        $filteredEmployees = collect();

        foreach ($allEmployees as $emp) {
            $lastIncrement = null;
            if ($emp->salaryRevisions->isNotEmpty()) {
                $lastIncrement = $emp->salaryRevisions->first()->effective_from;
            } else {
                $lastIncrement = $emp->joining_date;
            }

            if ($lastIncrement) {
                $lastDate = \Carbon\Carbon::parse($lastIncrement);
                $now = \Carbon\Carbon::now();
                $diffInYears = $lastDate->diffInYears($now);
                $diffInMonths = $lastDate->diffInMonths($now) % 12;
                
                $ageingStr = $diffInYears . ' Year' . ($diffInYears != 1 ? 's' : '');
                if ($diffInMonths > 0) {
                    $ageingStr .= ', ' . $diffInMonths . ' Month' . ($diffInMonths != 1 ? 's' : '');
                }

                $emp->last_increment_date = $lastDate->format('Y-m-d');
                $emp->ageing_str = $ageingStr;
                $emp->ageing_years = $diffInYears;

                $include = true;
                if ($ageingFilter === '1' && $diffInYears < 1) $include = false;
                if ($ageingFilter === '2' && $diffInYears < 2) $include = false;
                if ($ageingFilter === '3' && $diffInYears < 3) $include = false;

                if ($include) {
                    $filteredEmployees->push($emp);
                }
            }
        }

        // Sort by last increment date (oldest first)
        $filteredEmployees = $filteredEmployees->sortBy('last_increment_date')->values();

        if ($request->has('export') && $request->export === 'excel') {
            $filename = "increment_ageing_" . date('Y_m_d_H_i_s') . ".xls";
            return response(view('admin.report.increment_ageing.excel', ['employees' => $filteredEmployees])->render())
                ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Pragma', 'no-cache')
                ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                ->header('Expires', '0');
        }

        // Basic manual pagination on collection
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 20;
        $paginatedEmployees = new \Illuminate\Pagination\LengthAwarePaginator(
            $filteredEmployees->forPage($page, $perPage),
            $filteredEmployees->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        return view('admin.report.increment_ageing.index', compact(
            'locations',
            'costCenters',
            'departments',
            'grades',
            'paginatedEmployees'
        ));
    }

    public function costToCompany(Request $request)
    {
        $locations = \App\Models\Location::all();
        $costCenters = \App\Models\CostCenter::all();
        $departments = \App\Models\Department::all();

        if ($request->has('export')) {
            $query = \App\Models\Employee::with([
                'workProfiles.location', 
                'workProfiles.department', 
                'workProfiles.designation',
                'workProfiles.costCenter'
            ]);

            // Employee Search
            if ($request->filled('employee_search')) {
                $search = $request->employee_search;
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('employee_code', 'like', "%{$search}%");
                });
            }

            // Active Records Only
            if ($request->has('active_only') && $request->active_only == '1') {
                $query->where('status', 'active');
            }

            // Work profile filters
            $workProfileFilters = ['location_id', 'cost_center_id', 'department_id'];
            foreach ($workProfileFilters as $filter) {
                if ($request->filled($filter) && $request->$filter !== 'All') {
                    $query->whereHas('workProfiles', function($q) use ($filter, $request) {
                        $q->where($filter, $request->$filter)
                          ->where('is_current', true);
                    });
                }
            }

            $query->orderBy('first_name', 'asc');
            $employees = $query->get();
            $exportData = collect();

            $revisionMode = $request->input('revision_mode', 'latest');
            $revisionDate = $request->input('revision_date');

            foreach ($employees as $emp) {
                $revisionsQuery = \App\Models\EmployeeSalaryRevision::where('employee_id', $emp->id);

                if ($revisionMode === 'latest') {
                    $revisions = $revisionsQuery->orderBy('effective_from', 'desc')->take(1)->get();
                } elseif ($revisionMode === 'all') {
                    $revisions = $revisionsQuery->orderBy('effective_from', 'desc')->get();
                } elseif ($revisionMode === 'date' && $revisionDate) {
                    $date = \Carbon\Carbon::parse($revisionDate)->format('Y-m-d');
                    $revisions = $revisionsQuery->where('effective_from', '<=', $date)
                                                ->orderBy('effective_from', 'desc')
                                                ->take(1)->get();
                } else {
                    $revisions = collect();
                }

                foreach ($revisions as $rev) {
                    $exportData->push([
                        'employee' => $emp,
                        'revision' => $rev
                    ]);
                }
            }

            if ($request->export === 'excel') {
                $filename = "cost_to_company_" . date('Y_m_d_H_i_s') . ".xls";
                return response(view('admin.report.cost_to_company.excel', compact('exportData'))->render())
                    ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
                    ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                    ->header('Pragma', 'no-cache')
                    ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                    ->header('Expires', '0');
            }

            if ($request->export === 'pdf') {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.report.cost_to_company.pdf', compact('exportData'));
                $pdf->setPaper('a4', 'landscape');
                return $pdf->download("cost_to_company_" . date('Y_m_d_H_i_s') . ".pdf");
            }
        }

        return view('admin.report.cost_to_company.index', compact(
            'locations',
            'costCenters',
            'departments'
        ));
    }
}
