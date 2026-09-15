<?php

namespace App\Http\Controllers\Api\Statutory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SalaryTaxMapping;

class SalaryTaxMappingApiController extends Controller
{
    private function getBusinessId()
    {
        return auth()->user()->active_business_id;
    }

    public function index()
    {
        $mappings = SalaryTaxMapping::with('salaryComponent')
            ->where('business_id', $this->getBusinessId())
            ->get();
        return response()->json(['status' => true, 'message' => 'Mappings retrieved successfully', 'data' => $mappings]);
    }

    public function store(\App\Http\Requests\StoreSalaryTaxMappingRequest $request)
    {
        $mapping = SalaryTaxMapping::updateOrCreate(
            [
                'business_id' => $this->getBusinessId(),
                'salary_component_id' => $request->salary_component_id
            ],
            [
                'user_id' => auth()->id(),
                'basic' => $request->basic ?? false,
                'hra' => $request->hra ?? false,
                'profit' => $request->profit ?? false,
                'perk' => $request->perk ?? false,
                'entire_taxable' => $request->entire_taxable ?? false,
                'exempt' => $request->exempt ?? false,
                'new_exempt' => $request->new_exempt ?? false,
            ]
        );

        return response()->json(['status' => true, 'message' => 'Mapping saved successfully', 'data' => $mapping]);
    }

    public function destroy($id)
    {
        $mapping = SalaryTaxMapping::where('business_id', $this->getBusinessId())->find($id);
        if (!$mapping) return response()->json(['status' => false, 'message' => 'Not found'], 404);

        $mapping->delete();
        return response()->json(['status' => true, 'message' => 'Mapping deleted successfully']);
    }
}
