<?php

namespace App\Http\Controllers\DataCapture;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TdsChallan;

class TdsChallanController extends Controller
{
    public function index(Request $request)
    {
        $financialYear = $request->input('financial_year', date('Y') . '-' . substr(date('Y') + 1, -2));
        
        $months = [
            'APR' => '04', 'MAY' => '05', 'JUN' => '06', 
            'JUL' => '07', 'AUG' => '08', 'SEP' => '09', 
            'OCT' => '10', 'NOV' => '11', 'DEC' => '12', 
            'JAN' => '01', 'FEB' => '02', 'MAR' => '03'
        ];

        $challans = [];
        $startYear = explode('-', $financialYear)[0];
        $endYear = $startYear + 1;

        if ($request->has('financial_year')) {
            foreach ($months as $monName => $monNum) {
                $year = in_array($monName, ['JAN', 'FEB', 'MAR']) ? $endYear : $startYear;
                $formattedMonth = $monName . '-' . $year;

                $challan = TdsChallan::firstOrCreate(
                    [
                        'financial_year' => $financialYear,
                        'month' => $formattedMonth
                    ]
                );

                $challans[] = $challan;
            }
        }

        return view('admin.data_capture.tds_challans', compact('financialYear', 'challans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:tds_challans,id',
            'bsr_code' => 'nullable|string',
            'deposit_date' => 'nullable|date',
            'challan_serial' => 'nullable|string'
        ]);

        try {
            $challan = TdsChallan::findOrFail($request->id);
            $challan->update([
                'bsr_code' => $request->bsr_code,
                'deposit_date' => $request->deposit_date,
                'challan_serial' => $request->challan_serial
            ]);

            return response()->json(['success' => true, 'message' => 'Saved successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error saving data: ' . $e->getMessage()], 500);
        }
    }
}
