<?php

namespace App\Http\Controllers\Setup\Salary;

use App\Http\Controllers\Controller;
use App\Models\SalaryStructure;
use App\Models\SalaryStructureRule;
use App\Models\SalaryComponent;
use App\Models\UserBusinessSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryStructureController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Structure List
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $structures = SalaryStructure::latest()->get();

        return view(
            'admin.setup.salary-deduction.structures.index',
            compact('structures')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Structure
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'structure_name' => 'required'
        ]);

        $activeBusiness = UserBusinessSession::where(
            'user_id',
            Auth::id()
        )
        ->where(
            'status',
            'ACTIVE'
        )
        ->latest()
        ->first();

        SalaryStructure::create([

            'business_id' => $activeBusiness->business_id,

            'auth_id' => Auth::id(),

            'structure_name' => $request->structure_name

        ]);

        return back()->with(
            'success',
            'Structure Added Successfully'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Structure
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $structure = SalaryStructure::findOrFail($id);

        $components = SalaryComponent::where(
            'business_id',
            $structure->business_id
        )->get();

        $rules = SalaryStructureRule::where(
            'salary_structure_id',
            $id
        )
        ->orderBy('order_no')
        ->get();

        return view(
            'admin.setup.salary-deduction.structures.edit',
            compact(
                'structure',
                'components',
                'rules'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Structure Name
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $request->validate([
            'structure_name' => 'required'
        ]);

        $structure = SalaryStructure::findOrFail($id);

        $structure->update([

            'structure_name' => $request->structure_name

        ]);

        return back()->with(
            'success',
            'Updated Successfully'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Structure
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        SalaryStructure::findOrFail($id)->delete();

        return back()->with(
            'success',
            'Deleted Successfully'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Allocation Rule
    |--------------------------------------------------------------------------
    */

    public function storeRule(Request $request, $id)
    {
        $structure = SalaryStructure::findOrFail($id);

        SalaryStructureRule::create([

            'salary_structure_id' => $structure->id,

            'business_id' => $structure->business_id,

            'auth_id' => Auth::id(),

            'salary_component_id' => $request->salary_component_id,

            'order_no' => $request->order_no,

            'condition_component' => $request->condition_component,

            'condition_operator' => $request->condition_operator,

            'condition_value' => $request->condition_value,

            'calculate_percentage' => $request->calculate_percentage,

            'base_component' => $request->base_component,

            'minimum_amount' => $request->minimum_amount,

            'maximum_amount' => $request->maximum_amount,

            'do_not_exceed_gross_salary' =>
                $request->boolean(
                    'do_not_exceed_gross_salary'
                )

        ]);

        return back()->with(
            'success',
            'Allocation Rule Added Successfully'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Rule
    |--------------------------------------------------------------------------
    */

    public function deleteRule($id)
    {
        SalaryStructureRule::findOrFail($id)->delete();

        return back()->with(
            'success',
            'Rule Deleted Successfully'
        );
    }
}
