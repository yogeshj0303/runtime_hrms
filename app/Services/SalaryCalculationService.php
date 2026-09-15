<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\SalaryStructure;
use App\Models\EpfSetting;
use App\Models\EsiSetting;
use App\Models\PtaxSetting;
use App\Models\LwfSetting;
use App\Models\PtaxSlab;
use Illuminate\Support\Facades\DB;

class SalaryCalculationService
{
    /**
     * Calculate all salary components and statutory deductions dynamically.
     *
     * @param int $employeeId
     * @param int $salaryStructureId
     * @param array $customComponents
     * @param array $options
     * @return array
     */
    public function calculate($employeeId, $salaryStructureId, $customComponents = [], $options = [])
    {
        $employee = Employee::findOrFail($employeeId);
        $businessId = $employee->business_id;

        $structure = SalaryStructure::with(['rules.component'])->findOrFail($salaryStructureId);
        
        $gross = 0;
        $basic = 0;
        $hra = 0;
        $epfWage = 0;
        $esiWage = 0;
        $ptaxWage = 0;

        $epfComponentIds = DB::table('business_epf_components')
            ->where('business_id', $businessId)
            ->pluck('salary_component_id')
            ->toArray();
            
        $esiComponentIds = DB::table('business_esi_components')
            ->where('business_id', $businessId)
            ->pluck('salary_component_id')
            ->toArray();
            
        $ptaxComponentIds = DB::table('business_ptax_components')
            ->where('business_id', $businessId)
            ->pluck('salary_component_id')
            ->toArray();

        // 1. Calculate Gross, Basic, HRA and Statutory Wages
        if (!empty($customComponents) && $structure->rules->count() > 0) {
            foreach ($customComponents as $compId => $val) {
                $val = (float) $val;
                $compIdInt = (int) $compId;
                
                $rule = $structure->rules->firstWhere('salary_component_id', $compIdInt);
                if ($rule && $rule->component) {
                    $name = strtolower($rule->component->name);
                    if (str_contains($name, 'basic')) $basic += $val;
                    if (str_contains($name, 'hra') || str_contains($name, 'house rent')) $hra += $val;
                    
                    if (!$rule->component->exclude_from_gross_salary) {
                        $gross += $val;
                    }
                }
                
                if (in_array($compIdInt, $epfComponentIds)) $epfWage += $val;
                if (in_array($compIdInt, $esiComponentIds)) $esiWage += $val;
                if (in_array($compIdInt, $ptaxComponentIds)) $ptaxWage += $val;
            }
        } else {
            // Fallback for older non-dynamic setup (if any)
            $epfWage = $basic > 0 ? $basic : $gross * 0.5;
            $esiWage = $gross;
            $ptaxWage = $gross;
        }

        // Overrides
        if (!empty($options['pf_on_gross'])) {
            $epfWage = $gross;
        }

        // 2. PF Calculation
        $pfDeduction = 0;
        $pfCont = 0;
        $pensionCont = 0;
        $edliCont = 0;
        
        $epfSetting = EpfSetting::where('business_id', $businessId)->latest()->first();
        if ($epfSetting && $epfSetting->is_enabled && empty($options['pf_do_not_deduct']) && $epfWage > 0) {
            $ceiling = (float) ($epfSetting->wage_ceiling ?? 15000);
            
            $empWageBase = $epfWage;
            if ($ceiling > 0 && empty($options['pf_above_ceiling_employee']) && $empWageBase > $ceiling) {
                $empWageBase = $ceiling;
            }
            
            $emprWageBase = $epfWage;
            if ($ceiling > 0 && empty($options['pf_above_ceiling_employer']) && $emprWageBase > $ceiling) {
                $emprWageBase = $ceiling;
            }
            
            // Pension always capped by law
            $pensionWageBase = $epfWage;
            if ($ceiling > 0 && $pensionWageBase > $ceiling) {
                $pensionWageBase = $ceiling;
            }
            
            $empRate = (float) ($epfSetting->employee_contribution_rate ?? 12);
            $emprRate = (float) ($epfSetting->employer_contribution_rate ?? 12);
            $penRate = (float) ($epfSetting->pension_contribution_rate ?? 8.33);
            $edliRate = (float) ($epfSetting->edli_contribution_rate ?? 0.5);
            
            $pfDeduction = ($empWageBase * $empRate) / 100;
            
            if (!empty($options['pf_min_deduction'])) {
                $minDed = (float) $options['pf_min_deduction'];
                if ($minDed > $pfDeduction) {
                    $pfDeduction = $minDed;
                }
            }
            
            if (!empty($options['pf_no_pension'])) {
                $pfCont = ($emprWageBase * $emprRate) / 100;
                $pensionCont = 0;
            } else {
                $pensionCont = ($pensionWageBase * $penRate) / 100;
                $totalEmprAmount = ($emprWageBase * $emprRate) / 100;
                $pfCont = $totalEmprAmount - $pensionCont;
                if ($pfCont < 0) $pfCont = 0;
            }
            
            $edliCont = ($pensionWageBase * $edliRate) / 100;
        }

        // 3. ESI Calculation
        $esiDeduction = 0;
        $esiCont = 0;
        $esiSetting = EsiSetting::where('business_id', $businessId)->latest()->first();
        if ($esiSetting && $esiSetting->is_enabled && empty($options['esi_do_not_deduct']) && $esiWage > 0) {
            $ceiling = (float) ($esiSetting->gross_wage_ceiling ?? 21000);
            $shouldDeduct = ($gross <= $ceiling) || !empty($options['esi_above_ceiling']);
            
            if ($shouldDeduct) {
                $empRate = (float) ($esiSetting->employee_contribution ?? 0.75);
                $emprRate = (float) ($esiSetting->employer_contribution ?? 3.25);
                
                $esiDeduction = ($esiWage * $empRate) / 100;
                $esiCont = ($esiWage * $emprRate) / 100;
            }
        }

        // 4. PTAX Calculation
        $ptDeduction = 0;
        $ptaxSetting = PtaxSetting::where('business_id', $businessId)->latest()->first();
        if ($ptaxSetting && $ptaxSetting->is_enabled && empty($options['pt_do_not_deduct']) && $ptaxWage > 0 && !empty($options['pt_state'])) {
            $applicableSlab = PtaxSlab::where('business_id', $businessId)
                ->where('state', $options['pt_state'])
                ->where('salary_from', '<=', $ptaxWage)
                ->where('salary_to', '>=', $ptaxWage)
                ->first();
                
            if ($applicableSlab) {
                $ptDeduction = (float) $applicableSlab->tax_amount;
            }
        }

        // 5. LWF Calculation
        $lwfDeductionVal = 0;
        $lwfContVal = 0;
        if (empty($options['lwf_do_not_deduct']) && !empty($options['lwf_state']) && $gross > 0) {
            $rule = LwfSetting::where('business_id', $businessId)
                ->where('is_enabled', true)
                ->where('state', $options['lwf_state'])
                ->first();
                
            if ($rule) {
                $lwfWage = $gross; 
                if ($rule->salary_limit && $rule->salary_limit > 0 && $lwfWage > $rule->salary_limit) {
                    $lwfWage = $rule->salary_limit;
                }
                
                if ($rule->employee_contribution_type === 'Fixed Amount') {
                    $lwfDeductionVal = (float) ($rule->employee_contribution_amount ?? 0);
                } else {
                    $lwfDeductionVal = ($lwfWage * ((float) $rule->employee_contribution_rate ?? 0)) / 100;
                }
                
                if ($rule->employer_contribution_type === 'Fixed Amount') {
                    $lwfContVal = (float) ($rule->employer_contribution_amount ?? 0);
                } else {
                    $lwfContVal = ($lwfWage * ((float) $rule->employer_contribution_rate ?? 0)) / 100;
                }
            }
        }

        // Rounding
        $pfDeduction = round($pfDeduction);
        $esiDeduction = ceil($esiDeduction);
        $ptDeduction = round($ptDeduction);
        $lwfDeductionVal = round($lwfDeductionVal);
        
        $pfCont = round($pfCont);
        $pensionCont = round($pensionCont);
        $edliCont = round($edliCont);
        $esiCont = ceil($esiCont);
        $lwfContVal = round($lwfContVal);

        $totalDeductions = $pfDeduction + $esiDeduction + $ptDeduction + $lwfDeductionVal;
        $net = $gross - $totalDeductions;
        $ctc = $gross + $pfCont + $pensionCont + $edliCont + $esiCont + $lwfContVal;

        return [
            'basic_salary' => $basic,
            'hra' => $hra,
            'gross_salary' => $gross,
            'pf' => $pfDeduction,
            'esi' => $esiDeduction,
            'pt' => $ptDeduction,
            'net_salary' => $net,
            'contributions' => [
                'pf' => $pfCont,
                'pension' => $pensionCont,
                'edli' => $edliCont,
                'esi' => $esiCont,
                'lwf_deduction' => $lwfDeductionVal,
                'lwf_contribution' => $lwfContVal,
            ],
            'ctc' => $ctc
        ];
    }
}
