<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeSeparation;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SeparationDashboardController extends Controller
{
    public function index(Request $request)
    {
        $businessId = Auth::user()->active_business_id ?? 1;

        // Date Range Filter
        $startDate = $request->input('start_date', now()->subMonths(6)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Base Query
        $separationsQuery = EmployeeSeparation::with('employee')
            ->whereHas('employee', function ($q) use ($businessId) {
                $q->where('business_id', $businessId);
            })
            ->whereBetween('exit_date', [$startDate, $endDate]);

        // Key Metrics
        $totalExits = (clone $separationsQuery)->count();
        
        $retentionAttempted = (clone $separationsQuery)->where('retention_attempted', true)->count();
        $retentionPercentage = $totalExits > 0 ? round(($retentionAttempted / $totalExits) * 100) : 0;

        $separations = (clone $separationsQuery)->get();

        $totalTenureDays = 0;
        $preConfirmationExits = 0;

        // Tenure buckets
        $tenureBuckets = [
            'Below 6m' => 0,
            '6-24m' => 0,
            '2-5y' => 0,
            '5y+' => 0,
        ];

        // Reason Data for Pie Chart
        $reasonsMap = [];
        
        // Department Data for Donut Chart
        $deptMap = [];

        foreach ($separations as $sep) {
            // Reasons
            if (!isset($reasonsMap[$sep->exit_reason])) {
                $reasonsMap[$sep->exit_reason] = 0;
            }
            $reasonsMap[$sep->exit_reason]++;

            // Departments
            $dept = $sep->employee->department ?? 'Unassigned';
            if (!isset($deptMap[$dept])) {
                $deptMap[$dept] = 0;
            }
            $deptMap[$dept]++;

            // Tenure Calculation
            $joiningDate = Carbon::parse($sep->employee->joining_date);
            $exitDate = Carbon::parse($sep->exit_date);
            $days = $exitDate->diffInDays($joiningDate);
            $totalTenureDays += $days;

            if ($days < 180) { // Approx 6 months
                $preConfirmationExits++;
                $tenureBuckets['Below 6m']++;
            } elseif ($days <= 730) {
                $tenureBuckets['6-24m']++;
            } elseif ($days <= 1825) {
                $tenureBuckets['2-5y']++;
            } else {
                $tenureBuckets['5y+']++;
            }
        }

        $avgTenureMonths = $totalExits > 0 ? round(($totalTenureDays / $totalExits) / 30, 1) : 0;

        // Trend Data (last 6 months)
        $trendMonths = [];
        $trendCounts = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $trendMonths[] = $month->format('M');
            $trendCounts[] = EmployeeSeparation::whereHas('employee', function ($q) use ($businessId) {
                $q->where('business_id', $businessId);
            })->whereYear('exit_date', $month->year)
              ->whereMonth('exit_date', $month->month)
              ->count();
        }

        // Data for charts
        $reasonLabels = array_keys($reasonsMap);
        $reasonSeries = array_values($reasonsMap);
        
        $deptLabels = array_keys($deptMap);
        $deptSeries = array_values($deptMap);
        
        $tenureLabels = array_keys($tenureBuckets);
        $tenureSeries = array_values($tenureBuckets);

        return view('admin.separation.dashboard', compact(
            'startDate', 'endDate', 'totalExits', 'avgTenureMonths', 'retentionPercentage', 'preConfirmationExits',
            'reasonLabels', 'reasonSeries', 'deptLabels', 'deptSeries', 
            'tenureLabels', 'tenureSeries', 'trendMonths', 'trendCounts',
            'separations'
        ));
    }
}
