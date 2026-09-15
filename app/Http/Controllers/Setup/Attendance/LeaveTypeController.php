<?php

namespace App\Http\Controllers\Setup\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\LeaveType;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $leaveTypes = LeaveType::where('business_id', Auth::user()->active_business_id)
            ->latest()
            ->get();
        
        return view('admin.setup.attandance.leave-types.index', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        $businessId = Auth::user()->active_business_id;

        $request->validate([
            'name' => [
                'required',
                'max:255',
                Rule::unique('leave_types')->where(function ($query) use ($businessId) {
                    return $query->where('business_id', $businessId);
                })
            ],
            'short_name' => 'required|max:50',
            'status' => 'required|in:active,inactive',
            'probation_rule' => 'required|in:allow,disallow',
        ]);

        LeaveType::create([
            'user_id' => Auth::id(),
            'business_id' => $businessId,
            'name' => $request->name,
            'short_name' => $request->short_name,
            'color' => $request->color ?? '#007bff',
            'description' => $request->description,
            'status' => $request->status,
            'is_paid_leave' => $request->has('is_paid_leave'),
            'maintain_leave_balance' => $request->has('maintain_leave_balance'),
            'allow_leave_requests' => $request->has('allow_leave_requests'),
            'allow_future_requests' => $request->has('allow_future_requests'),
            'track_balance' => $request->has('track_balance'),
            'probation_rule' => $request->probation_rule,
            'advance_leave_days' => $request->advance_leave_days ?? 0,
            'past_request_days' => $request->past_request_days ?? 0,
            'monthly_limit' => $request->monthly_limit ?? 0,
            'request_limit' => $request->request_limit ?? 0,
        ]);

        return redirect()->route('leave-types.index')
            ->with('success', 'Leave Type Created Successfully.');
    }

    public function edit($id)
    {
        $leaveType = LeaveType::where('business_id', Auth::user()->active_business_id)
            ->findOrFail($id);

        return response()->json($leaveType);
    }

    public function update(Request $request, $id)
    {
        $businessId = Auth::user()->active_business_id;

        $leaveType = LeaveType::where('business_id', $businessId)
            ->findOrFail($id);

        $request->validate([
            'name' => [
                'required',
                'max:255',
                Rule::unique('leave_types')->where(function ($query) use ($businessId) {
                    return $query->where('business_id', $businessId);
                })->ignore($id)
            ],
            'short_name' => 'required|max:50',
            'status' => 'required|in:active,inactive',
            'probation_rule' => 'required|in:allow,disallow',
        ]);

        $leaveType->update([
            'name' => $request->name,
            'short_name' => $request->short_name,
            'color' => $request->color ?? '#007bff',
            'description' => $request->description,
            'status' => $request->status,
            'is_paid_leave' => $request->has('is_paid_leave'),
            'maintain_leave_balance' => $request->has('maintain_leave_balance'),
            'allow_leave_requests' => $request->has('allow_leave_requests'),
            'allow_future_requests' => $request->has('allow_future_requests'),
            'track_balance' => $request->has('track_balance'),
            'probation_rule' => $request->probation_rule,
            'advance_leave_days' => $request->advance_leave_days ?? 0,
            'past_request_days' => $request->past_request_days ?? 0,
            'monthly_limit' => $request->monthly_limit ?? 0,
            'request_limit' => $request->request_limit ?? 0,
        ]);

        return redirect()->route('leave-types.index')
            ->with('success', 'Leave Type Updated Successfully.');
    }

    public function destroy($id)
    {
        $leaveType = LeaveType::where('business_id', Auth::user()->active_business_id)
            ->findOrFail($id);

        $leaveType->delete();

        return redirect()->route('leave-types.index')
            ->with('success', 'Leave Type Deleted Successfully.');
    }
}
