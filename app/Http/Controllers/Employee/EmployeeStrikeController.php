<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeStrike;
use App\Models\StrikeRule;
use Illuminate\Support\Facades\Auth;

class EmployeeStrikeController extends Controller
{
    public function index($id)
    {
        $businessId = Auth::user()->active_business_id;
        $employee = Employee::where('business_id', $businessId)->findOrFail($id);
        
        $strikes = EmployeeStrike::where('employee_id', $id)
            ->with('strikeRule')
            ->orderBy('strike_date', 'desc')
            ->get();
            
        $strikeRules = StrikeRule::where('business_id', $businessId)->get();

        return view('admin.employee.profile.strikes', compact('employee', 'strikes', 'strikeRules'));
    }

    public function store(Request $request, $id)
    {
        $businessId = Auth::user()->active_business_id;
        $employee = Employee::where('business_id', $businessId)->findOrFail($id);

        $request->validate([
            'strike_date' => 'required|date',
            'strike_rule_id' => 'nullable|exists:strike_rules,id',
            'reason' => 'required|string',
        ]);

        EmployeeStrike::create([
            'business_id' => $businessId,
            'employee_id' => $employee->id,
            'strike_rule_id' => $request->strike_rule_id,
            'strike_date' => $request->strike_date,
            'reason' => $request->reason,
            'status' => 'Active',
        ]);

        return redirect()->route('employee.profile.strikes', $employee->id)
            ->with('success', 'Strike added successfully.');
    }

    public function waive($id)
    {
        $businessId = Auth::user()->active_business_id;
        $strike = EmployeeStrike::where('business_id', $businessId)->findOrFail($id);
        
        $strike->update(['status' => 'Waived']);

        return redirect()->back()->with('success', 'Strike has been waived.');
    }
}
