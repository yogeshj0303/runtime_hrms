<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $employee = Employee::with('addresses')->findOrFail($request->id);
        
        $statesJson = file_get_contents(public_path('assets/admin/json/states-and-districts.json'));
        $statesData = json_decode($statesJson, true);
        $states = array_column($statesData['states'] ?? [], 'state');
        
        return view('admin.employee.profile.address', compact('employee', 'states'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required',
            'address1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
        ]);
        
        \App\Models\EmployeeAddress::create($request->all());
        
        return redirect()->back()->with('success', 'Address added successfully');
    }
    
    public function update(Request $request, $id)
    {
        $address = \App\Models\EmployeeAddress::findOrFail($id);
        
        $request->validate([
            'type' => 'required',
            'address1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
        ]);
        
        $address->update($request->all());
        
        return redirect()->back()->with('success', 'Address updated successfully');
    }
    
    public function destroy($id)
    {
        $address = \App\Models\EmployeeAddress::findOrFail($id);
        $address->delete();
        
        return redirect()->back()->with('success', 'Address deleted successfully');
    }
}
