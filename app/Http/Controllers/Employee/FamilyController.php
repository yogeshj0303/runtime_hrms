<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

class FamilyController extends Controller
{
    public function index(Request $request)
    {
        $employee = Employee::with('familyMembers')->findOrFail($request->id);
        return view('admin.employee.profile.family', compact('employee'));
    }

    public function store(Request $request)
    {
        $employee = Employee::findOrFail($request->id);

        $request->validate([
            'relation' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'dob' => 'nullable|date',
            'notes' => 'nullable|string',
            'is_dependent' => 'nullable|boolean'
        ]);

        $data = $request->only([
            'relation', 'name', 'phone', 'email', 'dob', 'notes'
        ]);
        $data['is_dependent'] = $request->has('is_dependent') ? true : false;

        $employee->familyMembers()->create($data);

        return redirect()->back()->with('success', 'Family member added successfully');
    }

    public function destroy(Request $request, $id, $member_id)
    {
        $employee = Employee::findOrFail($id);
        $member = $employee->familyMembers()->findOrFail($member_id);
        $member->delete();

        return redirect()->back()->with('success', 'Family member deleted successfully');
    }
}
