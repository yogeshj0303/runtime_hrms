<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\AttendanceRelaxation;

class AttendanceRelaxationApiController extends Controller

{
    public function store(Request $request)
{
    $validator = Validator::make($request->all(),[

        'business_id'=>'required',

        'employee_id'=>'required|exists:users,id',

        'attendance_date'=>'required|date',

        'relaxation_minutes'=>'required|integer',

        'reason'=>'required'
    ]);

    if($validator->fails()){

        return response()->json([

            'status'=>false,

            'errors'=>$validator->errors()

        ],422);
    }

    $relaxation = AttendanceRelaxation::create([

        'business_id'=>$request->business_id,

        'employee_id'=>$request->employee_id,

        'shift_id'=>$request->shift_id,

        'attendance_date'=>$request->attendance_date,

        'relaxation_minutes'=>$request->relaxation_minutes,

        'reason'=>$request->reason
    ]);

    return response()->json([

        'status'=>true,

        'message'=>'Relaxation request submitted.',

        'data'=>$relaxation
    ]);
}

public function pending($employee_id)
{
    $data = AttendanceRelaxation::where(
            'employee_id',
            $employee_id
        )
        ->where(
            'status',
            'pending'
        )
        ->latest()
        ->get();

    return response()->json([

        'status'=>true,

        'data'=>$data

    ]);
}

public function approved($employee_id)
{
    $data = AttendanceRelaxation::where(
            'employee_id',
            $employee_id
        )
        ->where(
            'status',
            'approved'
        )
        ->latest()
        ->get();

    return response()->json([

        'status'=>true,

        'data'=>$data

    ]);
}
}
