<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alert;
use Carbon\Carbon;

class AlertController extends Controller
{
    public function dashboard(Request $request)
    {
        $userId = $request->user()->id;

        $totalAlerts = Alert::where('user_id', $userId)->count();
        $pendingAlerts = Alert::where('user_id', $userId)->where('status', 'pending')->count();
        $resolvedAlerts = Alert::where('user_id', $userId)->where('status', 'resolved')->count();
        
        $todayAlerts = Alert::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->count();

        return response()->json([
            'status' => true,
            'data' => [
                'total_alerts' => $totalAlerts,
                'pending_alerts' => $pendingAlerts,
                'resolved_alerts' => $resolvedAlerts,
                'today_alerts' => $todayAlerts,
            ]
        ]);
    }

    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $status = $request->query('status');
        
        $query = Alert::with(['rule', 'employee'])->where('user_id', $userId);

        if ($status) {
            $query->where('status', $status);
        }

        $alerts = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'status' => true,
            'data' => $alerts
        ]);
    }

    public function markAsRead(Request $request, $id)
    {
        $alert = Alert::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        
        if ($alert->status === 'pending') {
            $alert->update([
                'status' => 'read',
                'read_at' => now()
            ]);
        }

        return response()->json(['status' => true, 'message' => 'Alert marked as read']);
    }

    public function resolve(Request $request, $id)
    {
        $alert = Alert::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        
        $alert->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolved_by' => $request->user()->id
        ]);

        return response()->json(['status' => true, 'message' => 'Alert resolved successfully']);
    }

    public function broadcast(Request $request)
    {
        if (!$request->user()->can('manage_alerts')) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'message' => 'required|string',
            'priority' => 'required|in:critical,high,normal,low',
            'target_type' => 'required|in:all,department,employee',
            'target_ids' => 'nullable|array' // required if not 'all'
        ]);

        $rule = \App\Models\AlertRule::where('name', 'HR Custom Broadcast')->first();
        if (!$rule) {
            return response()->json(['status' => false, 'message' => 'Broadcast rule not found'], 500);
        }

        $employees = \App\Models\Employee::where('status', 'active');

        if ($validated['target_type'] === 'department') {
            $employees->whereIn('department_id', $validated['target_ids']);
        } elseif ($validated['target_type'] === 'employee') {
            $employees->whereIn('id', $validated['target_ids']);
        }

        $targets = $employees->get();
        $count = 0;

        foreach ($targets as $emp) {
            if ($emp->user_id) {
                Alert::create([
                    'alert_rule_id' => $rule->id,
                    'user_id' => $emp->user_id,
                    'employee_id' => $emp->id,
                    'message' => $validated['message'],
                    'status' => 'pending'
                ]);
                $count++;
            }
        }

        return response()->json([
            'status' => true,
            'message' => "Broadcast alert sent to $count employees."
        ]);
    }
}
