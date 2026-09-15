<?php

namespace App\Http\Controllers\Api\Statutory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\IncomeTaxSetting;

class IncomeTaxSettingApiController extends Controller
{
    private function getBusinessId()
    {
        return auth()->user()->active_business_id;
    }

    public function show()
    {
        $setting = IncomeTaxSetting::firstOrCreate(
            ['business_id' => $this->getBusinessId()],
            [
                'user_id' => auth()->id(),
                'enable_tds_deduction' => false,
                'declaration_window_open' => false,
            ]
        );

        return response()->json(['status' => true, 'message' => 'Settings retrieved successfully', 'data' => $setting]);
    }

    public function update(\App\Http\Requests\UpdateIncomeTaxSettingRequest $request)
    {
        $setting = IncomeTaxSetting::firstOrCreate(
            ['business_id' => $this->getBusinessId()],
            [
                'user_id' => auth()->id(),
                'enable_tds_deduction' => false,
                'declaration_window_open' => false,
            ]
        );

        $setting->update($request->validated());

        return response()->json(['status' => true, 'message' => 'Settings updated successfully', 'data' => $setting]);
    }
}
