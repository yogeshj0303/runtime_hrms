<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $employee = Employee::with('assets')->findOrFail($request->id);
        return view('admin.employee.profile.assets', compact('employee'));
    }

    public function store(Request $request)
    {
        $employee = Employee::findOrFail($request->id);

        $request->validate([
            'asset_name' => 'required|string|max:255',
            'asset_type' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'estimated_value' => 'nullable|numeric',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'notify_on_expiry' => 'nullable|boolean'
        ]);

        $data = $request->only([
            'asset_name', 'asset_type', 'serial_number', 'estimated_value', 'issue_date', 'expiry_date'
        ]);
        $data['notify_on_expiry'] = $request->has('notify_on_expiry') ? true : false;

        $employee->assets()->create($data);

        return redirect()->back()->with('success', 'Asset assigned successfully');
    }

    public function destroy(Request $request, $id, $asset_id)
    {
        $employee = Employee::findOrFail($id);
        $asset = $employee->assets()->findOrFail($asset_id);
        $asset->delete();

        return redirect()->back()->with('success', 'Asset deleted successfully');
    }
}
