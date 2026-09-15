<?php

namespace App\Http\Controllers\Setup\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StrikeRule;

class StrikeRuleController extends Controller
{
    /**
     * Display all strike rules for the active business.
     */
    public function index()
    {
        $strikeRules = StrikeRule::where('business_id', Auth::user()->active_business_id)
            ->orderBy('rule_type')
            ->orderBy('from_occurrences')
            ->get();

        $letterTemplates = \App\Models\LetterTemplate::where('type', 'Warning')->get();

        return view('admin.setup.attandance.strike-rules.index', compact('strikeRules', 'letterTemplates'));
    }

    /**
     * Store a new strike rule — auto-binds user_id and business_id.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rule_type'         => 'required|in:Early Coming,Late Coming,Early Going,Late Going,Late Lunch',
            'from_occurrences'  => 'required|integer|min:1',
            'to_occurrences'    => 'required|integer|min:0',
            'strike_color'      => 'required|in:Yellow,Orange,Red,Blue,Green',
            'deduction_type'    => 'required|in:None,Fixed,Per Day,Per Hour,Half Day,Full Day',
            'deduction_value'   => 'nullable|numeric|min:0',
            'warning_letter'    => 'nullable|string|max:255',
        ]);

        StrikeRule::create([
            'user_id'          => Auth::id(),
            'business_id'      => Auth::user()->active_business_id,
            'rule_type'        => $request->rule_type,
            'from_occurrences' => $request->from_occurrences,
            'to_occurrences'   => $request->to_occurrences,
            'strike_color'     => $request->strike_color,
            'deduction_type'   => $request->deduction_type,
            'deduction_value'  => $request->deduction_type !== 'None' ? $request->deduction_value : null,
            'warning_letter'   => $request->warning_letter,
            'is_active'        => $request->has('is_active'),
        ]);

        return redirect()->route('strike-rules.index')
            ->with('success', 'Strike Rule Created Successfully.');
    }

    /**
     * Return a single rule as JSON for the AJAX edit modal.
     * Scoped to active business — prevents cross-business access.
     */
    public function edit($id)
    {
        $strikeRule = StrikeRule::where('business_id', Auth::user()->active_business_id)
            ->findOrFail($id);

        return response()->json($strikeRule);
    }

    /**
     * Update a strike rule — scoped to active business.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'rule_type'         => 'required|in:Early Coming,Late Coming,Early Going,Late Going,Late Lunch',
            'from_occurrences'  => 'required|integer|min:1',
            'to_occurrences'    => 'required|integer|min:0',
            'strike_color'      => 'required|in:Yellow,Orange,Red,Blue,Green',
            'deduction_type'    => 'required|in:None,Fixed,Per Day,Per Hour,Half Day,Full Day',
            'deduction_value'   => 'nullable|numeric|min:0',
            'warning_letter'    => 'nullable|string|max:255',
        ]);

        $strikeRule = StrikeRule::where('business_id', Auth::user()->active_business_id)
            ->findOrFail($id);

        $strikeRule->update([
            'rule_type'        => $request->rule_type,
            'from_occurrences' => $request->from_occurrences,
            'to_occurrences'   => $request->to_occurrences,
            'strike_color'     => $request->strike_color,
            'deduction_type'   => $request->deduction_type,
            'deduction_value'  => $request->deduction_type !== 'None' ? $request->deduction_value : null,
            'warning_letter'   => $request->warning_letter,
            'is_active'        => $request->has('is_active'),
        ]);

        return redirect()->route('strike-rules.index')
            ->with('success', 'Strike Rule Updated Successfully.');
    }

    /**
     * Delete a strike rule — scoped to active business.
     */
    public function destroy($id)
    {
        $strikeRule = StrikeRule::where('business_id', Auth::user()->active_business_id)
            ->findOrFail($id);

        $strikeRule->delete();

        return redirect()->route('strike-rules.index')
            ->with('success', 'Strike Rule Deleted Successfully.');
    }
}
