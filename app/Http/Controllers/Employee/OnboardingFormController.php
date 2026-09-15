<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OnboardingForm;
use App\Models\Employee;
use Illuminate\Support\Str;

class OnboardingFormController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'All');
        $query = OnboardingForm::with('employee');
        
        if ($status != 'All') {
            $query->where('status', $status);
        }
        
        $forms = $query->latest()->get();
        return view('admin.employee.onboarding-forms.index', compact('forms', 'status'));
    }

    public function recall($id)
    {
        $form = OnboardingForm::findOrFail($id);
        if ($form->status == 'Sent') {
            $form->update(['status' => 'Draft', 'token' => null]);
            return redirect()->back()->with('success', 'Form recalled and saved as Draft.');
        }
        return redirect()->back()->with('error', 'Cannot recall this form.');
    }

    public function action(Request $request, $id)
    {
        $form = OnboardingForm::findOrFail($id);
        $action = $request->input('action_type');

        if ($action == 'approve') {
            $form->update(['status' => 'Approved']);
            return redirect()->back()->with('success', 'Onboarding approved successfully.');
        } elseif ($action == 'send_again') {
            $form->update(['status' => 'Sent']);
            return redirect()->back()->with('success', 'Form sent back to candidate for corrections.');
        } elseif ($action == 'reject') {
            $form->update(['status' => 'Rejected']);
            return redirect()->back()->with('success', 'Onboarding rejected.');
        }
        
        return redirect()->back();
    }

    public function destroy($id)
    {
        $form = OnboardingForm::findOrFail($id);
        $form->delete();
        return redirect()->back()->with('success', 'Onboarding form deleted successfully.');
    }

    public function create(Request $request)
    {
        $employeeId = $request->query('employee_id');
        $formId = $request->query('form_id');
        
        $form = null;
        $employee = null;
        
        if ($formId) {
            $form = OnboardingForm::with('employee')->find($formId);
            if ($form) {
                $employee = $form->employee;
            }
        } elseif ($employeeId) {
            $employee = Employee::find($employeeId);
            $form = OnboardingForm::where('employee_id', $employeeId)->where('status', 'Draft')->first();
            if (!$form && $employee) {
                $form = OnboardingForm::create([
                    'employee_id' => $employeeId,
                    'form_name' => 'Standard Onboarding - ' . $employee->first_name,
                    'status' => 'Draft',
                ]);
            }
        }
        
        $businessId = \Illuminate\Support\Facades\Auth::user()->active_business_id;
        $designations = \App\Models\Designation::where('business_id', $businessId)->get();
        $departments = \App\Models\Department::where('business_id', $businessId)->get();
        $locations = class_exists('\App\Models\Location') ? \App\Models\Location::where('business_id', $businessId)->get() : collect();
        $costCenters = class_exists('\App\Models\CostCenter') ? \App\Models\CostCenter::where('business_id', $businessId)->get() : collect();
        $grades = class_exists('\App\Models\Grade') ? \App\Models\Grade::where('business_id', $businessId)->get() : collect();
        $businessUnits = class_exists('\App\Models\BusinessUnit') ? \App\Models\BusinessUnit::where('business_id', $businessId)->get() : collect();
        
        $shiftPolicies = class_exists('\App\Models\ShiftPolicy') ? \App\Models\ShiftPolicy::where('business_id', $businessId)->get() : collect();
        $weekOffPolicies = class_exists('\App\Models\WeekOffPolicy') ? \App\Models\WeekOffPolicy::where('business_id', $businessId)->get() : collect();
        $overtimePolicies = class_exists('\App\Models\OvertimePolicy') ? \App\Models\OvertimePolicy::where('business_id', $businessId)->get() : collect();
        $leavePolicies = class_exists('\App\Models\LeaveType') ? \App\Models\LeaveType::where('business_id', $businessId)->get() : collect();
        $timeRules = class_exists('\App\Models\TimeRule') ? \App\Models\TimeRule::where('business_id', $businessId)->get() : collect();

        // Mock data for policies (used in Part C in the future)
        $policies = collect([
            (object)['id' => 1, 'name' => 'Code of Conduct'],
            (object)['id' => 2, 'name' => 'IT Security Policy'],
        ]);

        return view('admin.employee.onboarding-forms.create', compact(
            'form', 'employee', 'policies', 
            'designations', 'departments', 'locations', 'costCenters', 
            'grades', 'businessUnits', 'shiftPolicies', 'weekOffPolicies',
            'overtimePolicies', 'leavePolicies', 'timeRules'
        ));
    }

    public function store(Request $request)
    {
        $formId = $request->input('form_id');
        $step = $request->input('step', 'part_a');
        
        if ($step == 'part_a') {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'mobile' => 'nullable|string|max:20',
                'joining_date' => 'nullable|date',
                'confirmation_date' => 'nullable|date',
                'dob' => 'nullable|date',
            ], [
                'name.required' => 'Candidate Name is required.',
                'email.email' => 'Please provide a valid email address.',
            ]);
        }

        if (!$formId) {
            // Create a new employee/candidate record
            $businessId = \Illuminate\Support\Facades\Auth::user()->active_business_id;
            $name = trim((string)$request->input('name', '')) ?: 'Candidate';
            $email = trim((string)$request->input('email', '')) ?: ('candidate_' . time() . '_' . rand(1000, 9999) . '@dummy.com');
            
            // Generate user
            $user = \App\Models\User::create([
                'name' => $name,
                'email' => $email,
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'active_business_id' => $businessId,
            ]);

            $names = explode(' ', $name);
            $firstName = $names[0] ?? 'Candidate';
            $lastName = count($names) > 1 ? implode(' ', array_slice($names, 1)) : null;
            
            $employee = \App\Models\Employee::create([
                'user_id' => $user->id,
                'business_id' => $businessId,
                'employee_code' => 'CAND-'.rand(1000, 9999), // Candidate code
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $request->input('email'),
                'phone' => $request->input('mobile'),
                'joining_date' => $request->input('joining_date'),
                'confirmation_date' => $request->input('confirmation_date'),
                'dob' => $request->input('dob'),
                'gender' => $request->input('gender', 'Male'),
                'status' => 'inactive',
            ]);

            $form = \App\Models\OnboardingForm::create([
                'employee_id' => $employee->id,
                'status' => 'Draft',
            ]);
        } else {
            $form = \App\Models\OnboardingForm::findOrFail($formId);
        }

        if ($step == 'part_a') {
            $form->update([
                'part_a_data' => [
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'mobile' => $request->input('mobile'),
                    'pan_verified' => $request->has('pan_verified'),
                    'aadhaar_verified' => $request->has('aadhaar_verified'),
                    'bank_verified' => $request->has('bank_verified'),
                ]
            ]);
            return redirect()->route('onboarding.forms.create', ['form_id' => $form->id])->with('success', 'Part A saved successfully. Please complete Part B.');
        } 
        elseif ($step == 'part_b') {
            $form->update([
                'part_b_data' => $request->except(['_token', 'step', 'form_id'])
            ]);
            // For now, redirect to index since Part C isn't built in the new UI yet
            return redirect()->route('onboarding.forms.index')->with('success', 'Onboarding Form created successfully!');
        }
        elseif ($step == 'part_c') {
            // Handle file upload if any (mocking path for now)
            $offerLetterPath = null;
            if ($request->hasFile('offer_letter')) {
                // $offerLetterPath = $request->file('offer_letter')->store('offer_letters', 'public');
                $offerLetterPath = 'dummy_path.pdf';
            }
            
            $partCData = $form->part_c_data ?? [];
            $partCData['salary_structure'] = $request->input('salary_structure');
            if ($offerLetterPath) {
                $partCData['offer_letter'] = $offerLetterPath;
            }

            $form->update([
                'part_c_data' => $partCData
            ]);

            return redirect()->back()->with('step', 'finalize')->with('success', 'Part C saved successfully.');
        }

        return redirect()->back();
    }

    public function finalize(Request $request, $id)
    {
        $form = OnboardingForm::findOrFail($id);
        
        $sendForm = $request->has('send_form');
        
        if ($sendForm) {
            $form->update([
                'status' => 'Sent',
                'token' => Str::random(60),
            ]);
            // TODO: Send Email to Candidate with URL: route('candidate.onboarding', ['token' => $form->token])
            return redirect()->route('onboarding.index')->with('success', 'Form finalized and invitation sent to candidate.');
        } else {
            $form->update([
                'status' => 'Draft',
            ]);
            return redirect()->route('onboarding.index')->with('success', 'Form saved as Draft.');
        }
    }
}
