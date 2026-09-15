<?php

namespace App\Http\Controllers\Setup\Salary;

use App\Http\Controllers\Controller;
use App\Models\SalaryDeduction;
use App\Models\UserBusinessSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryDeductionController extends Controller
{
    public function index()
    {
        $deductions = SalaryDeduction::latest()->get();

        return view(
            'admin.setup.salary-deduction.deductions.index',
            compact('deductions')
        );
    }



    public function store(Request $request)
    {
        $request->validate([

            'name'=>'required',

            'short_name'=>'required',

            'deduction_type'=>'required'

        ]);


        $activeBusiness = UserBusinessSession::where(

            'user_id',

            Auth::id()

        )->where(

            'status',

            'ACTIVE'

        )->latest()->first();


        SalaryDeduction::create([

            'business_id'=>$activeBusiness->business_id,

            'auth_id'=>Auth::id(),

            'name'=>$request->name,

            'short_name'=>$request->short_name,

            'deduction_type'=>$request->deduction_type,

            'active'=>$request->boolean('active')

        ]);


        return back()->with(
            'success',
            'Deduction Added Successfully'
        );
    }



    public function update(Request $request,$id)
    {
        $deduction = SalaryDeduction::findOrFail($id);

        $deduction->update([

            'name'=>$request->name,

            'short_name'=>$request->short_name,

            'deduction_type'=>$request->deduction_type,

            'active'=>$request->boolean('active')

        ]);


        return back()->with(
            'success',
            'Updated Successfully'
        );
    }



    public function destroy($id)
    {
        SalaryDeduction::findOrFail($id)->delete();

        return back()->with(
            'success',
            'Deleted Successfully'
        );
    }
}