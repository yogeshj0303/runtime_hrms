<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        $employee = Employee::with(['salaryRevisions' => function($q) {
            $q->orderBy('effective_from', 'desc');
        }])->findOrFail($request->id);

        $jsonPath = public_path('assets/admin/json/states-and-districts.json');
        $statesData = [];
        if (file_exists($jsonPath)) {
            $json = file_get_contents($jsonPath);
            $statesData = json_decode($json, true) ?? [];
        }

        // Set the business ID for setup rules
        $businessId = $employee->business_id;
        
        $lwfRules = collect();
        $lwfComponentIds = [];
        $salaryStructures = collect();
        $epfSetting = null;
        $epfComponentIds = [];
        $esiSetting = null;
        $esiComponentIds = [];
        $ptaxSetting = null;
        $ptaxSlabs = collect();
        $ptaxComponentIds = [];
        
        if ($businessId) {
            $lwfRules = \App\Models\LwfSetting::where('business_id', $businessId)->where('is_enabled', true)->get();
            $lwfComponentIds = \Illuminate\Support\Facades\DB::table('business_lwf_components')
                ->where('business_id', $businessId)
                ->pluck('salary_component_id')
                ->toArray();
                
            $salaryStructures = \App\Models\SalaryStructure::where('business_id', $businessId)->get();
            
            // EPF
            $epfSetting = \App\Models\EpfSetting::where('business_id', $businessId)->latest()->first();
            $epfComponentIds = \Illuminate\Support\Facades\DB::table('business_epf_components')
                ->where('business_id', $businessId)
                ->pluck('salary_component_id')
                ->toArray();
                
            // ESI
            $esiSetting = \App\Models\EsiSetting::where('business_id', $businessId)->latest()->first();
            $esiComponentIds = \Illuminate\Support\Facades\DB::table('business_esi_components')
                ->where('business_id', $businessId)
                ->pluck('salary_component_id')
                ->toArray();
                
            // PTAX
            $ptaxSetting = \App\Models\PtaxSetting::where('business_id', $businessId)->latest()->first();
            $ptaxSlabs = \App\Models\PtaxSlab::where('business_id', $businessId)->get();
            $ptaxComponentIds = \Illuminate\Support\Facades\DB::table('business_ptax_components')
                ->where('business_id', $businessId)
                ->pluck('salary_component_id')
                ->toArray();
        }

        return view('admin.employee.profile.salary', compact(
            'employee', 'statesData', 'lwfRules', 'lwfComponentIds', 'salaryStructures',
            'epfSetting', 'epfComponentIds', 'esiSetting', 'esiComponentIds',
            'ptaxSetting', 'ptaxSlabs', 'ptaxComponentIds'
        ));
    }

    public function store(Request $request, \App\Services\SalaryCalculationService $salaryCalcService)
    {
        $employee = Employee::findOrFail($request->id);

        $request->validate([
            'effective_from' => 'required|date_format:Y-m',
            'salary_structure_id' => 'required|exists:salary_structures,id',
        ]);

        $effectiveFrom = \Carbon\Carbon::parse($request->effective_from . '-01')->format('Y-m-d');

        $calculated = $salaryCalcService->calculate(
            $employee->id,
            $request->salary_structure_id,
            $request->custom_components ?? [],
            $request->options ?? []
        );

        $employee->salaryRevisions()->create(array_merge([
            'salary_structure_id' => $request->salary_structure_id,
            'effective_from' => $effectiveFrom,
            'allowances' => $request->allowances ?? [],
            'tds' => $request->tds ?? 0,
            'is_increment' => $request->has('is_increment'),
            'salary_options' => $request->options ?? [],
            'custom_components' => $request->custom_components ?? [],
        ], $calculated));

        return redirect()->back()->with('success', 'Salary revision added successfully');
    }

    public function update(Request $request, $revision_id, \App\Services\SalaryCalculationService $salaryCalcService)
    {
        $employee = Employee::findOrFail($request->id);
        $revision = $employee->salaryRevisions()->findOrFail($revision_id);

        $request->validate([
            'effective_from' => 'required|date_format:Y-m',
            'salary_structure_id' => 'required|exists:salary_structures,id',
        ]);

        $effectiveFrom = \Carbon\Carbon::parse($request->effective_from . '-01')->format('Y-m-d');

        $calculated = $salaryCalcService->calculate(
            $employee->id,
            $request->salary_structure_id,
            $request->custom_components ?? [],
            $request->options ?? []
        );

        $revision->update(array_merge([
            'salary_structure_id' => $request->salary_structure_id,
            'effective_from' => $effectiveFrom,
            'allowances' => $request->allowances ?? [],
            'tds' => $request->tds ?? 0,
            'is_increment' => $request->has('is_increment'),
            'salary_options' => $request->options ?? [],
            'custom_components' => $request->custom_components ?? [],
        ], $calculated));

        return redirect()->back()->with('success', 'Salary revision updated successfully');
    }

    public function destroy(Request $request, $revision_id)
    {
        $employee = Employee::findOrFail($request->id);
        $revision = $employee->salaryRevisions()->findOrFail($revision_id);
        $revision->delete();

        return redirect()->back()->with('success', 'Salary revision deleted successfully');
    }

    public function getStructureDetails($structure_id)
    {
        $structure = \App\Models\SalaryStructure::with(['rules.component'])->findOrFail($structure_id);
        
        // Return structure, rules, and components
        return response()->json([
            'status' => 'success',
            'structure' => $structure
        ]);
    }
}
