<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $employee = \App\Models\Employee::with([
            'profile', 
            'workProfiles.designation', 
            'workProfiles.department', 
            'workProfiles.location', 
            'workProfiles.businessUnit', 
            'workProfiles.reportingManager', 
            'workProfiles.hrManager',
            'workProfiles.indirectManager',
            'salaryRevisions', 
            'identity',
            'addresses', 
            'familyMembers', 
            'permission'
        ])->findOrFail($request->id);

        $prevEmployee = \App\Models\Employee::where('id', '<', $employee->id)->orderBy('id', 'desc')->first();
        $nextEmployee = \App\Models\Employee::where('id', '>', $employee->id)->orderBy('id', 'asc')->first();

        $directReports = \App\Models\EmployeeWorkProfile::where('reporting_manager_id', $employee->id)
            ->where('is_current', true)
            ->with(['employee.profile', 'designation', 'department'])
            ->get();

        return view('admin.employee.profile.summary', compact('employee', 'prevEmployee', 'nextEmployee', 'directReports'));
    }

    public function basic(Request $request)
    {
        $employee = \App\Models\Employee::with('profile')->findOrFail($request->id);

        return view('admin.employee.profile.basic', compact('employee'));
    }

    public function updateBasic(Request $request)
    {
        $employee = \App\Models\Employee::findOrFail($request->id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'employee_code' => 'required|string|max:255',
        ]);

        $employee->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'phone' => $request->mobile_number,
            'email' => $request->official_email,
            'employee_code' => $request->employee_code,
        ]);

        $profileData = [
            'confirmation_date' => $request->confirmation_date,
            'office_phone' => $request->office_phone,
            'official_email' => $request->official_email,
            'biometric_code' => $request->biometric_code,
            'notice_period' => $request->notice_period,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'marital_status' => $request->marital_status,
            'personal_email' => $request->personal_email,
            'personal_phone' => $request->personal_phone,
            'emergency_contact' => $request->emergency_contact,
        ];

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $profileData['profile_photo'] = $path;
        }

        $employee->profile()->updateOrCreate(
            ['employee_id' => $employee->id],
            $profileData
        );

        if ($request->filled('joining_date')) {
            $employee->joining_date = $request->joining_date;
            $employee->save();
        }

        return redirect()->back()->with('success', 'Basic Info updated successfully!');
    }

    public function saveFace(Request $request)
    {
        $employee = \App\Models\Employee::findOrFail($request->id);

        $request->validate([
            'face_image_base64' => 'required|string',
        ]);

        $employee->face_image = $request->face_image_base64;
        $employee->face_registered = 1;
        $employee->save();

        \Illuminate\Support\Facades\Cache::forget('employee_profile_' . $employee->user_id);
        \Illuminate\Support\Facades\Cache::forget('user_' . $employee->user_id);

        return response()->json([
            'status' => true,
            'message' => 'Face registered successfully!'
        ]);
    }

    public function addTag(Request $request)
    {
        $employee = \App\Models\Employee::findOrFail($request->id);
        $request->validate(['tag' => 'required|string|max:50']);

        $tags = $employee->tags ? json_decode($employee->tags, true) : [];
        if (!in_array($request->tag, $tags)) {
            $tags[] = $request->tag;
            $employee->tags = json_encode($tags);
            $employee->save();
        }

        return response()->json(['status' => true, 'tags' => $tags]);
    }

    public function removeTag(Request $request)
    {
        $employee = \App\Models\Employee::findOrFail($request->id);
        $request->validate(['tag' => 'required|string']);

        $tags = $employee->tags ? json_decode($employee->tags, true) : [];
        if (($key = array_search($request->tag, $tags)) !== false) {
            unset($tags[$key]);
            $employee->tags = json_encode(array_values($tags));
            $employee->save();
        }

        return response()->json(['status' => true, 'tags' => array_values($tags)]);
    }
}
