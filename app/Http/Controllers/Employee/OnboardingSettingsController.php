<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OnboardingSetting;

class OnboardingSettingsController extends Controller
{
    public function index()
    {
        // Define default settings categories and keys
        $defaultSettings = [
            'Personal Information' => [
                'pan_card' => 'PAN Card',
                'aadhaar_card' => 'Aadhaar Card',
                'passport' => 'Passport',
                'driving_license' => 'Driving License',
            ],
            'Family Details' => [
                'father_details' => 'Father Details',
                'mother_details' => 'Mother Details',
                'spouse_details' => 'Spouse Details',
            ],
            'Addresses' => [
                'current_address' => 'Current Address',
                'permanent_address' => 'Permanent Address',
            ],
            'Bank Details' => [
                'bank_account' => 'Bank Account Info',
                'cancelled_cheque' => 'Cancelled Cheque Upload',
            ]
        ];

        // Ensure defaults exist in DB
        foreach ($defaultSettings as $category => $items) {
            foreach ($items as $key => $label) {
                OnboardingSetting::firstOrCreate(
                    ['key' => $key],
                    ['category' => $category, 'is_required' => false]
                );
            }
        }

        $settings = OnboardingSetting::all()->groupBy('category');
        
        // Pass labels mapping for display
        $labels = [];
        foreach ($defaultSettings as $items) {
            foreach ($items as $key => $label) {
                $labels[$key] = $label;
            }
        }

        return view('admin.employee.onboarding.settings', compact('settings', 'labels'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'key' => 'required|string|exists:onboarding_settings,key',
            'is_required' => 'required|boolean',
        ]);

        $setting = OnboardingSetting::where('key', $request->key)->firstOrFail();
        $setting->update(['is_required' => $request->is_required]);

        return response()->json([
            'success' => true,
            'message' => 'Setting updated successfully.'
        ]);
    }
}
