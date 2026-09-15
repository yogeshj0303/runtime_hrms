<?php

namespace App\Http\Controllers\Api\Statutory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TaxSlab;

class TaxSlabApiController extends Controller
{
    private function getBusinessId()
    {
        return auth()->user()->active_business_id;
    }

    public function index(Request $request)
    {
        $query = TaxSlab::where('business_id', $this->getBusinessId());

        if ($request->has('financial_year_id')) {
            $query->where('financial_year_id', $request->financial_year_id);
        }
        if ($request->has('scheme')) {
            $query->where('scheme', $request->scheme);
        }
        if ($request->has('age_category')) {
            $query->where('age_category', $request->age_category);
        }

        $slabs = $query->orderBy('sort_order')->get();
        return response()->json(['status' => true, 'message' => 'Tax slabs retrieved successfully', 'data' => $slabs]);
    }

    public function store(\App\Http\Requests\StoreTaxSlabRequest $request)
    {
        // Validation for overlapping ranges can be complex, doing basic validation
        $exists = TaxSlab::where('business_id', $this->getBusinessId())
            ->where('financial_year_id', $request->financial_year_id)
            ->where('scheme', $request->scheme)
            ->where('age_category', $request->age_category)
            ->where(function ($q) use ($request) {
                $q->whereBetween('income_from', [$request->income_from, $request->income_to ?? 999999999])
                  ->orWhereBetween('income_to', [$request->income_from, $request->income_to ?? 999999999]);
            })->exists();

        if ($exists) {
            return response()->json(['status' => false, 'message' => 'Income range overlaps with an existing slab'], 422);
        }

        $data = $request->validated();
        $data['business_id'] = $this->getBusinessId();
        $data['user_id'] = auth()->id();

        $slab = TaxSlab::create($data);

        return response()->json(['status' => true, 'message' => 'Tax slab created successfully', 'data' => $slab], 201);
    }

    public function show($id)
    {
        $slab = TaxSlab::where('business_id', $this->getBusinessId())->find($id);
        if (!$slab) return response()->json(['status' => false, 'message' => 'Not found'], 404);
        
        return response()->json(['status' => true, 'message' => 'Tax slab retrieved successfully', 'data' => $slab]);
    }

    public function update(\App\Http\Requests\UpdateTaxSlabRequest $request, $id)
    {
        $slab = TaxSlab::where('business_id', $this->getBusinessId())->find($id);
        if (!$slab) return response()->json(['status' => false, 'message' => 'Not found'], 404);

        $slab->update($request->validated());

        return response()->json(['status' => true, 'message' => 'Tax slab updated successfully', 'data' => $slab]);
    }

    public function destroy($id)
    {
        $slab = TaxSlab::where('business_id', $this->getBusinessId())->find($id);
        if (!$slab) return response()->json(['status' => false, 'message' => 'Not found'], 404);

        $slab->delete();
        return response()->json(['status' => true, 'message' => 'Tax slab deleted successfully']);
    }
}
