<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

class IdentityController extends Controller
{
    public function index(Request $request)
    {
        $employee = Employee::with('identity')->findOrFail($request->id);
        return view('admin.employee.profile.identity', compact('employee'));
    }

    public function update(Request $request)
    {
        $employee = Employee::findOrFail($request->id);
        
        $identity = \App\Models\EmployeeIdentity::firstOrNew(['employee_id' => $employee->id]);
        
        if ($request->action == 'verify_bank') {
            $identity->is_bank_verified = true;
        } elseif ($request->action == 'verify_aadhaar') {
            $identity->is_aadhaar_verified = true;
        } elseif ($request->action == 'verify_pan') {
            $identity->is_pan_verified = true;
        } else {
            // Handle Bank Details
            if ($request->has('bank_name') || $request->has('ifsc') || $request->has('account_number')) {
                $identity->bank_name = $request->bank_name;
                $identity->ifsc = $request->ifsc;
                $identity->account_number = $request->account_number;
                $identity->is_bank_verified = false;
            }
            
            // Handle Aadhar
            if ($request->has('aadhaar_number')) {
                $identity->aadhaar_number = $request->aadhaar_number;
                $identity->is_aadhaar_verified = false;
            }
            
            // Handle PAN
            if ($request->has('pan_number')) {
                $identity->pan_number = $request->pan_number;
                $identity->is_pan_verified = false;
            }
            
            // Handle General Info
            if ($request->has('passport') || $request->has('driving_license') || $request->has('blood_group') || $request->has('pf_uan') || $request->has('esi_number')) {
                $identity->passport = $request->passport;
                $identity->driving_license = $request->driving_license;
                $identity->blood_group = $request->blood_group;
                $identity->pf_uan = $request->pf_uan;
                $identity->esi_number = $request->esi_number;
            }
        }
        
        // Handle KYC Checkbox
        if ($request->has('is_kyc_done_submit')) {
            $identity->is_kyc_done = $request->is_kyc_done == '1';
        }

        $identity->save();
        
        return redirect()->back()->with('success', 'Identity updated successfully');
    }
}
