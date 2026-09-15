<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeSeparation;
use Illuminate\Support\Facades\Auth;

class SeparationController extends Controller
{
    public function create()
    {
        $businessId = Auth::user()->active_business_id ?? 1;

        // Fetch active employees for the dropdown
        $employees = Employee::where('business_id', $businessId)
            ->where('status', 'active') // Or whatever active status you use
            ->get();

        // Fetch High Flight Risk Employees
        $flightRisks = Employee::where('business_id', $businessId)
            ->where('flight_risk_status', 'High Risk')
            ->where('status', 'active')
            ->get();

        return view('admin.separation.create', compact('employees', 'flightRisks'));
    }

    public function getEmployeeDetails(Request $request)
    {
        $employeeId = $request->query('employee_id');
        
        $employee = Employee::with('business')->find($employeeId);
        
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found.'], 404);
        }

        // Mock Notice Period Calculation (e.g., standard 30 days)
        $noticePeriodDays = 30;

        return response()->json([
            'success' => true,
            'data' => [
                'name' => $employee->first_name . ' ' . $employee->last_name,
                'employee_code' => $employee->employee_code,
                'designation' => $employee->designation ?? 'N/A',
                'department' => $employee->department ?? 'N/A',
                'location' => 'HQ', // Mock
                'joining_date' => $employee->joining_date ? $employee->joining_date->format('d M, Y') : 'N/A',
                'notice_period_days' => $noticePeriodDays,
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'exit_date' => 'required|date',
            'resignation_date' => 'required|date',
            'exit_reason' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        EmployeeSeparation::create([
            'employee_id' => $request->employee_id,
            'resignation_date' => $request->resignation_date,
            'exit_date' => $request->exit_date,
            'exit_reason' => $request->exit_reason,
            'retention_attempted' => $request->has('retention_attempted'),
            'status' => 'Initiated',
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('separation.dashboard')->with('success', 'Exit initiated successfully.');
    }

    public function pending(Request $request)
    {
        $businessId = Auth::user()->active_business_id ?? 1;

        $query = EmployeeSeparation::with('employee')
            ->whereHas('employee', function ($q) use ($businessId) {
                $q->where('business_id', $businessId);
            })
            ->whereIn('status', ['Initiated', 'Pending', 'Processed']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('exit_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('exit_reason')) {
            $query->where('exit_reason', $request->exit_reason);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        $pendingExits = $query->orderBy('created_at', 'desc')->get();

        return view('admin.separation.pending', compact('pendingExits'));
    }

    public function cancelExit(Request $request, $id)
    {
        $separation = EmployeeSeparation::findOrFail($id);
        $separation->update(['status' => 'Cancelled']);
        
        return response()->json(['success' => true, 'message' => 'Exit request cancelled.']);
    }

    public function processExit(Request $request, $id)
    {
        $separation = EmployeeSeparation::findOrFail($id);
        
        // Mock F&F Logic
        $separation->update(['status' => 'Processed']);

        return response()->json(['success' => true, 'message' => 'Full & Final settlement processed successfully.']);
    }

    public function finalizeExit(Request $request, $id)
    {
        $separation = EmployeeSeparation::findOrFail($id);
        
        $employee = $separation->employee;
        $employee->update([
            'status' => 'ex-employee',
            'exit_date' => $separation->exit_date
        ]);

        $separation->update(['status' => 'Completed']);

        // Mock generating and sending relieving letter

        return back()->with('success', 'Exit finalized and Relieving Letter sent to employee.');
    }

    public function exEmployees(Request $request)
    {
        $businessId = Auth::user()->active_business_id ?? 1;

        $query = EmployeeSeparation::with('employee')
            ->whereHas('employee', function ($q) use ($businessId) {
                $q->where('business_id', $businessId);
            })
            ->where('status', 'Completed');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('exit_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('exit_reason')) {
            $query->where('exit_reason', $request->exit_reason);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        $exEmployees = $query->orderBy('exit_date', 'desc')->get();

        return view('admin.separation.ex-employees', compact('exEmployees'));
    }

    public function downloadFnf($id)
    {
        $separation = EmployeeSeparation::findOrFail($id);
        // Mock PDF download
        return back()->with('success', 'F&F Statement downloaded successfully. (Mocked)');
    }

    public function reprocessExit(Request $request, $id)
    {
        $separation = EmployeeSeparation::findOrFail($id);
        $separation->update(['status' => 'Pending']);

        return redirect()->route('separation.pending')->with('success', 'Exit moved back to Pending for re-processing.');
    }

    public function rehire(Request $request, $id)
    {
        $separation = EmployeeSeparation::findOrFail($id);
        
        $employee = $separation->employee;
        $employee->update([
            'status' => 'active',
            'exit_date' => null
        ]);

        // Clear separation record or mark as deleted
        $separation->delete();

        return back()->with('success', 'Employee has been re-hired successfully.');
    }

    public function destroy($id)
    {
        $separation = EmployeeSeparation::findOrFail($id);
        $separation->delete();

        return back()->with('success', 'Separation record deleted successfully.');
    }
}
