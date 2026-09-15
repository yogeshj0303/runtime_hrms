<?php

namespace App\Http\Controllers\Setup\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WeekOffPolicy;
use App\Models\Employee;

class WeekOffPolicyController extends Controller
{
    public function index()
    {
        $policies = WeekOffPolicy::where('business_id', Auth::user()->active_business_id)
            ->latest()
            ->get();

        return view('admin.setup.attandance.week-off-policy.index', compact('policies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $isDefault = $request->has('is_default');
        $businessId = Auth::user()->active_business_id;

        // If this is set as default, remove default from existing policies
        if ($isDefault) {
            WeekOffPolicy::where('business_id', $businessId)->update(['is_default' => false]);
        }

        WeekOffPolicy::create([
            'user_id' => Auth::id(),
            'business_id' => $businessId,
            'name' => $request->name,
            'description' => $request->description,
            'is_default' => $isDefault,
            'is_payable' => $request->has('is_payable'),
            'sunday_weeks' => $request->sunday_weeks ?? [],
            'monday_weeks' => $request->monday_weeks ?? [],
            'tuesday_weeks' => $request->tuesday_weeks ?? [],
            'wednesday_weeks' => $request->wednesday_weeks ?? [],
            'thursday_weeks' => $request->thursday_weeks ?? [],
            'friday_weeks' => $request->friday_weeks ?? [],
            'saturday_weeks' => $request->saturday_weeks ?? [],
        ]);

        return redirect()->route('week-off-policies.index')
            ->with('success', 'Week Off Policy Created Successfully.');
    }

    public function edit($id)
    {
        $policy = WeekOffPolicy::where('business_id', Auth::user()->active_business_id)
            ->findOrFail($id);

        return response()->json($policy);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $policy = WeekOffPolicy::where('business_id', Auth::user()->active_business_id)
            ->findOrFail($id);

        $isDefault = $request->has('is_default');
        $businessId = Auth::user()->active_business_id;

        // If this is set as default, remove default from existing policies
        if ($isDefault) {
            WeekOffPolicy::where('business_id', $businessId)->update(['is_default' => false]);
        }

        $policy->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_default' => $isDefault,
            'is_payable' => $request->has('is_payable'),
            'sunday_weeks' => $request->sunday_weeks ?? [],
            'monday_weeks' => $request->monday_weeks ?? [],
            'tuesday_weeks' => $request->tuesday_weeks ?? [],
            'wednesday_weeks' => $request->wednesday_weeks ?? [],
            'thursday_weeks' => $request->thursday_weeks ?? [],
            'friday_weeks' => $request->friday_weeks ?? [],
            'saturday_weeks' => $request->saturday_weeks ?? [],
        ]);

        return redirect()->route('week-off-policies.index')
            ->with('success', 'Week Off Policy Updated Successfully.');
    }

    public function destroy($id)
    {
        $policy = WeekOffPolicy::where('business_id', Auth::user()->active_business_id)
            ->findOrFail($id);

        // Check if assigned to any employee
        if (Employee::where('week_off_policy_id', $policy->id)->exists()) {
            return redirect()->route('week-off-policies.index')
                ->withErrors(['error' => 'Cannot delete policy because it is currently assigned to employees.']);
        }

        $policy->delete();

        return redirect()->route('week-off-policies.index')
            ->with('success', 'Week Off Policy Deleted Successfully.');
    }
}
