<?php

namespace App\Http\Controllers\Api\Statutory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Tds24QInfo;
use App\Http\Requests\StoreTds24QInfoRequest;

class Tds24QInfoApiController extends Controller
{
    private function getBusinessId()
    {
        return auth()->user()->active_business_id;
    }

    public function show()
    {
        $info = Tds24QInfo::where('business_id', $this->getBusinessId())->first();
        return response()->json([
            'status' => true,
            'message' => 'TDS 24Q Info retrieved successfully.',
            'data' => $info
        ]);
    }

    public function update(StoreTds24QInfoRequest $request)
    {
        $info = Tds24QInfo::updateOrCreate(
            ['business_id' => $this->getBusinessId()],
            array_merge($request->validated(), [
                'user_id' => auth()->id()
            ])
        );

        return response()->json([
            'status' => true,
            'message' => 'TDS 24Q Information saved successfully.',
            'data' => $info
        ]);
    }
}
