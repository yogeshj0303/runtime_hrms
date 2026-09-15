<?php

namespace App\Http\Controllers\Setup\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CompOffRule;

class CompOffRuleController extends Controller
{
    /**
     * Display the Comp Off Rules settings page.
     * Only loads the record belonging to the active business.
     */
    public function index()
    {
        $rule = CompOffRule::where('business_id', Auth::user()->active_business_id)->first();

        return view('admin.setup.attandance.comp-off-rules.index', compact('rule'));
    }

    /**
     * Save (create or update) Comp Off Rules for the active business.
     * user_id and business_id are auto-stored — never exposed in UI.
     */
    public function store(Request $request)
    {
        $request->validate([
            'wo_half_day_min_hours'   => 'required|integer|min:0|max:23',
            'wo_half_day_min_minutes' => 'required|integer|in:0,15,30,45',
            'wo_full_day_min_hours'   => 'required|integer|min:0|max:23',
            'wo_full_day_min_minutes' => 'required|integer|in:0,15,30,45',
            'wo_grant_comp_off'       => 'required|in:same_day,next_day,any_day',
            'ho_half_day_min_hours'   => 'required|integer|min:0|max:23',
            'ho_half_day_min_minutes' => 'required|integer|in:0,15,30,45',
            'ho_full_day_min_hours'   => 'required|integer|min:0|max:23',
            'ho_full_day_min_minutes' => 'required|integer|in:0,15,30,45',
            'ho_grant_comp_off'       => 'required|in:same_day,next_day,any_day',
            'lapse_after_days'        => 'required|integer|min:1|max:365',
        ]);

        CompOffRule::updateOrCreate(
            [
                'business_id' => Auth::user()->active_business_id,
            ],
            [
                'user_id' => Auth::id(),

                // Weekly Off
                'wo_auto_grant_comp_off' => $request->has('wo_auto_grant_comp_off'),
                'wo_half_day_min_hours'  => $request->wo_half_day_min_hours,
                'wo_half_day_min_minutes'=> $request->wo_half_day_min_minutes,
                'wo_full_day_min_hours'  => $request->wo_full_day_min_hours,
                'wo_full_day_min_minutes'=> $request->wo_full_day_min_minutes,
                'wo_grant_comp_off'      => $request->wo_grant_comp_off,
                'wo_add_to_extra_days'   => $request->has('wo_add_to_extra_days'),

                // Holiday
                'ho_auto_grant_comp_off' => $request->has('ho_auto_grant_comp_off'),
                'ho_half_day_min_hours'  => $request->ho_half_day_min_hours,
                'ho_half_day_min_minutes'=> $request->ho_half_day_min_minutes,
                'ho_full_day_min_hours'  => $request->ho_full_day_min_hours,
                'ho_full_day_min_minutes'=> $request->ho_full_day_min_minutes,
                'ho_grant_comp_off'      => $request->ho_grant_comp_off,
                'ho_add_to_extra_days'   => $request->has('ho_add_to_extra_days'),

                // Lapse
                'lapse_after_days'   => $request->lapse_after_days,
                'lapse_at_month_end' => $request->has('lapse_at_month_end'),
            ]
        );

        return redirect()->route('comp-off-rules.index')
            ->with('success', 'Comp Off Rules Saved Successfully.');
    }
}
