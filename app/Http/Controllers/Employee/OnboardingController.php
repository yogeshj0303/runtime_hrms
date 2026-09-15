<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OnboardingController extends Controller
{
    public function index(Request $request)
    {
        $businessId = Auth::user()->active_business_id;

        // Date range filter
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Base query for forms within date range
        $formsQuery = \App\Models\OnboardingForm::with(['employee' => function($q) use ($businessId) {
            $q->where('business_id', $businessId);
        }])->whereHas('employee', function($q) use ($businessId) {
            $q->where('business_id', $businessId);
        })->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        // Analytics Data
        $totalForms = (clone $formsQuery)->count();
        $offersSent = (clone $formsQuery)->where('status', 'Sent')->count();
        $inProgress = (clone $formsQuery)->where('status', 'Draft')->count();
        $totalHires = (clone $formsQuery)->where('status', 'Approved')->count();

        // Chart Data: Completion %
        $completionData = [
            $totalHires, // Approved
            $offersSent, // Sent
            $inProgress  // Draft
        ];

        // Chart Data: Hires by Department
        $departments = \App\Models\Department::where('business_id', $businessId)->get();
        $deptNames = [];
        $deptCounts = [];
        foreach ($departments as $dept) {
            $count = Employee::where('business_id', $businessId)
                ->where('department', $dept->name)
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->count();
            if ($count > 0) {
                $deptNames[] = $dept->name;
                $deptCounts[] = $count;
            }
        }
        
        // Chart Data: Hiring Trend (Joinings per month for the last 15 months)
        $trendMonths = [];
        $trendCounts = [];
        for ($i = 14; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $trendMonths[] = $month->format('M Y');
            $trendCounts[] = Employee::where('business_id', $businessId)
                ->whereYear('joining_date', $month->year)
                ->whereMonth('joining_date', $month->month)
                ->count();
        }

        // Get actual forms for data table
        $forms = $formsQuery->latest()->get();

        return view('admin.employee.onboarding.index', compact(
            'forms', 'totalForms', 'offersSent', 'inProgress', 'totalHires',
            'startDate', 'endDate', 'completionData', 'deptNames', 'deptCounts',
            'trendMonths', 'trendCounts'
        ));
    }

    public function create()
    {
        $businessId = Auth::user()->active_business_id;
        $designations = \App\Models\Designation::where('business_id', $businessId)->get();
        $departments = \App\Models\Department::where('business_id', $businessId)->get();
        
        // Fetch new models for Work Profile (using empty collections if missing for now)
        $locations = class_exists('\App\Models\Location') ? \App\Models\Location::where('business_id', $businessId)->get() : collect();
        $costCenters = class_exists('\App\Models\CostCenter') ? \App\Models\CostCenter::where('business_id', $businessId)->get() : collect();
        $grades = class_exists('\App\Models\Grade') ? \App\Models\Grade::where('business_id', $businessId)->get() : collect();
        $businessUnits = class_exists('\App\Models\BusinessUnit') ? \App\Models\BusinessUnit::where('business_id', $businessId)->get() : collect();
        
        // Policies
        $shiftPolicies = class_exists('\App\Models\Shift') ? \App\Models\Shift::where('business_id', $businessId)->get() : collect();
        $weekOffPolicies = class_exists('\App\Models\WeekOffPolicy') ? \App\Models\WeekOffPolicy::where('business_id', $businessId)->get() : collect();

        return view('admin.employee.onboarding.create', compact(
            'designations', 'departments', 'locations', 'costCenters', 
            'grades', 'businessUnits', 'shiftPolicies', 'weekOffPolicies'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20', // Changed to required
            'gender' => 'required|in:Male,Female,Other',
            'dob' => 'nullable|date',
            'employee_code' => 'required|string|max:50|unique:employees,employee_code',
            'joining_date' => 'required|date',
            'confirmation_date' => 'nullable|date',
            'designation' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
        ]);

        $businessId = Auth::user()->active_business_id;
        $businessCode = \App\Models\Business::find($businessId)->business_code ?? 'B-'.rand(1000, 9999);

        // Create User account for employee
        $user = User::create([
            'name' => trim($request->first_name . ' ' . $request->last_name),
            'email' => $request->email,
            'password' => Hash::make('password123'), // Default password
            'active_business_id' => $businessId,
        ]);

        // Create Employee record
        $employee = Employee::create([
            'user_id' => $user->id,
            'business_id' => $businessId,
            'business_code' => $businessCode,
            'employee_code' => $request->employee_code,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->mobile_number,
            'joining_date' => $request->joining_date,
            'confirmation_date' => $request->confirmation_date,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'department' => collect($departments ?? [])->firstWhere('id', $request->department)->name ?? $request->department,
            'designation' => collect($designations ?? [])->firstWhere('id', $request->designation)->name ?? $request->designation,
            'status' => 'active',
        ]);

        // Save Work Profile if it exists
        if (class_exists('\App\Models\EmployeeWorkProfile')) {
            \App\Models\EmployeeWorkProfile::create([
                'employee_id' => $employee->id,
                'business_unit_id' => $request->business_unit ?: null,
                'location_id' => $request->location ?: null,
                'cost_center_id' => $request->cost_center ?: null,
                'department_id' => $request->department ?: null,
                'designation_id' => $request->designation ?: null,
                'grade_id' => $request->grade ?: null,
                'is_current' => true,
                'effective_from' => $request->joining_date,
            ]);
        }
        
        // Save Policies if provided
        if ($request->shift_policy || $request->week_off_policy) {
            // Check if EmployeePolicy model exists, else just skip or create dummy
            if (class_exists('\App\Models\EmployeePolicy')) {
                \App\Models\EmployeePolicy::create([
                    'business_id' => $businessId,
                    'employee_id' => $employee->id,
                    'shift_policy_id' => $request->shift_policy ?: null,
                    'week_off_policy_id' => $request->week_off_policy ?: null,
                    'effective_from' => $request->joining_date,
                ]);
            }
        }

        // Self-Service Access Check
        if ($request->has('send_mobile_login') || $request->has('send_web_login')) {
            // TODO: Send Email / SMS to employee with credentials
            // e.g., event(new EmployeeCreated($employee, $user, 'password123'));
        }

        return redirect()->route('onboarding.index')->with('success', 'Employee successfully added to onboarding.');
    }
}
