<?php

namespace App\Http\Controllers\Api\Statutory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinancialYear;

class FinancialYearApiController extends Controller
{
    private function getBusinessId()
    {
        return auth()->user()->active_business_id;
    }

    public function index()
    {
        $years = FinancialYear::where('business_id', $this->getBusinessId())->get();
        return response()->json(['status' => true, 'message' => 'Financial years retrieved successfully', 'data' => $years]);
    }

    public function listActive()
    {
        $year = FinancialYear::where('business_id', $this->getBusinessId())->where('active', true)->first();
        return response()->json(['status' => true, 'message' => 'Active financial year retrieved successfully', 'data' => $year]);
    }

    public function store(\App\Http\Requests\StoreFinancialYearRequest $request)
    {
        if ($request->active) {
            FinancialYear::where('business_id', $this->getBusinessId())->update(['active' => false]);
        }
        
        $data = $request->validated();
        $data['business_id'] = $this->getBusinessId();
        $data['user_id'] = auth()->id();

        $year = FinancialYear::create($data);

        return response()->json(['status' => true, 'message' => 'Financial year created successfully', 'data' => $year], 201);
    }

    public function show($id)
    {
        $year = FinancialYear::where('business_id', $this->getBusinessId())->find($id);
        if (!$year) return response()->json(['status' => false, 'message' => 'Not found'], 404);
        
        return response()->json(['status' => true, 'message' => 'Financial year retrieved successfully', 'data' => $year]);
    }

    public function update(\App\Http\Requests\UpdateFinancialYearRequest $request, $id)
    {
        $year = FinancialYear::where('business_id', $this->getBusinessId())->find($id);
        if (!$year) return response()->json(['status' => false, 'message' => 'Not found'], 404);

        if ($request->active) {
            FinancialYear::where('business_id', $this->getBusinessId())->update(['active' => false]);
        }

        $year->update($request->validated());

        return response()->json(['status' => true, 'message' => 'Financial year updated successfully', 'data' => $year]);
    }

    public function destroy($id)
    {
        $year = FinancialYear::where('business_id', $this->getBusinessId())->find($id);
        if (!$year) return response()->json(['status' => false, 'message' => 'Not found'], 404);

        $year->delete();
        return response()->json(['status' => true, 'message' => 'Financial year deleted successfully']);
    }
}
