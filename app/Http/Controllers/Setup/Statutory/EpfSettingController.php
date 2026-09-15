<?php

namespace App\Http\Controllers\Setup\Statutory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EpfSetting;
use App\Models\UserBusinessSession;
use App\Models\SalaryComponent;
use App\Models\Business;
use Illuminate\Support\Facades\Auth;

class EpfSettingController extends Controller
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
            $settings = EpfSetting::where('business_id', $businessId)
                ->orderBy('effective_from', 'desc')
                ->get();

            $components = SalaryComponent::where('business_id', $businessId)->get();
            
            $business = Business::with('epfSettings')->find($businessId);
            if($business) {
                // Fetch the pivot IDs
                $selectedComponentIds = \DB::table('business_epf_components')
                    ->where('business_id', $businessId)
                    ->pluck('salary_component_id')
                    ->toArray();
            }
        }

        return view('admin.setup.statutory.epf-settings.index', compact('settings', 'components', 'selectedComponentIds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'effective_from' => 'required|date',
            'employee_contribution_rate' => 'required|numeric|min:0',
            'employer_contribution_rate' => 'required|numeric|min:0',
            'pension_contribution_rate' => 'required|numeric|min:0',
            'edli_contribution_rate' => 'required|numeric|min:0',
            'admin_charges_rate' => 'required|numeric|min:0',
            'wage_ceiling' => 'required|numeric|min:0',
            'senior_citizen_age' => 'nullable|integer|min:0',
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
            EpfSetting::where('business_id', $businessId)->update(['is_enabled' => false]);
        }

        $effectiveFrom = \Carbon\Carbon::parse($request->effective_from)->format('Y-m-d');

        EpfSetting::updateOrCreate(
            [
                'business_id' => $businessId,
                'effective_from' => $effectiveFrom,
            ],
            [
                'user_id' => Auth::id(),
                'employee_contribution_rate' => $request->employee_contribution_rate,
                'employer_contribution_rate' => $request->employer_contribution_rate,
                'pension_contribution_rate' => $request->pension_contribution_rate,
                'edli_contribution_rate' => $request->edli_contribution_rate,
                'admin_charges_rate' => $request->admin_charges_rate,
                'wage_ceiling' => $request->wage_ceiling,
                'senior_citizen_age' => $request->senior_citizen_age,
                'senior_employee_contribution_rate' => $request->senior_employee_contribution_rate,
                'senior_employer_contribution_rate' => $request->senior_employer_contribution_rate,
                'senior_pension_contribution_rate' => $request->senior_pension_contribution_rate,
                'calculation_method' => $request->calculation_method,
                'rounding_method' => $request->rounding_method,
                'notes' => $request->notes,
                'is_enabled' => $isEnabled,
            ]
        );

        return back()->with('success', 'EPF Setting saved successfully');
    }

    public function edit($id)
    {
        $setting = EpfSetting::findOrFail($id);
        return response()->json($setting);
    }

    public function update(Request $request, $id)
    {
        $setting = EpfSetting::findOrFail($id);
        
        $request->validate([
            'effective_from' => 'required|date',
            'employee_contribution_rate' => 'required|numeric|min:0',
            'employer_contribution_rate' => 'required|numeric|min:0',
            'pension_contribution_rate' => 'required|numeric|min:0',
            'edli_contribution_rate' => 'required|numeric|min:0',
            'admin_charges_rate' => 'required|numeric|min:0',
            'wage_ceiling' => 'required|numeric|min:0',
            'senior_citizen_age' => 'nullable|integer|min:0',
        ]);

        $isEnabled = $request->has('is_enabled');

        if ($isEnabled && !$setting->is_enabled) {
            EpfSetting::where('business_id', $setting->business_id)
                ->where('id', '!=', $setting->id)
                ->update(['is_enabled' => false]);
        }

        $setting->update([
            'effective_from' => \Carbon\Carbon::parse($request->effective_from)->format('Y-m-d'),
            'employee_contribution_rate' => $request->employee_contribution_rate,
            'employer_contribution_rate' => $request->employer_contribution_rate,
            'pension_contribution_rate' => $request->pension_contribution_rate,
            'edli_contribution_rate' => $request->edli_contribution_rate,
            'admin_charges_rate' => $request->admin_charges_rate,
            'wage_ceiling' => $request->wage_ceiling,
            'senior_citizen_age' => $request->senior_citizen_age,
            'senior_employee_contribution_rate' => $request->senior_employee_contribution_rate,
            'senior_employer_contribution_rate' => $request->senior_employer_contribution_rate,
            'senior_pension_contribution_rate' => $request->senior_pension_contribution_rate,
            'calculation_method' => $request->calculation_method,
            'rounding_method' => $request->rounding_method,
            'notes' => $request->notes,
            'is_enabled' => $isEnabled,
        ]);

        return back()->with('success', 'EPF Setting updated successfully');
    }

    public function destroy($id)
    {
        EpfSetting::findOrFail($id)->delete();
        return back()->with('success', 'EPF Setting deleted successfully');
    }

    public function toggleStatus(Request $request, $id)
    {
        $setting = EpfSetting::findOrFail($id);
        
        if ($request->status) {
            EpfSetting::where('business_id', $setting->business_id)
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
            \DB::table('business_epf_components')->updateOrInsert(
                ['business_id' => $businessId, 'salary_component_id' => $componentId],
                ['created_at' => now(), 'updated_at' => now()]
            );
        } else {
            \DB::table('business_epf_components')
                ->where('business_id', $businessId)
                ->where('salary_component_id', $componentId)
                ->delete();
        }

        return response()->json(['success' => true]);
    }
}
