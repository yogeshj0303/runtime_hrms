<?php

namespace App\Http\Controllers\Setup\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CostCenter;

class CostCenterController extends Controller
{
    /**
     * Listing Page
     */
    public function index()
    {
        $costCenters = CostCenter::where(
            'business_id',
            Auth::user()->active_business_id
        )
        ->latest()
        ->get();

        return view(
            'admin.setup.cost_centers.index',
            compact('costCenters')
        );
    }

    /**
     * Store Cost Center
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        CostCenter::create([
            'user_id'     => Auth::id(),
            'business_id' => Auth::user()->active_business_id,
            'name'        => $request->name,
            'is_default'  => $request->has('is_default') ? 1 : 0,
        ]);

        return redirect()
            ->route('cost-centers.index')
            ->with('success', 'Cost Center Created Successfully.');
    }

    /**
     * Update Cost Center
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $costCenter = CostCenter::where(
            'business_id',
            Auth::user()->active_business_id
        )->findOrFail($id);

        $costCenter->update([
            'name'       => $request->name,
            'is_default' => $request->has('is_default') ? 1 : 0,
        ]);

        return redirect()
            ->route('cost-centers.index')
            ->with('success', 'Cost Center Updated Successfully.');
    }

    /**
     * Delete Cost Center
     */
    public function destroy($id)
    {
        $costCenter = CostCenter::where(
            'business_id',
            Auth::user()->active_business_id
        )->findOrFail($id);

        $costCenter->delete();

        return redirect()
            ->route('cost-centers.index')
            ->with('success', 'Cost Center Deleted Successfully.');
    }
}