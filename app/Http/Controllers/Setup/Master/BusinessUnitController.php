<?php

namespace App\Http\Controllers\Setup\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BusinessUnit;

class BusinessUnitController extends Controller
{
    /**
     * Display listing
     */
    public function index()
    {
        $businessUnits = BusinessUnit::where(
                'business_id',
                Auth::user()->active_business_id
            )
            ->latest()
            ->get();

        return view(
            'admin.setup.Business_units.index',
            compact('businessUnits')
        );
    }

    /**
     * Add Page
     */
    public function create()
    {
        return view(
            'admin.setup.Business_units.create'
        );
    }

    /**
     * Store Data
     */
    public function store(Request $request)
    {
        $request->validate([
            'unit_name'     => 'required|string|max:255',
            'report_title'  => 'required|string|max:255',
        ]);

        BusinessUnit::create([

            'user_id' => Auth::id(),

            'business_id' =>
                Auth::user()->active_business_id,

            'unit_name' =>
                $request->unit_name,

            'report_title' =>
                $request->report_title,

            'sub_header_1' =>
                $request->sub_header_1,

            'sub_header_2' =>
                $request->sub_header_2,

            'footer_line_1' =>
                $request->footer_line_1,

            'footer_line_2' =>
                $request->footer_line_2,

            'is_default' =>
                $request->has('is_default') ? 1 : 0,
        ]);

        return redirect()
            ->route('business-units.index')
            ->with(
                'success',
                'Business Unit Created Successfully.'
            );
    }

    /**
     * Edit Page
     */
    public function edit($id)
    {
        $businessUnit = BusinessUnit::where(
                'business_id',
                Auth::user()->active_business_id
            )
            ->findOrFail($id);

        return view(
            'admin.setup.Business_units.edit',
            compact('businessUnit')
        );
    }

    /**
     * Update Data
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'unit_name'     => 'required|string|max:255',
            'report_title'  => 'required|string|max:255',
        ]);

        $businessUnit = BusinessUnit::where(
                'business_id',
                Auth::user()->active_business_id
            )
            ->findOrFail($id);

        $businessUnit->update([

            'unit_name' =>
                $request->unit_name,

            'report_title' =>
                $request->report_title,

            'sub_header_1' =>
                $request->sub_header_1,

            'sub_header_2' =>
                $request->sub_header_2,

            'footer_line_1' =>
                $request->footer_line_1,

            'footer_line_2' =>
                $request->footer_line_2,

            'is_default' =>
                $request->has('is_default') ? 1 : 0,
        ]);

        return redirect()
            ->route('business-units.index')
            ->with(
                'success',
                'Business Unit Updated Successfully.'
            );
    }

    /**
     * Delete Data
     */
    public function destroy($id)
    {
        $businessUnit = BusinessUnit::where(
                'business_id',
                Auth::user()->active_business_id
            )
            ->findOrFail($id);

        $businessUnit->delete();

        return redirect()
            ->route('business-units.index')
            ->with(
                'success',
                'Business Unit Deleted Successfully.'
            );
    }

    /**
     * Show Images Page
     */
    public function images($id)
    {
        $businessUnit = BusinessUnit::where(
                'business_id',
                Auth::user()->active_business_id
            )
            ->findOrFail($id);

        return view(
            'admin.setup.Business_units.images',
            compact('businessUnit')
        );
    }

    /**
     * Update Images
     */
    public function updateImages(Request $request, $id)
    {
        $request->validate([
            'header_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'footer_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $businessUnit = BusinessUnit::where(
                'business_id',
                Auth::user()->active_business_id
            )
            ->findOrFail($id);

        if ($request->hasFile('header_image')) {
            if ($businessUnit->header_image && \Storage::disk('public')->exists($businessUnit->header_image)) {
                \Storage::disk('public')->delete($businessUnit->header_image);
            }
            $businessUnit->header_image = $request->file('header_image')->store('business_units/headers', 'public');
        }

        if ($request->hasFile('footer_image')) {
            if ($businessUnit->footer_image && \Storage::disk('public')->exists($businessUnit->footer_image)) {
                \Storage::disk('public')->delete($businessUnit->footer_image);
            }
            $businessUnit->footer_image = $request->file('footer_image')->store('business_units/footers', 'public');
        }

        $businessUnit->save();

        return redirect()
            ->route('business-units.index')
            ->with(
                'success',
                'Business Unit Images Updated Successfully.'
            );
    }
}