<?php

namespace App\Http\Controllers\Setup\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Holiday;
use App\Models\Location;
use App\Models\AttendanceSetting;

class HolidayController extends Controller
{
    public function index(Request $request)
    {
        $businessId = Auth::user()->active_business_id;

        $locations = Location::where('business_id', $businessId)->get();
        $selectedYear = $request->input('year', date('Y'));
        $selectedLocation = $request->input('location_id', '');

        $query = Holiday::where('business_id', $businessId)
            ->whereYear('date', $selectedYear);

        if ($selectedLocation) {
            $query->where('location_id', $selectedLocation);
        }

        $holidays = $query->orderBy('date')->get();

        $settings = AttendanceSetting::firstOrCreate(
            ['business_id' => $businessId],
            ['user_id' => Auth::id(), 'holidays_payable' => true]
        );

        return view('admin.setup.attandance.holidays.index', compact('holidays', 'locations', 'selectedYear', 'selectedLocation', 'settings'));
    }

    public function store(Request $request)
    {
        $businessId = Auth::user()->active_business_id;

        $request->validate([
            'location_id' => 'nullable|exists:locations,id',
            'date' => 'required|date',
            'holiday_name' => 'required|string|max:255',
        ]);

        // Duplicate validation
        $exists = Holiday::where('business_id', $businessId)
            ->where('date', $request->date)
            ->where('location_id', $request->location_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['Duplicate holiday exists for this date and location.'])->withInput();
        }

        Holiday::create([
            'user_id' => Auth::id(),
            'business_id' => $businessId,
            'location_id' => $request->location_id,
            'holiday_name' => $request->holiday_name,
            'date' => $request->date,
            'day' => date('l', strtotime($request->date)),
        ]);

        return redirect()->back()->with('success', 'Holiday Added Successfully.');
    }

    public function edit($id)
    {
        $holiday = Holiday::where('business_id', Auth::user()->active_business_id)->findOrFail($id);
        return response()->json($holiday);
    }

    public function update(Request $request, $id)
    {
        $businessId = Auth::user()->active_business_id;
        $holiday = Holiday::where('business_id', $businessId)->findOrFail($id);

        $request->validate([
            'location_id' => 'nullable|exists:locations,id',
            'date' => 'required|date',
            'holiday_name' => 'required|string|max:255',
        ]);

        $exists = Holiday::where('business_id', $businessId)
            ->where('date', $request->date)
            ->where('location_id', $request->location_id)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['Duplicate holiday exists for this date and location.'])->withInput();
        }

        $holiday->update([
            'location_id' => $request->location_id,
            'holiday_name' => $request->holiday_name,
            'date' => $request->date,
            'day' => date('l', strtotime($request->date)),
        ]);

        return redirect()->back()->with('success', 'Holiday Updated Successfully.');
    }

    public function destroy($id)
    {
        $holiday = Holiday::where('business_id', Auth::user()->active_business_id)->findOrFail($id);
        $holiday->delete();

        return redirect()->back()->with('success', 'Holiday Deleted Successfully.');
    }

    public function copyHolidays(Request $request)
    {
        $businessId = Auth::user()->active_business_id;

        $request->validate([
            'from_location' => 'required',
            'to_location' => 'required',
            'from_year' => 'required|digits:4',
            'to_year' => 'required|digits:4',
        ]);

        $query = Holiday::where('business_id', $businessId)
            ->whereYear('date', $request->from_year);

        if ($request->from_location !== 'all') {
            $query->where('location_id', $request->from_location);
        } else {
            $query->whereNull('location_id'); // All locations usually means null location_id in the db setup
        }

        $fromHolidays = $query->get();

        if ($fromHolidays->isEmpty()) {
            return redirect()->back()->withErrors(['No holidays found to copy based on the selected criteria.']);
        }

        $count = 0;
        foreach ($fromHolidays as $holiday) {
            $newDate = date('Y-m-d', strtotime(str_replace($request->from_year, $request->to_year, $holiday->date)));
            $targetLocationId = $request->to_location === 'all' ? null : $request->to_location;

            $exists = Holiday::where('business_id', $businessId)
                ->where('date', $newDate)
                ->where('location_id', $targetLocationId)
                ->exists();

            if (!$exists) {
                Holiday::create([
                    'user_id' => Auth::id(),
                    'business_id' => $businessId,
                    'location_id' => $targetLocationId,
                    'holiday_name' => $holiday->holiday_name,
                    'date' => $newDate,
                    'day' => date('l', strtotime($newDate)),
                ]);
                $count++;
            }
        }

        return redirect()->back()->with('success', $count . ' Holidays successfully copied.');
    }

    public function updatePayableStatus(Request $request)
    {
        $businessId = Auth::user()->active_business_id;
        
        $settings = AttendanceSetting::firstOrCreate(
            ['business_id' => $businessId],
            ['user_id' => Auth::id()]
        );

        $settings->update([
            'holidays_payable' => $request->holidays_payable ? true : false
        ]);

        return response()->json(['success' => true, 'message' => 'Payable status updated.']);
    }
}
