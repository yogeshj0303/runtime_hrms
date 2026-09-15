<?php

namespace App\Http\Controllers\Setup\Statutory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PtaxSetting;
use App\Models\PtaxSlab;
use App\Models\UserBusinessSession;
use App\Models\SalaryComponent;
use App\Models\Business;
use Illuminate\Support\Facades\Auth;

class PtaxController extends Controller
{
    private function getBusinessId()
    {
        $activeBusiness = UserBusinessSession::where('user_id', Auth::id())
            ->where('status', 'ACTIVE')
            ->latest()
            ->first();

        return $activeBusiness ? $activeBusiness->business_id : null;
    }

    public function index()
    {
        $businessId = $this->getBusinessId();

        if (!$businessId) {
            return redirect()->back()->with('error', 'No active business found.');
        }

        $business = Business::with('ptaxSetting')->find($businessId);
        $setting = $business->ptaxSetting ?? new PtaxSetting();

        $components = SalaryComponent::where('business_id', $businessId)->get();
        $selectedComponentIds = $business->ptaxComponents()->pluck('salary_components.id')->toArray();

        return view('admin.setup.statutory.ptax-settings.index', compact('setting', 'components', 'selectedComponentIds'));
    }

    public function updateSettings(Request $request)
    {
        $businessId = $this->getBusinessId();
        if (!$businessId) {
            return response()->json(['success' => false, 'message' => 'No active business found.']);
        }

        $setting = PtaxSetting::updateOrCreate(
            ['business_id' => $businessId],
            [
                'user_id' => Auth::id(),
                'is_enabled' => $request->has('is_enabled') && $request->is_enabled == 'true',
                'calculation_basis' => $request->calculation_basis ?? 'gross_salary',
            ]
        );

        return response()->json(['success' => true, 'message' => 'Settings updated successfully.']);
    }

    public function updateComponent(Request $request)
    {
        $businessId = $this->getBusinessId();
        
        if (!$businessId) {
            return response()->json(['success' => false]);
        }

        $business = Business::find($businessId);
        $componentId = $request->component_id;

        if ($request->is_selected == 'true') {
            $business->ptaxComponents()->syncWithoutDetaching([$componentId]);
        } else {
            $business->ptaxComponents()->detach($componentId);
        }

        return response()->json(['success' => true]);
    }

    public function getSlabs(Request $request)
    {
        $businessId = $this->getBusinessId();
        if (!$businessId) {
            return response()->json(['success' => false]);
        }

        $slabs = PtaxSlab::where('business_id', $businessId)
            ->where('state', $request->state)
            ->orderBy('salary_from', 'asc')
            ->get();

        return response()->json(['success' => true, 'slabs' => $slabs]);
    }

    public function storeSlab(Request $request)
    {
        $request->validate([
            'state' => 'required|string',
            'effective_date' => 'required|date',
            'salary_from' => 'required|numeric|min:0',
            'salary_to' => 'nullable|numeric|gte:salary_from',
            'tax_amount' => 'required|numeric|min:0',
        ]);

        $businessId = $this->getBusinessId();
        if (!$businessId) {
            return redirect()->back()->with('error', 'No active business found.');
        }

        PtaxSlab::create([
            'business_id' => $businessId,
            'user_id' => Auth::id(),
            'state' => $request->state,
            'effective_date' => $request->effective_date,
            'salary_from' => $request->salary_from,
            'salary_to' => $request->salary_to,
            'tax_amount' => $request->tax_amount,
            'month' => $request->month ?? 'all',
            'gender' => $request->gender ?? 'all',
        ]);

        return redirect()->back()->with('success', 'PT slab added successfully.');
    }

    public function updateSlab(Request $request, $id)
    {
        $request->validate([
            'state' => 'required|string',
            'effective_date' => 'required|date',
            'salary_from' => 'required|numeric|min:0',
            'salary_to' => 'nullable|numeric|gte:salary_from',
            'tax_amount' => 'required|numeric|min:0',
        ]);

        $businessId = $this->getBusinessId();
        if (!$businessId) {
            return response()->json(['success' => false, 'message' => 'No active business found.']);
        }

        $slab = PtaxSlab::where('business_id', $businessId)->findOrFail($id);
        
        $slab->update([
            'state' => $request->state,
            'effective_date' => $request->effective_date,
            'salary_from' => $request->salary_from,
            'salary_to' => $request->salary_to,
            'tax_amount' => $request->tax_amount,
            'month' => $request->month ?? 'all',
            'gender' => $request->gender ?? 'all',
        ]);

        return redirect()->back()->with('success', 'PT slab updated successfully.');
    }

    public function destroySlab($id)
    {
        $businessId = $this->getBusinessId();
        $slab = PtaxSlab::where('business_id', $businessId)->findOrFail($id);
        $slab->delete();

        return redirect()->back()->with('success', 'PT slab deleted successfully.');
    }
}
