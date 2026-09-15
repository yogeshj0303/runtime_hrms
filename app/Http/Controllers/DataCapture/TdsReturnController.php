<?php

namespace App\Http\Controllers\DataCapture;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TdsReturn;

class TdsReturnController extends Controller
{
    public function index(Request $request)
    {
        $financialYear = $request->input('financial_year', date('Y') . '-' . substr(date('Y') + 1, -2));
        
        $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
        $returns = [];

        if ($request->has('financial_year')) {
            foreach ($quarters as $quarter) {
                $return = TdsReturn::firstOrCreate(
                    [
                        'financial_year' => $financialYear,
                        'quarter' => $quarter
                    ]
                );

                $returns[] = $return;
            }
        }

        return view('admin.data_capture.tds_returns', compact('financialYear', 'returns'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:tds_returns,id',
            'receipt_number' => 'nullable|string'
        ]);

        try {
            $return = TdsReturn::findOrFail($request->id);
            $return->update([
                'receipt_number' => $request->receipt_number
            ]);

            return response()->json(['success' => true, 'message' => 'Saved successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error saving data: ' . $e->getMessage()], 500);
        }
    }
}
