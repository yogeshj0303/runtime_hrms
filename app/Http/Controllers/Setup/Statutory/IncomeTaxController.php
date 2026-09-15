<?php

namespace App\Http\Controllers\Setup\Statutory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FinancialYear;
use App\Models\IncomeTaxSetting;
use App\Models\SalaryComponent;
use App\Models\SalaryTaxMapping;
use App\Models\TaxSlab;
use Illuminate\Support\Facades\Auth;
use App\Models\UserBusinessSession;

class IncomeTaxController extends Controller
{
    private function getActiveBusiness()
    {
        return UserBusinessSession::where('user_id', Auth::id())
            ->where('status', 'ACTIVE')
            ->latest()
            ->first();
    }

    public function index()
    {
        $activeBusiness = $this->getActiveBusiness();
        if (!$activeBusiness) {
            return back()->with('error', 'No active business found.');
        }
        $businessId = $activeBusiness->business_id;

        $financialYears = FinancialYear::where('business_id', $businessId)->get();
        $settings = IncomeTaxSetting::firstOrCreate(
            ['business_id' => $businessId],
            [
                'user_id' => Auth::id(),
                'enable_tds_deduction' => false,
                'declaration_window_open' => false,
            ]
        );
        $salaryComponents = SalaryComponent::where('business_id', $businessId)->where('active', true)->get();
        $salaryMappings = SalaryTaxMapping::where('business_id', $businessId)->get()->keyBy('salary_component_id');
        
        $activeFinancialYear = $financialYears->where('active', true)->first();
        $taxSlabs = collect();
        if ($activeFinancialYear) {
            $taxSlabs = TaxSlab::where('business_id', $businessId)
                               ->where('financial_year_id', $activeFinancialYear->id)
                               ->orderBy('sort_order')
                               ->get();
        }

        return view('admin.setup.statutory.income-tax.index', compact(
            'financialYears',
            'settings',
            'salaryComponents',
            'salaryMappings',
            'taxSlabs',
            'activeFinancialYear'
        ));
    }
}
