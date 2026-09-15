<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $employee = Employee::with('permission')->findOrFail($request->id);
        return view('admin.employee.profile.permissions', compact('employee'));
    }

    public function update(Request $request)
    {
        $employee = Employee::findOrFail($request->id);

        $data = [
            'selfie_punch' => $request->has('selfie_punch') ? true : false,
            'face_recognition' => $request->has('face_recognition') ? true : false,
            'selfie_at_all_locations' => $request->has('selfie_at_all_locations') ? true : false,
            'remote_punch' => $request->has('remote_punch') ? true : false,
            'missed_punch' => $request->has('missed_punch') ? true : false,
            'web_chat_punch' => $request->has('web_chat_punch') ? true : false,
            'time_relaxation' => $request->has('time_relaxation') ? true : false,
            'scan_at_all_locations' => $request->has('scan_at_all_locations') ? true : false,
            'ignore_time_strikes' => $request->has('ignore_time_strikes') ? true : false,
            'auto_punch_in_out' => $request->has('auto_punch_in_out') ? true : false,
            
            'missed_punch_limit' => $request->input('missed_punch_limit', 0),
            'strike_exemption_limit' => $request->input('strike_exemption_limit', 0),
            
            'visit_punch' => $request->has('visit_punch') ? true : false,
            'visit_punch_approval' => $request->has('visit_punch_approval') ? true : false,
            'visit_punch_attendance' => $request->has('visit_punch_attendance') ? true : false,
            'live_travel' => $request->has('live_travel') ? true : false,
            'live_travel_attendance' => $request->has('live_travel_attendance') ? true : false,
            
            'give_badges' => $request->has('give_badges') ? true : false,
            'give_rewards' => $request->has('give_rewards') ? true : false,
        ];

        $employee->permission()->updateOrCreate(
            ['employee_id' => $employee->id],
            $data
        );

        return redirect()->back()->with('success', 'Permissions updated successfully');
    }
}
