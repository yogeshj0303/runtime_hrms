<?php

namespace App\Http\Controllers\Setup\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TimeRule;

class TimeRuleController extends Controller
{
    public function index()
    {
        $timeRules = TimeRule::where('business_id', Auth::user()->active_business_id)
            ->latest()
            ->get();

        $letterTemplates = \App\Models\LetterTemplate::where('type', 'Warning')->get();

        return view('admin.setup.attandance.time-rules.index', compact('timeRules', 'letterTemplates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'time_basis' => 'required',
            'from_hours' => 'required|integer|min:0',
            'from_minutes' => 'required|integer|min:0|max:59',
            'to_hours' => 'required|integer|min:0',
            'to_minutes' => 'required|integer|min:0|max:59',
        ]);

        TimeRule::create([
            'user_id' => Auth::id(),
            'business_id' => Auth::user()->active_business_id,
            'time_basis' => $request->time_basis,
            'from_hours' => $request->from_hours,
            'from_minutes' => $request->from_minutes,
            'to_hours' => $request->to_hours,
            'to_minutes' => $request->to_minutes,
            'mark_only_if_present' => $request->has('mark_only_if_present'),
            'is_active' => $request->has('is_active'),
            'warning_occurrences' => $request->warning_occurrences,
            'warning_letter' => $request->warning_letter,
            'attendance_occurrences' => $request->attendance_occurrences,
            'attendance_status' => $request->attendance_status,
            'attendance_update_type' => $request->attendance_update_type,
        ]);

        return redirect()->route('time-rules.index')
            ->with('success', 'Time Rule Created Successfully.');
    }

    public function edit($id)
    {
        $timeRule = TimeRule::where('business_id', Auth::user()->active_business_id)
            ->findOrFail($id);

        return response()->json($timeRule);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'time_basis' => 'required',
            'from_hours' => 'required|integer|min:0',
            'from_minutes' => 'required|integer|min:0|max:59',
            'to_hours' => 'required|integer|min:0',
            'to_minutes' => 'required|integer|min:0|max:59',
        ]);

        $timeRule = TimeRule::where('business_id', Auth::user()->active_business_id)
            ->findOrFail($id);

        $timeRule->update([
            'time_basis' => $request->time_basis,
            'from_hours' => $request->from_hours,
            'from_minutes' => $request->from_minutes,
            'to_hours' => $request->to_hours,
            'to_minutes' => $request->to_minutes,
            'mark_only_if_present' => $request->has('mark_only_if_present'),
            'is_active' => $request->has('is_active'),
            'warning_occurrences' => $request->warning_occurrences,
            'warning_letter' => $request->warning_letter,
            'attendance_occurrences' => $request->attendance_occurrences,
            'attendance_status' => $request->attendance_status,
            'attendance_update_type' => $request->attendance_update_type,
        ]);

        return redirect()->route('time-rules.index')
            ->with('success', 'Time Rule Updated Successfully.');
    }

    public function destroy($id)
    {
        $timeRule = TimeRule::where('business_id', Auth::user()->active_business_id)
            ->findOrFail($id);

        $timeRule->delete();

        return redirect()->route('time-rules.index')
            ->with('success', 'Time Rule Deleted Successfully.');
    }
}
