<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShiftChangeRequestController extends Controller
{
   public function approve($id)
{
    DB::beginTransaction();

    try {

        $request = ShiftChangeRequest::findOrFail($id);

        $request->update([
            'status'=>'approved',
            'approved_by'=>auth()->id(),
            'approved_at'=>now()
        ]);

        EmployeeShiftHistory::create([
            'business_id'=>$request->business_id,
            'employee_id'=>$request->employee_id,
            'shift_id'=>$request->requested_shift_id,
            'effective_from'=>$request->effective_from,
            'is_active'=>1,
            'assigned_by'=>auth()->id(),
            'remarks'=>'Shift changed through request approval'
        ]);

        DB::commit();

        return back()
            ->with('success','Shift request approved.');

    } catch (\Exception $e){

        DB::rollBack();

        return back()
            ->with('error',$e->getMessage());
    }
}
}
