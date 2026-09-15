<?php

namespace App\Http\Controllers\Api\Statutory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Form16Info;
use App\Http\Requests\StoreForm16InfoRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Form16InfoApiController extends Controller
{
    private function getBusinessId()
    {
        return Auth::user()->business_id ?? Auth::user()->active_business_id;
    }

    public function show()
    {
        $info = Form16Info::where('business_id', $this->getBusinessId())->first();
        
        if ($info && $info->signature_image) {
            $info->signature_image_url = Storage::url($info->signature_image);
        }

        return response()->json([
            'status' => true,
            'message' => 'Form 16 Information retrieved successfully.',
            'data' => $info ?: (object)[]
        ]);
    }

    public function update(StoreForm16InfoRequest $request)
    {
        $data = $request->validated();
        $businessId = $this->getBusinessId();
        
        $info = Form16Info::where('business_id', $businessId)->first();

        if ($request->hasFile('signature_image')) {
            // Delete old image if it exists
            if ($info && $info->signature_image) {
                if (Storage::disk('public')->exists($info->signature_image)) {
                    Storage::disk('public')->delete($info->signature_image);
                }
            }
            
            // Store new image
            $path = $request->file('signature_image')->store('form16_signatures', 'public');
            $data['signature_image'] = $path;
        } else {
            // Remove from array so it doesn't get updated to null if not provided
            unset($data['signature_image']);
        }

        $info = Form16Info::updateOrCreate(
            ['business_id' => $businessId],
            array_merge($data, [
                'user_id' => Auth::id()
            ])
        );

        if ($info->signature_image) {
            $info->signature_image_url = Storage::url($info->signature_image);
        }

        return response()->json([
            'status' => true,
            'message' => 'Form 16 information saved successfully.',
            'data' => $info
        ]);
    }
}
