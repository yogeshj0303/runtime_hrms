<?php

namespace App\Http\Controllers\Setup\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ExitReason;

class ExitReasonController extends Controller
{
    public function index()
    {
        $exitReasons = ExitReason::where(
            'business_id',
            Auth::user()->active_business_id
        )->latest()->get();

        return view(
            'admin.setup.exit_reasons.index',
            compact('exitReasons')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'reason_name' => 'required|max:255',
        ]);

        ExitReason::create([

            'user_id' =>
                Auth::id(),

            'business_id' =>
                Auth::user()->active_business_id,

            'reason_name' =>
                $request->reason_name,

            'esi_mapping' =>
                $request->esi_mapping,
        ]);

        return redirect()
            ->route('exit-reasons.index')
            ->with(
                'success',
                'Exit Reason Created Successfully.'
            );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'reason_name' => 'required|max:255',
        ]);

        $exitReason = ExitReason::where(
            'business_id',
            Auth::user()->active_business_id
        )->findOrFail($id);

        $exitReason->update([

            'reason_name' =>
                $request->reason_name,

            'esi_mapping' =>
                $request->esi_mapping,
        ]);

        return redirect()
            ->route('exit-reasons.index')
            ->with(
                'success',
                'Exit Reason Updated Successfully.'
            );
    }

    public function destroy($id)
    {
        $exitReason = ExitReason::where(
            'business_id',
            Auth::user()->active_business_id
        )->findOrFail($id);

        $exitReason->delete();

        return redirect()
            ->route('exit-reasons.index')
            ->with(
                'success',
                'Exit Reason Deleted Successfully.'
            );
    }
}