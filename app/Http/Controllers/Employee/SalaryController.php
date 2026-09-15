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
            $salaryComponents = \App\Models\SalaryComponent::where('business_id', $businessId)->get();
            $salaryDeductions = \App\Models\SalaryDeduction::where('business_id', $businessId)->get();
        } else {
            $salaryComponents = collect();
            $salaryDeductions = collect();
        }

        return view('admin.employee.profile.salary', compact(
            'employee', 'statesData', 'lwfRules', 'lwfComponentIds', 'salaryStructures',
            'salaryComponents', 'salaryDeductions',
            'epfSetting', 'epfComponentIds', 'esiSetting', 'esiComponentIds',
            'ptaxSetting', 'ptaxSlabs', 'ptaxComponentIds'
        ));
    }

    public function store(Request $request, \App\Services\SalaryCalculationService $salaryCalcService)
    {
        $employee = Employee::findOrFail($request->id);

        if ($request->filled('effective_month') && $request->filled('effective_year')) {
            $effectiveFrom = $request->effective_year . '-' . str_pad($request->effective_month, 2, '0', STR_PAD_LEFT) . '-01';
        } elseif ($request->filled('effective_from')) {
            $effectiveFrom = \Carbon\Carbon::parse($request->effective_from)->startOfMonth()->format('Y-m-d');
        } else {
            $effectiveFrom = date('Y-m-01');
        }

        $latestRevision = $employee->salaryRevisions()->latest('effective_from')->first();
        $copyLatest = $request->has('copy_latest') || $request->input('copy_latest') == '1';

        $calculated = [];
        if ($request->filled('salary_structure_id')) {
            $calculated = $salaryCalcService->calculate(
                $employee->id,
                $request->salary_structure_id,
                $request->custom_components ?? [],
                $request->options ?? []
            );
        }

        // Direct inputs from editable form take precedence
        if ($request->filled('basic_salary')) $calculated['basic_salary'] = (float)$request->basic_salary;
        if ($request->filled('hra')) $calculated['hra'] = (float)$request->hra;
        if ($request->filled('gross_salary')) $calculated['gross_salary'] = (float)$request->gross_salary;
        if ($request->filled('net_salary')) $calculated['net_salary'] = (float)$request->net_salary;
        if ($request->filled('ctc')) $calculated['ctc'] = (float)$request->ctc;
        if ($request->filled('pf')) $calculated['pf'] = (float)$request->pf;
        if ($request->filled('esi')) $calculated['esi'] = (float)$request->esi;
        if ($request->filled('pt')) $calculated['pt'] = (float)$request->pt;
        if ($request->has('contributions')) {
            $calculated['contributions'] = array_merge($calculated['contributions'] ?? [], $request->contributions);
        }

        $newRevisionData = array_merge([
            'salary_structure_id' => $request->salary_structure_id ?? ($copyLatest && $latestRevision ? $latestRevision->salary_structure_id : null),
            'effective_from' => $effectiveFrom,
            'allowances' => $request->allowances ?? ($copyLatest && $latestRevision ? $latestRevision->allowances : []),
            'tds' => $request->tds ?? ($copyLatest && $latestRevision ? $latestRevision->tds : 0),
            'is_increment' => $request->has('is_increment'),
            'salary_options' => $request->options ?? ($copyLatest && $latestRevision ? $latestRevision->salary_options : []),
            'custom_components' => $request->custom_components ?? ($copyLatest && $latestRevision ? $latestRevision->custom_components : []),
            'basic_salary' => $copyLatest && $latestRevision && empty($calculated['basic_salary']) ? $latestRevision->basic_salary : ($calculated['basic_salary'] ?? 12000),
            'hra' => $copyLatest && $latestRevision && empty($calculated['hra']) ? $latestRevision->hra : ($calculated['hra'] ?? 0),
            'gross_salary' => $copyLatest && $latestRevision && empty($calculated['gross_salary']) ? $latestRevision->gross_salary : ($calculated['gross_salary'] ?? 12000),
            'net_salary' => $copyLatest && $latestRevision && empty($calculated['net_salary']) ? $latestRevision->net_salary : ($calculated['net_salary'] ?? 12000),
            'ctc' => $copyLatest && $latestRevision && empty($calculated['ctc']) ? $latestRevision->ctc : ($calculated['ctc'] ?? 12000),
            'pf' => $copyLatest && $latestRevision && empty($calculated['pf']) ? $latestRevision->pf : ($calculated['pf'] ?? 0),
            'esi' => $copyLatest && $latestRevision && empty($calculated['esi']) ? $latestRevision->esi : ($calculated['esi'] ?? 0),
            'pt' => $copyLatest && $latestRevision && empty($calculated['pt']) ? $latestRevision->pt : ($calculated['pt'] ?? 0),
            'contributions' => $copyLatest && $latestRevision && empty($calculated['contributions']) ? $latestRevision->contributions : ($calculated['contributions'] ?? []),
        ], $calculated);

        $newRev = $employee->salaryRevisions()->create($newRevisionData);

        return redirect()->route('employee.profile.salary', [
            'id' => $employee->id
        ])->with('success', 'Salary revision added successfully');
    }

    public function update(Request $request, $revision_id, \App\Services\SalaryCalculationService $salaryCalcService)
    {
        $employee = Employee::findOrFail($request->id);
        $revision = $employee->salaryRevisions()->findOrFail($revision_id);

        $request->validate([
            'effective_from' => 'required',
            'salary_structure_id' => 'nullable|exists:salary_structures,id',
        ]);

        $effectiveFrom = \Carbon\Carbon::parse($request->effective_from)->startOfMonth()->format('Y-m-d');

        $calculated = [];
        if ($request->filled('salary_structure_id')) {
            $calculated = $salaryCalcService->calculate(
                $employee->id,
                $request->salary_structure_id,
                $request->custom_components ?? [],
                $request->options ?? []
            );
        }

        // Direct inputs from editable form take precedence
        if ($request->filled('basic_salary')) $calculated['basic_salary'] = (float)$request->basic_salary;
        if ($request->filled('hra')) $calculated['hra'] = (float)$request->hra;
        if ($request->filled('gross_salary')) $calculated['gross_salary'] = (float)$request->gross_salary;
        if ($request->filled('net_salary')) $calculated['net_salary'] = (float)$request->net_salary;
        if ($request->filled('ctc')) $calculated['ctc'] = (float)$request->ctc;
        if ($request->filled('pf')) $calculated['pf'] = (float)$request->pf;
        if ($request->filled('esi')) $calculated['esi'] = (float)$request->esi;
        if ($request->filled('pt')) $calculated['pt'] = (float)$request->pt;
        if ($request->has('contributions')) {
            $calculated['contributions'] = array_merge($calculated['contributions'] ?? [], $request->contributions);
        }

        $revision->update(array_merge([
            'salary_structure_id' => $request->salary_structure_id,
            'effective_from' => $effectiveFrom,
            'allowances' => $request->allowances ?? [],
            'tds' => $request->tds ?? 0,
            'is_increment' => $request->has('is_increment'),
            'salary_options' => $request->options ?? [],
            'custom_components' => $request->custom_components ?? [],
        ], $calculated));

        return redirect()->route('employee.profile.salary', ['id' => $employee->id])->with('success', 'Salary revision updated successfully');
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

    public function exportExcel(Request $request, $id)
    {
        $employee = Employee::with(['business', 'salaryRevisions' => function($q) {
            $q->orderBy('effective_from', 'desc');
        }])->findOrFail($id);

        $business = $employee->business;
        $companyName = $business ? ($business->business_name ?? 'SOMYA AUTOCAR PRIVATE LIMITED') : 'SOMYA AUTOCAR PRIVATE LIMITED';
        $companyAddress = $business ? ($business->address ?? 'Babji Nagar, 8-13, Indore, A.B. Road, Madhya Pradesh, 452010') : 'Babji Nagar, 8-13, Indore, A.B. Road, Madhya Pradesh, 452010';
        
        $cityParts = [];
        if ($business && $business->city) $cityParts[] = strtolower($business->city);
        if ($business && $business->pincode) $cityParts[] = '- ' . $business->pincode;
        if ($business && $business->state) $cityParts[] = $business->state;
        $cityState = !empty($cityParts) ? implode(' ', $cityParts) : 'indore - 452010 Madhya Pradesh';

        $fullName = trim($employee->first_name . ' ' . ($employee->middle_name ? $employee->middle_name . ' ' : '') . $employee->last_name);
        $empCode = $employee->employee_code ?? $employee->id;
        $doj = $employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('d-M-Y') : 'N/A';
        $dept = is_object($employee->department) ? ($employee->department->name ?? 'N/A') : ($employee->department ?? 'Service');
        $desig = is_object($employee->designation) ? ($employee->designation->name ?? 'N/A') : ($employee->designation ?? 'CRE');

        $revisions = $employee->salaryRevisions;

        $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $fullName);
        $filename = "Salary_Revisions_{$empCode}_{$safeName}.xlsx";

        $excelData = \App\Services\SalaryRevisionExcelExporter::generate([
            'companyName' => $companyName,
            'companyAddress' => $companyAddress,
            'cityState' => $cityState,
            'fullName' => $fullName,
            'empCode' => $empCode,
            'doj' => $doj,
            'dept' => $dept,
            'desig' => $desig,
            'revisions' => $revisions,
        ]);

        return response($excelData, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Content-Length' => strlen($excelData),
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        ]);
    }
}
