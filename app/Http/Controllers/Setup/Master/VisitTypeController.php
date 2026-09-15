<?php

namespace App\Http\Controllers\Setup\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VisitType;

class VisitTypeController extends Controller
{
    public function index()
    {
        $visitTypes = VisitType::where(
            'business_id',
            Auth::user()->active_business_id
        )->latest()->get();

        return view(
            'admin.setup.visit_types.index',
            compact('visitTypes')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        VisitType::create([

            'user_id' =>
                Auth::id(),

            'business_id' =>
                Auth::user()->active_business_id,

            'name' =>
                $request->name,
        ]);

        return redirect()
            ->route('visit-types.index')
            ->with(
                'success',
                'Visit Type Created Successfully.'
            );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $visitType = VisitType::where(
            'business_id',
            Auth::user()->active_business_id
        )->findOrFail($id);

        $visitType->update([
            'name' => $request->name,
        ]);

        return redirect()
            ->route('visit-types.index')
            ->with(
                'success',
                'Visit Type Updated Successfully.'
            );
    }

    public function destroy($id)
    {
        $visitType = VisitType::where(
            'business_id',
            Auth::user()->active_business_id
        )->findOrFail($id);

        $visitType->delete();

        return redirect()
            ->route('visit-types.index')
            ->with(
                'success',
                'Visit Type Deleted Successfully.'
            );
    }
}