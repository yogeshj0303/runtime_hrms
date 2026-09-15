<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeAdditionalInformation;

class AdditionalInfoController extends Controller
{
    public function index(Request $request)
    {
        $employee = Employee::with('additionalInformation')->findOrFail($request->id);
        $additionalInfo = $employee->additionalInformation;
        return view('admin.employee.profile.additional-info', compact('employee', 'additionalInfo'));
    }

    public function update(Request $request)
    {
        $employee = Employee::findOrFail($request->id);

        $customFields = [];
        if ($request->has('custom_keys') && is_array($request->custom_keys)) {
            foreach ($request->custom_keys as $index => $key) {
                if (!empty($key)) {
                    $customFields[$key] = $request->custom_values[$index] ?? '';
                }
            }
        }

        EmployeeAdditionalInformation::updateOrCreate(
            ['employee_id' => $employee->id],
            [
                'notes' => $request->notes,
                'extra_information' => $request->extra_information,
                'custom_fields' => $customFields,
            ]
        );

        return redirect()->back()->with('success', 'Additional information updated successfully.');
    }
}
