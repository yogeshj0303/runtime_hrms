<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Holiday;

class HolidayApiController extends Controller
{
    public function index($business_id)
    {
        $holidays = Holiday::with([
            'business',
            'user',
            'location'
        ])
        ->where('business_id', $business_id)
        ->orderBy('date')
        ->get();

        $mappedHolidays = $holidays->map(function($h) {
            return [
                'id' => $h->id,
                'holiday_name' => $h->holiday_name ?? $h->name ?? '',
                'date' => $h->date,
                'is_optional' => $h->is_optional ?? 0
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Holiday list fetched successfully',
            'data' => $mappedHolidays
        ]);
    }
}