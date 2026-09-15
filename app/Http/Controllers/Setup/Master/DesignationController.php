<?php

namespace App\Http\Controllers\Setup\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Designation;

class DesignationController extends Controller
{
    public function index()
    {
        $designations = Designation::where(
            'business_id',
            Auth::user()->active_business_id
        )->latest()->get();

        return view(
            'admin.setup.designations.index',
            compact('designations')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        Designation::create([

            'user_id' =>
                Auth::id(),

            'business_id' =>
                Auth::user()->active_business_id,

            'name' =>
                $request->name,

            'is_default' =>
                $request->has('is_default') ? 1 : 0,
        ]);

        return redirect()
            ->route('designations.index')
            ->with(
                'success',
                'Designation Created Successfully.'
            );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $designation = Designation::where(
            'business_id',
            Auth::user()->active_business_id
        )->findOrFail($id);

        $designation->update([

            'name' =>
                $request->name,

            'is_default' =>
                $request->has('is_default') ? 1 : 0,
        ]);

        return redirect()
            ->route('designations.index')
            ->with(
                'success',
                'Designation Updated Successfully.'
            );
    }

    public function destroy($id)
    {
        $designation = Designation::where(
            'business_id',
            Auth::user()->active_business_id
        )->findOrFail($id);

        $designation->delete();

        return redirect()
            ->route('designations.index')
            ->with(
                'success',
                'Designation Deleted Successfully.'
            );
    }
}