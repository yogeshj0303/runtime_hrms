<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alert;
use App\Models\Employee;
use App\Models\AlertRule;
use Illuminate\Support\Facades\Auth;

class AlertBroadcastController extends Controller
{
    public function create()
    {
        return view('admin.hr.alerts.broadcast');
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $businessId = Auth::user()->active_business_id;
        
        // Use an existing generic rule or create a fallback
        $rule = AlertRule::where('name', 'HR Custom Broadcast')->first();
        if (!$rule) {
            $rule = AlertRule::create([
                'name' => 'HR Custom Broadcast',
                'category' => 'Employee',
                'description' => 'Custom broadcast from HR',
                'is_active' => true,
            ]);
        }

        $employees = Employee::where('business_id', $businessId)->where('status', 'active')->get();
        $count = 0;

        foreach ($employees as $emp) {
            if ($emp->user_id) {
                Alert::create([
                    'alert_rule_id' => $rule->id,
                    'user_id' => $emp->user_id,
                    'employee_id' => $emp->id,
                    'message' => $request->message,
                    'status' => 'pending'
                ]);
                $count++;
            }
        }

        return redirect()->back()->with('success', "Alert broadcasted successfully to {$count} employees.");
    }
}
