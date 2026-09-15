<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        $employee = Employee::with('loginAccess', 'business')->findOrFail($request->id);
        return view('admin.employee.profile.login-access', compact('employee'));
    }

    public function update(Request $request)
    {
        $employee = Employee::findOrFail($request->id);

        $data = [
            'pin_never_expires' => $request->has('pin_never_expires') ? true : false,
            'multi_device' => $request->has('multi_device') ? true : false,
            'web_login' => $request->has('web_login') ? true : false,
            'make_wall_admin' => $request->has('make_wall_admin') ? true : false,
            'allow_wall_posting' => $request->has('allow_wall_posting') ? true : false,
            'allow_wall_comments' => $request->has('allow_wall_comments') ? true : false,
        ];

        $employee->loginAccess()->updateOrCreate(
            ['employee_id' => $employee->id],
            $data
        );

        return redirect()->back()->with('success', 'Login Access settings updated successfully');
    }
}
