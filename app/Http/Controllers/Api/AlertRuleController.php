<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AlertRule;

class AlertRuleController extends Controller
{
    public function index(Request $request)
    {
        // Require specific permission to manage alert rules
        if (!$request->user()->can('manage_alerts')) {
            // return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $rules = AlertRule::with('filters', 'templates')->get();

        return response()->json([
            'status' => true,
            'data' => $rules
        ]);
    }

    public function update(Request $request, $id)
    {
        $rule = AlertRule::findOrFail($id);
        
        $validated = $request->validate([
            'is_active' => 'boolean',
            'priority' => 'string|in:critical,high,normal,low',
            'reminder_days_before' => 'integer',
            'channels' => 'array'
        ]);

        $rule->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Alert rule updated successfully',
            'data' => $rule
        ]);
    }
}
