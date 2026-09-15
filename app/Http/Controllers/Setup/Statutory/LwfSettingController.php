<?php

namespace App\Http\Controllers\Setup\Statutory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LwfSetting;
use App\Models\UserBusinessSession;
use App\Models\SalaryComponent;
use App\Models\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LwfSettingController extends Controller
{
    private function getActiveBusiness()
    {
        return UserBusinessSession::where('user_id', Auth::id())
            ->where('status', 'ACTIVE')
            ->latest()
            ->first();
    }

    public function index()
    {
        $activeBusiness = $this->getActiveBusiness();
        $businessId = $activeBusiness ? $activeBusiness->business_id : null;

        $settings = collect();
        $components = collect();
        $selectedComponentIds = [];
        $lwfEnabled = false;

        if ($businessId) {
            $settings = LwfSetting::where('business_id', $businessId)
                ->orderBy('effective_from', 'desc')
                ->get();

            $components = SalaryComponent::where('business_id', $businessId)->get();

            $selectedComponentIds = DB::table('business_lwf_components')
                ->where('business_id', $businessId)
                ->pluck('salary_component_id')
                ->toArray();
                
            $activeSetting = LwfSetting::where('business_id', $businessId)
                ->where('is_enabled', true)
                ->first();
                
            $lwfEnabled = $activeSetting ? true : false;
        }
        
        $jsonPath = public_path('assets/admin/json/states-and-districts.json');
        $statesData = [];
        if (file_exists($jsonPath)) {
            $json = file_get_contents($jsonPath);
            $statesData = json_decode($json, true) ?? [];
        }

        return view('admin.setup.statutory.lwf-settings.index', compact('settings', 'components', 'selectedComponentIds', 'lwfEnabled', 'statesData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'state' => 'required|string|max:255',
            'effective_from' => 'required|date',
            'employee_contribution_type' => 'nullable|in:Fixed Amount,Percentage',
            'employee_contribution_amount' => 'nullable|numeric|min:0',
            'employee_contribution_rate' => 'nullable|numeric|min:0',
            'employer_contribution_type' => 'nullable|in:Fixed Amount,Percentage',
            'employer_contribution_amount' => 'nullable|numeric|min:0',
            'employer_contribution_rate' => 'nullable|numeric|min:0',
            'salary_limit' => 'nullable|numeric|min:0',
            'deduction_frequency' => 'nullable|string|max:100',
            'deduction_month' => 'nullable|string|max:100',
            'calculation_method' => 'nullable|string|max:100',
            'rounding_method' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $activeBusiness = $this->getActiveBusiness();

        if (!$activeBusiness) {
            return back()->with('error', 'No active business found.');
        }

        $businessId = $activeBusiness->business_id;
        $isEnabled = $request->has('is_enabled');

        DB::transaction(function () use ($request, $businessId, $isEnabled) {
            if ($isEnabled) {
                LwfSetting::where('business_id', $businessId)->update(['is_enabled' => false]);
            }

            $effectiveFrom = Carbon::parse($request->effective_from)->format('Y-m-d');

            LwfSetting::updateOrCreate(
                [
                    'business_id' => $businessId,
                    'effective_from' => $effectiveFrom,
                    'state' => $request->state,
                ],
                [
                    'user_id' => Auth::id(),
                    'employee_contribution_type' => $request->employee_contribution_type,
                    'employee_contribution_amount' => $request->employee_contribution_amount,
                    'employee_contribution_rate' => $request->employee_contribution_rate,
                    'employer_contribution_type' => $request->employer_contribution_type,
                    'employer_contribution_amount' => $request->employer_contribution_amount,
                    'employer_contribution_rate' => $request->employer_contribution_rate,
                    'salary_limit' => $request->salary_limit,
                    'deduction_frequency' => $request->deduction_frequency,
                    'deduction_month' => $request->deduction_month,
                    'calculation_method' => $request->calculation_method,
                    'rounding_method' => $request->rounding_method,
                    'notes' => $request->notes,
                    'is_enabled' => $isEnabled,
                ]
            );
        });

        return back()->with('success', 'LWF Setting saved successfully');
    }

    public function edit($id)
    {
        $activeBusiness = $this->getActiveBusiness();

        if (!$activeBusiness) {
            return response()->json(['error' => 'No active business found.'], 403);
        }

        $setting = LwfSetting::where('business_id', $activeBusiness->business_id)
            ->where('id', $id)
            ->firstOrFail();

        return response()->json($setting);
    }

    public function update(Request $request, $id)
    {
        $activeBusiness = $this->getActiveBusiness();

        if (!$activeBusiness) {
            return back()->with('error', 'No active business found.');
        }

        $businessId = $activeBusiness->business_id;

        $setting = LwfSetting::where('business_id', $businessId)
            ->where('id', $id)
            ->firstOrFail();
            
        $request->validate([
            'state' => 'required|string|max:255',
            'effective_from' => 'required|date',
            'employee_contribution_type' => 'nullable|in:Fixed Amount,Percentage',
            'employee_contribution_amount' => 'nullable|numeric|min:0',
            'employee_contribution_rate' => 'nullable|numeric|min:0',
            'employer_contribution_type' => 'nullable|in:Fixed Amount,Percentage',
            'employer_contribution_amount' => 'nullable|numeric|min:0',
            'employer_contribution_rate' => 'nullable|numeric|min:0',
            'salary_limit' => 'nullable|numeric|min:0',
            'deduction_frequency' => 'nullable|string|max:100',
            'deduction_month' => 'nullable|string|max:100',
            'calculation_method' => 'nullable|string|max:100',
            'rounding_method' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $isEnabled = $request->has('is_enabled');

        DB::transaction(function () use ($request, $setting, $isEnabled, $businessId) {
            if ($isEnabled && !$setting->is_enabled) {
                LwfSetting::where('business_id', $businessId)
                    ->where('id', '!=', $setting->id)
                    ->update(['is_enabled' => false]);
            }

            $setting->update([
                'state' => $request->state,
                'effective_from' => Carbon::parse($request->effective_from)->format('Y-m-d'),
                'employee_contribution_type' => $request->employee_contribution_type,
                'employee_contribution_amount' => $request->employee_contribution_amount,
                'employee_contribution_rate' => $request->employee_contribution_rate,
                'employer_contribution_type' => $request->employer_contribution_type,
                'employer_contribution_amount' => $request->employer_contribution_amount,
                'employer_contribution_rate' => $request->employer_contribution_rate,
                'salary_limit' => $request->salary_limit,
                'deduction_frequency' => $request->deduction_frequency,
                'deduction_month' => $request->deduction_month,
                'calculation_method' => $request->calculation_method,
                'rounding_method' => $request->rounding_method,
                'notes' => $request->notes,
                'is_enabled' => $isEnabled,
            ]);
        });

        return back()->with('success', 'LWF Setting updated successfully');
    }

    public function destroy($id)
    {
        $activeBusiness = $this->getActiveBusiness();

        if (!$activeBusiness) {
            return back()->with('error', 'No active business found.');
        }

        $setting = LwfSetting::where('business_id', $activeBusiness->business_id)
            ->where('id', $id)
            ->firstOrFail();

        $setting->delete();

        return back()->with('success', 'LWF Setting deleted successfully');
    }

    public function toggleGlobal(Request $request)
    {
        $activeBusiness = $this->getActiveBusiness();

        if (!$activeBusiness) {
            return response()->json(['success' => false, 'message' => 'No active business.']);
        }

        $businessId = $activeBusiness->business_id;

        $request->validate([
            'status' => 'required|boolean'
        ]);

        $status = $request->status;

        LwfSetting::updateOrCreate(
            [
                'business_id' => $businessId,
                'state' => 'GLOBAL',
                'effective_from' => '1970-01-01'
            ],
            [
                'user_id' => Auth::id(),
                'is_enabled' => $status
            ]
        );
        
        return response()->json(['success' => true, 'message' => 'LWF deduction status updated successfully.']);
    }

    public function updateComponent(Request $request)
    {
        $activeBusiness = $this->getActiveBusiness();

        if (!$activeBusiness) {
            return response()->json(['success' => false, 'message' => 'No active business.']);
        }

        $businessId = $activeBusiness->business_id;
        
        $request->validate([
            'component_id' => 'required|integer|exists:salary_components,id',
            'is_selected' => 'required'
        ]);

        $componentId = $request->component_id;
        $isSelected = filter_var($request->input('is_selected'), FILTER_VALIDATE_BOOLEAN);

        // Security Check: Component must belong to the active business
        $component = SalaryComponent::where('business_id', $businessId)
            ->where('id', $componentId)
            ->firstOrFail();

        if ($isSelected) {
            DB::table('business_lwf_components')->updateOrInsert(
                ['business_id' => $businessId, 'salary_component_id' => $componentId],
                ['created_at' => now(), 'updated_at' => now()]
            );
        } else {
            DB::table('business_lwf_components')
                ->where('business_id', $businessId)
                ->where('salary_component_id', $componentId)
                ->delete();
        }

        return response()->json(['success' => true, 'message' => 'LWF salary component applicability updated successfully.']);
    }
}
