<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShiftRosterController extends Controller
{
    public function index(Request $request)
    {
        $business_id = auth()->user()->business_id;

        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));
        
        $startDate = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $daysInMonth = $startDate->daysInMonth;

        $employees = \App\Models\Employee::where('business_id', $business_id)
                        ->whereIn('status', ['Active', 'active'])
                        ->get();

        $shifts = \App\Models\Shift::where('business_id', $business_id)->get();

        $rosters = \App\Models\ShiftRoster::where('business_id', $business_id)
                        ->whereBetween('roster_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                        ->get();

        // Organize roster data: $rosterData[employee_id][date] = shift_id
        $rosterData = [];
        foreach ($rosters as $roster) {
            $dateStr = \Carbon\Carbon::parse($roster->roster_date)->format('Y-m-d');
            $rosterData[$roster->employee_id][$dateStr] = $roster->shift_id;
        }

        return view('admin.attendance.shift-roster.index', compact(
            'employees', 'shifts', 'rosterData', 'month', 'year', 'startDate', 'daysInMonth'
        ));
    }

    public function updateBulk(Request $request)
    {
        $business_id = auth()->user()->business_id;
        $rosters = $request->input('rosters', []);

        foreach ($rosters as $item) {
            if (!empty($item['shift_id'])) {
                \App\Models\ShiftRoster::updateOrCreate(
                    [
                        'business_id' => $business_id,
                        'employee_id' => $item['employee_id'],
                        'roster_date' => $item['roster_date']
                    ],
                    [
                        'shift_id' => $item['shift_id'],
                        'assigned_by' => auth()->id()
                    ]
                );
            } else {
                // If shift_id is empty, they might be clearing it. Delete if exists.
                \App\Models\ShiftRoster::where('business_id', $business_id)
                    ->where('employee_id', $item['employee_id'])
                    ->where('roster_date', $item['roster_date'])
                    ->delete();
            }
        }

        return response()->json(['success' => true]);
    }

    public function autoGenerate(Request $request)
    {
        $business_id = auth()->user()->business_id;
        $month = $request->input('month');
        $year = $request->input('year');

        $startDate = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // 1. Get default shift from ShiftPolicy per employee. 
        // 2. We'll simply find active employee shift histories and populate the month.
        $employees = \App\Models\Employee::where('business_id', $business_id)
                        ->whereIn('status', ['Active', 'active'])
                        ->get();

        foreach ($employees as $emp) {
            // Get their active ShiftPolicy if exists, or default Shift
            $policyAssign = \App\Models\EmployeePolicyAssignment::where('employee_id', $emp->id)
                                ->where('policy_type', 'shift')
                                ->first();

            if ($policyAssign) {
                $policy = \App\Models\ShiftPolicy::find($policyAssign->policy_id);
                if ($policy) {
                    for ($d = $startDate->copy(); $d->lte($endDate); $d->addDay()) {
                        $dayName = strtolower($d->format('l'));
                        $shiftId = $policy->{$dayName.'_shift_id'} ?? $policy->default_shift_id;
                        
                        if ($shiftId) {
                            \App\Models\ShiftRoster::updateOrCreate(
                                [
                                    'business_id' => $business_id,
                                    'employee_id' => $emp->id,
                                    'roster_date' => $d->format('Y-m-d')
                                ],
                                [
                                    'shift_id' => $shiftId,
                                    'assigned_by' => auth()->id()
                                ]
                            );
                        }
                    }
                }
            }
        }

        return response()->json(['success' => true]);
    }

    public function downloadTemplate(Request $request)
    {
        return back()->with('info', 'Download Template feature will be available once the export class is generated.');
    }

    public function uploadExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);
        
        return back()->with('info', 'Upload feature will be available once the import class is generated.');
    }

    public function exportRoster(Request $request)
    {
        return back()->with('info', 'Export Roster feature will be available once the export class is generated.');
    }
}
