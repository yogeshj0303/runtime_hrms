<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OnboardingForm;
use App\Models\Employee;

class CandidateOnboardingController extends Controller
{
    public function verifyToken($token)
    {
        $form = OnboardingForm::where('token', $token)->firstOrFail();
        
        // Ensure form is not already completed
        if ($form->status == 'Approved') {
            return redirect('/')->with('error', 'This onboarding link has expired or is already completed.');
        }

        return view('candidate.onboarding', compact('form'));
    }

    public function submit(Request $request, $token)
    {
        $form = OnboardingForm::where('token', $token)->firstOrFail();
        $employee = $form->employee;

        // Fetch dynamic settings
        $settings = \App\Models\OnboardingSetting::where('is_required', true)->pluck('key')->toArray();

        // Base validation rules
        $rules = [
            'address' => 'required|string',
            'identity_type' => 'required|string',
            'identity_number' => 'required|string',
        ];

        // Apply dynamic rules based on settings
        if (in_array('current_address', $settings) || in_array('permanent_address', $settings)) {
            $rules['address'] = 'required|string';
        }
        if (in_array('pan_card', $settings)) {
            $rules['pan_number'] = 'required|string';
        }
        if (in_array('aadhaar_card', $settings)) {
            $rules['aadhaar_number'] = 'required|string';
        }
        if (in_array('bank_account', $settings)) {
            $rules['bank_account_number'] = 'required|string';
        }

        // Process candidate inputs
        $request->validate($rules);

        // Update employee record
        $employee->update([
            'address' => $request->address,
        ]);

        // Update form status to Submitted
        $form->update([
            'status' => 'Approved', // Simulating final completion for now, could be 'Submitted' for HR review
        ]);

        return redirect()->back()->with('success', 'Your onboarding details have been submitted successfully! HR will contact you shortly.');
    }
}
