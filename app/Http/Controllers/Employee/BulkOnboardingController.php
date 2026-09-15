<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OnboardingForm;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BulkOnboardingController extends Controller
{
    public function index()
    {
        return view('admin.employee.onboarding.bulk');
    }

    public function store(Request $request)
    {
        $request->validate([
            'candidates' => 'required|array|max:25',
            'candidates.*.name' => 'required|string|max:255',
            'candidates.*.email' => 'required|email',
            'candidates.*.mobile' => 'required|string|max:20',
        ]);

        $candidates = $request->input('candidates');
        $businessId = Auth::user()->active_business_id ?? 1; // Default fallback
        $businessCode = \App\Models\Business::find($businessId)->business_code ?? 'B-'.rand(1000, 9999);

        // Verification flags
        $panVerified = $request->has('req_pan');
        $aadhaarVerified = $request->has('req_aadhaar');
        $bankVerified = $request->has('req_bank');

        $sentCount = 0;

        foreach ($candidates as $cand) {
            // Create minimal user/employee for the placeholder
            $user = User::firstOrCreate(
                ['email' => $cand['email']],
                [
                    'name' => $cand['name'],
                    'password' => Hash::make(Str::random(10)),
                    'active_business_id' => $businessId,
                ]
            );

            $names = explode(' ', $cand['name'], 2);
            $firstName = $names[0];
            $lastName = $names[1] ?? '';

            $employee = Employee::firstOrCreate(
                ['email' => $cand['email']],
                [
                    'user_id' => $user->id,
                    'business_id' => $businessId,
                    'business_code' => $businessCode,
                    'employee_code' => 'EMP-'.rand(10000, 99999), // Placeholder
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'phone' => $cand['mobile'],
                    'joining_date' => now(), // Placeholder
                    'created_by' => Auth::id(),
                    'status' => 'active',
                ]
            );

            // Create onboarding form
            $form = OnboardingForm::create([
                'employee_id' => $employee->id,
                'form_name' => 'Bulk Onboarding - ' . $cand['name'],
                'status' => 'Sent',
                'token' => Str::random(60),
                'part_a_data' => [
                    'name' => $cand['name'],
                    'email' => $cand['email'],
                    'mobile' => $cand['mobile'],
                    'pan_verified' => $panVerified,
                    'aadhaar_verified' => $aadhaarVerified,
                    'bank_verified' => $bankVerified,
                ]
            ]);

            // TODO: Dispatch Email/SMS event here
            // event(new BulkOnboardingInviteSent($form));

            $sentCount++;
        }

        return redirect()->route('onboarding.forms.index')->with('success', "Successfully sent onboarding invitations to {$sentCount} candidates.");
    }
}
