<?php

namespace App\Http\Controllers\Setup\Salary;

use App\Http\Controllers\Controller;
use App\Models\ClaimComponent;
use App\Models\Employee;
use App\Models\Grade;
use App\Models\SalaryClaim;
use App\Models\UserBusinessSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryClaimController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Get Active Business Helper
    |--------------------------------------------------------------------------
    */

    private function getActiveBusiness()
    {
        return UserBusinessSession::where('user_id', Auth::id())
            ->where('status', 'ACTIVE')
            ->latest()
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $activeBusiness = $this->getActiveBusiness();

        $components = ClaimComponent::where(
            'business_id',
            $activeBusiness->business_id
        )
        ->latest()
        ->get();

        $selectedComponent = $components->first();

        $claim = null;

        if ($selectedComponent) {
            $claim = SalaryClaim::where(
                'business_id',
                $activeBusiness->business_id
            )
            ->where(
                'claim_component_id',
                $selectedComponent->id
            )
            ->first();
        }

        /*
         * Grades — loaded using the same scope as GradeController
         * (Auth::user()->active_business_id) so this list always matches
         * what the user configured at Setup > Master > Grades.
         */
        $grades = Grade::where(
            'business_id',
            Auth::user()->active_business_id
        )
        ->latest()
        ->get();

        $employees = Employee::where(
            'business_id',
            $activeBusiness->business_id
        )
        ->get();

        return view(
            'admin.setup.salary-deduction.salary-claims.index',
            compact(
                'components',
                'selectedComponent',
                'claim',
                'grades',
                'employees'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Claim Component (AJAX)
    |--------------------------------------------------------------------------
    */

    public function storeComponent(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'short_name' => 'nullable|string|max:100',
        ]);

        $activeBusiness = $this->getActiveBusiness();

        $component = ClaimComponent::create([
            'business_id' => $activeBusiness->business_id,
            'auth_id'     => Auth::id(),
            'name'        => $request->name,
            'short_name'  => $request->short_name,
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Claim Component Added Successfully',
            'component' => $component,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Get Components (AJAX - for dropdown reload)
    |--------------------------------------------------------------------------
    */

    public function getComponents()
    {
        $activeBusiness = $this->getActiveBusiness();

        $components = ClaimComponent::where(
            'business_id',
            $activeBusiness->business_id
        )
        ->latest()
        ->get();

        return response()->json([
            'success'    => true,
            'components' => $components,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Store / Update Claim Settings (AJAX)
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'claim_component_id' => 'required|exists:claim_components,id',
            'claim_approver'     => 'nullable|integer|exists:employees,id',
            'request_limit'      => 'nullable|numeric|min:0',
            'monthly_limit'      => 'nullable|numeric|min:0',
            'employee_limit'     => 'nullable|numeric|min:0',
        ]);

        $activeBusiness = $this->getActiveBusiness();

        $allowedGrades = null;

        if (!$request->boolean('allow_all_grades')) {
            $allowedGrades = $request->input('allowed_grades', []);
        }

        $claim = SalaryClaim::updateOrCreate(
            [
                'business_id'        => $activeBusiness->business_id,
                'claim_component_id' => $request->claim_component_id,
            ],
            [
                'user_id'              => Auth::id(),
                'claim_approver'       => (is_numeric($request->claim_approver) && $request->claim_approver > 0)
                                            ? (int) $request->claim_approver
                                            : null,
                'enable_claim_request' => $request->boolean('enable_claim_request'),
                'request_limit'        => $request->request_limit ?? 0,
                'monthly_limit'        => $request->monthly_limit ?? 0,
                'employee_limit'       => $request->employee_limit ?? 0,
                'allow_all_grades'     => $request->boolean('allow_all_grades'),
                'allowed_grades'       => $allowedGrades,
                'status'               => 'active',
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Claim Settings Saved Successfully',
            'claim'   => $claim,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Edit — return claim data for selected component (AJAX)
    |--------------------------------------------------------------------------
    */

    public function edit($componentId)
    {
        $activeBusiness = $this->getActiveBusiness();

        $claim = SalaryClaim::where(
            'business_id',
            $activeBusiness->business_id
        )
        ->where(
            'claim_component_id',
            $componentId
        )
        ->first();

        return response()->json([
            'success' => true,
            'claim'   => $claim,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Update (AJAX)
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $request->validate([
            'claim_component_id' => 'required|exists:claim_components,id',
            'claim_approver'     => 'nullable|integer|exists:employees,id',
            'request_limit'      => 'nullable|numeric|min:0',
            'monthly_limit'      => 'nullable|numeric|min:0',
            'employee_limit'     => 'nullable|numeric|min:0',
        ]);

        $claim = SalaryClaim::findOrFail($id);

        $allowedGrades = null;

        if (!$request->boolean('allow_all_grades')) {
            $allowedGrades = $request->input('allowed_grades', []);
        }

        $claim->update([
            'claim_approver'       => (is_numeric($request->claim_approver) && $request->claim_approver > 0)
                                        ? (int) $request->claim_approver
                                        : null,
            'enable_claim_request' => $request->boolean('enable_claim_request'),
            'request_limit'        => $request->request_limit ?? 0,
            'monthly_limit'        => $request->monthly_limit ?? 0,
            'employee_limit'       => $request->employee_limit ?? 0,
            'allow_all_grades'     => $request->boolean('allow_all_grades'),
            'allowed_grades'       => $allowedGrades,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Claim Settings Updated Successfully',
            'claim'   => $claim,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Destroy (AJAX)
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        SalaryClaim::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Claim Deleted Successfully',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Change Status (AJAX)
    |--------------------------------------------------------------------------
    */

    public function changeStatus(Request $request, $id)
    {
        $claim = SalaryClaim::findOrFail($id);

        $claim->update([
            'status' => $claim->status === 'active' ? 'inactive' : 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status Updated Successfully',
            'status'  => $claim->status,
        ]);
    }
}
