<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

class FlightRiskController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->input('status', 'High Risk');

        $riskCounts = [
            'No Risk' => Employee::where('status', 'Active')->where('flight_risk_status', 'No Risk')->count(),
            'Moderate Risk' => Employee::where('status', 'Active')->where('flight_risk_status', 'Moderate Risk')->count(),
            'High Risk' => Employee::where('status', 'Active')->where('flight_risk_status', 'High Risk')->count(),
            'Untracked' => Employee::where('status', 'Active')->where('flight_risk_status', 'Untracked')->count(),
            'Uncalculated' => Employee::where('status', 'Active')->where('flight_risk_status', 'Uncalculated')->count(),
        ];

        $employees = Employee::with(['workProfiles.department', 'workProfiles.location', 'user'])
            ->where('status', 'Active')
            ->where('flight_risk_status', $statusFilter)
            ->get();

        return view('dashboards.flight-risk', compact('riskCounts', 'employees', 'statusFilter'));
    }

    public function markUntracked($employee_id)
    {
        $employee = Employee::findOrFail($employee_id);
        $employee->flight_risk_status = 'Untracked';
        $employee->save();

        return redirect()->back()->with('success', 'Employee marked as untracked.');
    }
}
