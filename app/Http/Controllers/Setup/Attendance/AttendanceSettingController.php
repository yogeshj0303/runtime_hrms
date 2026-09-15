<?php

namespace App\Http\Controllers\Setup\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceSetting;

class AttendanceSettingController extends Controller
{
    public function index()
    {
        $settings = AttendanceSetting::where('business_id', Auth::user()->active_business_id)->first();
        
        return view('admin.setup.attandance.settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'default_attendance' => 'required|in:Present,Absent',
        ]);

        AttendanceSetting::updateOrCreate(
            ['business_id' => Auth::user()->active_business_id],
            [
                'user_id' => Auth::id(),
                'default_attendance' => $request->default_attendance,
                'mark_out_every_second_punch' => $request->has('mark_out_every_second_punch'),
                'manual_attendance_enabled' => $request->has('manual_attendance_enabled'),
                'enable_manual_attendance' => $request->has('manual_attendance_enabled'),
                'holiday_sandwich_rule' => $request->has('holiday_sandwich_rule'),
                'holiday_absent_both_days' => $request->has('holiday_absent_both_days'),
                'holiday_restrict_one_day' => $request->has('holiday_restrict_one_day'),
                'week_off_sandwich_rule' => $request->has('week_off_sandwich_rule'),
                'week_off_absent_both_days' => $request->has('week_off_absent_both_days'),
                'week_off_restrict_one_day' => $request->has('week_off_restrict_one_day'),
            ]
        );

        return redirect()->route('attendance-settings.index')
            ->with('success', 'Attendance Settings Saved Successfully.');
    }
}
