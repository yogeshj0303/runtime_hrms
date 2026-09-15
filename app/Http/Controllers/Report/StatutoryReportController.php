<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatutoryReportController extends Controller
{
    //
    public function esiCoverage(\Illuminate\Http\Request $request)
    {
        $locations = \App\Models\Location::all();
        $costCenters = \App\Models\CostCenter::all();
        $departments = \App\Models\Department::all();

        $month = $request->input('month', \Carbon\Carbon::now()->format('Y-m'));
        $endOfMonth = \Carbon\Carbon::parse($month . '-01')->endOfMonth()->format('Y-m-d');

        $query = \App\Models\Employee::with([
            'workProfiles',
            'business',
            'identity',
            'salaryRevisions' => function ($q) {
                $q->latest('effective_from');
            }
        ])
        ->where('status', 'active')
        ->where(function($q) use ($endOfMonth) {
            $q->whereNull('joining_date')
              ->orWhere('joining_date', '<=', $endOfMonth);
        });

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

        $allEmployees = $query->get();

        // Process data
        $employees = [];
        $totalEmployees = $allEmployees->count();
        $esiDeducted = 0;

        foreach ($allEmployees as $emp) {
            $latestSalary = $emp->salaryRevisions->first();
            
            if ($latestSalary && $latestSalary->gross_salary > 0) {
                $gross = $latestSalary->gross_salary;
            } else {
                $gross = $emp->salary ? $emp->salary : 0;
            }

            $esiNumber = optional($emp->identity)->esi_number;
            
            // Assume covered if gross <= 21000 or has ESI number
            $isCovered = ($gross > 0 && $gross <= 21000) || !empty($esiNumber);
            
            $isFutureMonth = $month > \Carbon\Carbon::now()->format('Y-m');
            $statusStr = 'Not Covered';
            if ($isFutureMonth) {
                $statusStr = 'Pending';
            } elseif ($isCovered) {
                $statusStr = 'Covered';
                $esiDeducted++;
            }

            $employees[] = [
                'employee' => $emp,
                'gross_salary' => $gross,
                'esi_number' => $esiNumber,
                'is_covered' => $isCovered,
                'status_str' => $statusStr,
            ];
        }

        // Pagination for view (Collection pagination)
        $perPage = 20;
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $items = collect($employees);
        $paginatedEmployees = new \Illuminate\Pagination\LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        if ($request->has('export') && $request->export === 'excel') {
            $filename = "esi_coverage_" . $month . "_" . date('Y_m_d_H_i_s') . ".xls";
            return response(view('admin.report.esi_coverage.excel', compact('employees', 'month'))->render())
                ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Pragma', 'no-cache')
                ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                ->header('Expires', '0');
        }

        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.report.esi_coverage.pdf', compact('employees', 'month'));
            $pdf->setPaper('a4', 'landscape');
            return $pdf->download("esi_coverage_" . $month . "_" . date('Y_m_d_H_i_s') . ".pdf");
        }

        return view('admin.report.esi_coverage.index', compact(
            'locations',
            'costCenters',
            'departments',
            'paginatedEmployees',
            'totalEmployees',
            'esiDeducted',
            'month'
        ));
    }

    public function esiDeduction(Request $request)
    {
        $locations = \App\Models\Location::all();
        $costCenters = \App\Models\CostCenter::all();
        $departments = \App\Models\Department::all();

        $month = $request->input('month', \Carbon\Carbon::now()->format('Y-m'));
        $endOfMonth = \Carbon\Carbon::parse($month . '-01')->endOfMonth()->format('Y-m-d');
        $daysInMonth = \Carbon\Carbon::parse($month . '-01')->daysInMonth;

        $query = \App\Models\Employee::with([
            'workProfiles',
            'business',
            'identity',
            'salaryRevisions' => function ($q) {
                $q->latest('effective_from');
            }
        ])
        ->where('status', 'active')
        ->where(function($q) use ($endOfMonth) {
            $q->whereNull('joining_date')
              ->orWhere('joining_date', '<=', $endOfMonth);
        });

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

        $allEmployees = $query->get();

        $employees = [];

        foreach ($allEmployees as $emp) {
            $latestSalary = $emp->salaryRevisions->first();
            
            if ($latestSalary && $latestSalary->gross_salary > 0) {
                $gross = $latestSalary->gross_salary;
            } else {
                $gross = $emp->salary ? $emp->salary : 0;
            }

            $esiNumber = optional($emp->identity)->esi_number;
            
            // Assume covered if gross <= 21000 or has ESI number
            $isCovered = ($gross > 0 && $gross <= 21000) || !empty($esiNumber);
            
            if ($isCovered) {
                $days = $gross > 0 ? $daysInMonth : 0;
                $reason = $days == 0 ? 'No Wages' : '-';
                
                $employeeEsi = round($gross * 0.0075, 2);
                $employerEsi = round($gross * 0.0325, 2);

                $employees[] = [
                    'employee' => $emp,
                    'gross_salary' => $gross,
                    'esi_number' => $esiNumber,
                    'days' => $days,
                    'employee_esi' => $employeeEsi,
                    'employer_esi' => $employerEsi,
                    'reason_for_0_days' => $reason,
                ];
            }
        }

        if ($request->has('export') && $request->export === 'excel') {
            $filename = "esi_deduction_summary_" . date('Y_m_d_H_i_s') . ".xls";
            return response(view('admin.report.esi_deduction.excel', compact('employees', 'month'))->render())
                ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Pragma', 'no-cache')
                ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                ->header('Expires', '0');
        }

        if ($request->has('export') && $request->export === 'esi_return') {
            $filename = "esi_return_" . date('Y_m_d_H_i_s') . ".xls";
            return response(view('admin.report.esi_deduction.esi_return', compact('employees', 'month'))->render())
                ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Pragma', 'no-cache')
                ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                ->header('Expires', '0');
        }

        return view('admin.report.esi_deduction.index', compact(
            'locations',
            'costCenters',
            'departments',
            'employees',
            'month'
        ));
    }
}
