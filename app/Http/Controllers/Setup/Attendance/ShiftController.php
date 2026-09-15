<?php
namespace App\Http\Controllers\Setup\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shift;
use App\Models\ShiftPolicy;
use Carbon\Carbon;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::where(
            'business_id',
            Auth::user()->active_business_id
        )
        ->latest()
        ->get();

        return view(
            'admin.setup.attandance.work-shift.index',
            compact('shifts')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'=>'required',
            'name'=>'required',
            'start_time'=>'required'
        ]);

        $start = Carbon::parse($request->start_time);

        $end = $start->copy()
            ->addHours($request->duration_hours)
            ->addMinutes($request->duration_minutes);

        Shift::create([

            'user_id'=>Auth::id(),
            'business_id'=>Auth::user()->active_business_id,

            'code'=>$request->code,
            'name'=>$request->name,

            'start_time'=>$request->start_time,

            'duration_hours'=>$request->duration_hours,
            'duration_minutes'=>$request->duration_minutes,

            'end_time'=>$end->format('H:i:s'),

            'payable_hours'=>$request->payable_hours,
            'payable_minutes'=>$request->payable_minutes,

            'shift_type' => $request->shift_type ?? 'General',
            'break_time_minutes' => $request->break_time_minutes ?? 0,
            'grace_time_minutes' => $request->grace_time_minutes ?? 0,
            'min_working_hours' => $request->min_working_hours ?? 0,
            'max_working_hours' => $request->max_working_hours ?? 0,
            'color' => $request->color ?? '#556ee6',
            'status' => $request->status ?? 'active',

            'is_default'=>$request->has('is_default')
        ]);

        return back()->with(
            'success',
            'Shift Created Successfully.'
        );
    }

    public function update(Request $request,$id)
    {
        $shift = Shift::findOrFail($id);

        $start = Carbon::parse($request->start_time);

        $end = $start->copy()
            ->addHours($request->duration_hours)
            ->addMinutes($request->duration_minutes);

        $shift->update([

            'code'=>$request->code,
            'name'=>$request->name,

            'start_time'=>$request->start_time,

            'duration_hours'=>$request->duration_hours,
            'duration_minutes'=>$request->duration_minutes,

            'end_time'=>$end->format('H:i:s'),

            'payable_hours'=>$request->payable_hours,
            'payable_minutes'=>$request->payable_minutes,

            'shift_type' => $request->shift_type ?? 'General',
            'break_time_minutes' => $request->break_time_minutes ?? 0,
            'grace_time_minutes' => $request->grace_time_minutes ?? 0,
            'min_working_hours' => $request->min_working_hours ?? 0,
            'max_working_hours' => $request->max_working_hours ?? 0,
            'color' => $request->color ?? '#556ee6',
            'status' => $request->status ?? 'active',

            'is_default'=>$request->has('is_default')
        ]);

        return back()->with(
            'success',
            'Shift Updated Successfully.'
        );
    }

    public function destroy($id)
    {
        Shift::findOrFail($id)->delete();

        return back()->with(
            'success',
            'Shift Deleted Successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Shift Policies
    |--------------------------------------------------------------------------
    */

    public function policyIndex(Request $request, $id = null)
    {
        $businessId = Auth::user()->active_business_id;

        $policies = ShiftPolicy::where('business_id', $businessId)
            ->with(['defaultShift', 'mondayShift', 'tuesdayShift', 'wednesdayShift', 'thursdayShift', 'fridayShift', 'saturdayShift', 'sundayShift'])
            ->latest()
            ->get();

        $shifts = Shift::where('business_id', $businessId)->get();

        $editPolicy = null;
        if ($id) {
            $editPolicy = ShiftPolicy::where('business_id', $businessId)
                ->findOrFail($id);
        }

        return view('admin.setup.attandance.shift-policy.index', compact('policies', 'shifts', 'editPolicy'));
    }

    public function policyStore(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        ShiftPolicy::create([
            'user_id' => Auth::id(),
            'business_id' => Auth::user()->active_business_id,
            'name' => $request->name,
            'description' => $request->description,
            'is_default' => $request->has('is_default'),
            'default_shift_id' => $request->default_shift_id,
            'monday_shift_id' => $request->monday_shift_id,
            'tuesday_shift_id' => $request->tuesday_shift_id,
            'wednesday_shift_id' => $request->wednesday_shift_id,
            'thursday_shift_id' => $request->thursday_shift_id,
            'friday_shift_id' => $request->friday_shift_id,
            'saturday_shift_id' => $request->saturday_shift_id,
            'sunday_shift_id' => $request->sunday_shift_id,
        ]);

        return redirect()->route('shift-policies.index')
            ->with('success', 'Shift Policy Created Successfully.');
    }

    public function policyUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $policy = ShiftPolicy::where('business_id', Auth::user()->active_business_id)
            ->findOrFail($id);

        $policy->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_default' => $request->has('is_default'),
            'default_shift_id' => $request->default_shift_id,
            'monday_shift_id' => $request->monday_shift_id,
            'tuesday_shift_id' => $request->tuesday_shift_id,
            'wednesday_shift_id' => $request->wednesday_shift_id,
            'thursday_shift_id' => $request->thursday_shift_id,
            'friday_shift_id' => $request->friday_shift_id,
            'saturday_shift_id' => $request->saturday_shift_id,
            'sunday_shift_id' => $request->sunday_shift_id,
        ]);

        return redirect()->route('shift-policies.index')
            ->with('success', 'Shift Policy Updated Successfully.');
    }

    public function policyDestroy($id)
    {
        $policy = ShiftPolicy::where('business_id', Auth::user()->active_business_id)
            ->findOrFail($id);

        $policy->delete();

        return redirect()->route('shift-policies.index')
            ->with('success', 'Shift Policy Deleted Successfully.');
    }
}
