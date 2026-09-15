<?php

namespace App\Http\Controllers\Setup\Salary;

use App\Http\Controllers\Controller;
use App\Models\SalaryComponent;
use App\Models\UserBusinessSession ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryComponentController extends Controller
{
    public function index()
    {
        $components = SalaryComponent::latest()->get();

        return view(
            'admin.setup.salary-deduction.component.index',
            compact('components')
        );
    }


public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'short_name' => 'required',
        'unit_type' => 'required'
    ]);

    $activeBusiness = UserBusinessSession::where('user_id', Auth::id())
                        ->where('status', 'ACTIVE')
                        ->latest()
                        ->first();

    SalaryComponent::create([

        'business_id' => $activeBusiness->business_id,

        'auth_id' => Auth::id(),

        'name' => $request->name,

        'short_name' => $request->short_name,

        'unit_type' => $request->unit_type,

        'active' => $request->has('active'),

        'exclude_from_gross_salary' => $request->has('exclude_from_gross_salary'),

        'hide_in_ctc_reports' => $request->has('hide_in_ctc_reports'),

        'not_payable' => $request->has('not_payable')

    ]);

    return back()->with(
        'success',
        'Component Added Successfully'
    );
}


    public function edit($id)
    {
        return SalaryComponent::findOrFail($id);
    }


    public function update(Request $request, $id)
    {
        $component = SalaryComponent::findOrFail($id);

        $component->update([
            'name' => $request->name,
            'short_name' => $request->short_name,
            'unit_type' => $request->unit_type,
            'active' => $request->active ?? 0,
            'exclude_from_gross_salary' => $request->exclude_from_gross_salary ?? 0,
            'hide_in_ctc_reports' => $request->hide_in_ctc_reports ?? 0,
            'not_payable' => $request->not_payable ?? 0
        ]);

        return back()->with('success','Updated Successfully');
    }


    public function destroy($id)
    {
        SalaryComponent::findOrFail($id)->delete();

        return back()->with('success','Deleted Successfully');
    }
}