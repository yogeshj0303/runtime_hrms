<?php

namespace App\Http\Controllers\Setup\Statutory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EsiSetting;
use App\Models\UserBusinessSession;
use App\Models\SalaryComponent;
use App\Models\Business;
use Illuminate\Support\Facades\Auth;

class EsiSettingController extends Controller
{
    public function index()
    {
        $activeBusiness = UserBusinessSession::where('user_id', Auth::id())
            ->where('status', 'ACTIVE')
            ->latest()
            ->first();

        $businessId = $activeBusiness ? $activeBusiness->business_id : null;

        $settings = collect();
        $components = collect();
        $selectedComponentIds = [];

        if ($businessId) {
            $settings = EsiSetting::where('business_id', $businessId)
                ->orderBy('effective_from', 'desc')
                ->get();

            $components = SalaryComponent::where('business_id', $businessId)->get();
            
            $business = Business::with('esiSettings')->find($businessId);
            if($business) {
                // Fetch the pivot IDs
                $selectedComponentIds = \DB::table('business_esi_components')
                    ->where('business_id', $businessId)
                    ->pluck('salary_component_id')
                    ->toArray();
            }
        }

        return view('admin.setup.statutory.esi-settings.index', compact('settings', 'components', 'selectedComponentIds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'effective_from' => 'required|date',
            'employee_contribution' => 'required|numeric|min:0',
            'employer_contribution' => 'required|numeric|min:0',
            'gross_wage_ceiling' => 'required|numeric|min:0',
        ]);

        $activeBusiness = UserBusinessSession::where('user_id', Auth::id())
            ->where('status', 'ACTIVE')
            ->latest()
            ->first();

        if (!$activeBusiness) {
            return back()->with('error', 'No active business found.');
        }

        $businessId = $activeBusiness->business_id;
        $isEnabled = $request->has('is_enabled');

        // Active Rule: Disable others if this one is enabled
        if ($isEnabled) {
            EsiSetting::where('business_id', $businessId)->update(['is_enabled' => false]);
        }

        $effectiveFrom = \Carbon\Carbon::parse($request->effective_from)->format('Y-m-d');

        EsiSetting::updateOrCreate(
            [
                'business_id' => $businessId,
                'effective_from' => $effectiveFrom,
            ],
            [
                'user_id' => Auth::id(),
                'employee_contribution' => $request->employee_contribution,
                'employer_contribution' => $request->employer_contribution,
                'gross_wage_ceiling' => $request->gross_wage_ceiling,
                'is_enabled' => $isEnabled,
            ]
        );

        return back()->with('success', 'ESI Setting saved successfully');
    }

    public function edit($id)
    {
        // For AJAX requests to get data for editing
        $setting = EsiSetting::findOrFail($id);
        return response()->json($setting);
    }

    public function update(Request $request, $id)
    {
        $setting = EsiSetting::findOrFail($id);
        
        $request->validate([
            'effective_from' => 'required|date',
            'employee_contribution' => 'required|numeric|min:0',
            'employer_contribution' => 'required|numeric|min:0',
            'gross_wage_ceiling' => 'required|numeric|min:0',
        ]);

        $isEnabled = $request->has('is_enabled');

        if ($isEnabled && !$setting->is_enabled) {
            EsiSetting::where('business_id', $setting->business_id)
                ->where('id', '!=', $setting->id)
                ->update(['is_enabled' => false]);
        }

        $setting->update([
            'effective_from' => \Carbon\Carbon::parse($request->effective_from)->format('Y-m-d'),
            'employee_contribution' => $request->employee_contribution,
            'employer_contribution' => $request->employer_contribution,
            'gross_wage_ceiling' => $request->gross_wage_ceiling,
            'is_enabled' => $isEnabled,
        ]);

        return back()->with('success', 'ESI Setting updated successfully');
    }

    public function destroy($id)
    {
        EsiSetting::findOrFail($id)->delete();
        return back()->with('success', 'ESI Setting deleted successfully');
    }

    public function toggleStatus(Request $request, $id)
    {
        $setting = EsiSetting::findOrFail($id);
        
        if ($request->status) {
            EsiSetting::where('business_id', $setting->business_id)
                ->where('id', '!=', $id)
                ->update(['is_enabled' => false]);
        }
        
        $setting->update(['is_enabled' => $request->status]);
        
        return response()->json(['success' => true]);
    }

    public function updateComponent(Request $request)
    {
        $activeBusiness = UserBusinessSession::where('user_id', Auth::id())
            ->where('status', 'ACTIVE')
            ->latest()
            ->first();

        if (!$activeBusiness) {
            return response()->json(['success' => false, 'message' => 'No active business.']);
        }

        $businessId = $activeBusiness->business_id;
        $componentId = $request->component_id;
        $isSelected = $request->is_selected == 'true' || $request->is_selected === true;

        if ($isSelected) {
            \DB::table('business_esi_components')->updateOrInsert(
                ['business_id' => $businessId, 'salary_component_id' => $componentId],
                ['created_at' => now(), 'updated_at' => now()]
            );
        } else {
            \DB::table('business_esi_components')
                ->where('business_id', $businessId)
                ->where('salary_component_id', $componentId)
                ->delete();
        }

        return response()->json(['success' => true]);
    }
}
