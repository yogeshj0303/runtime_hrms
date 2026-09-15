<?php

namespace App\Http\Controllers\Setup\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::where(
            'business_id',
            Auth::user()->active_business_id
        )->latest()->get();

        return view(
            'admin.setup.departments.index',
            compact('departments')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        Department::create([

            'user_id' => Auth::id(),

            'business_id' =>
                Auth::user()->active_business_id,

            'name' =>
                $request->name,

            'head' =>
                $request->head,

            'deputy_head' =>
                $request->deputy_head,

            'is_default' =>
                $request->has('is_default') ? 1 : 0,
        ]);

        return redirect()
            ->route('departments.index')
            ->with(
                'success',
                'Department Created Successfully.'
            );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $department = Department::where(
            'business_id',
            Auth::user()->active_business_id
        )->findOrFail($id);

        $department->update([

            'name' =>
                $request->name,

            'head' =>
                $request->head,

            'deputy_head' =>
                $request->deputy_head,

            'is_default' =>
                $request->has('is_default') ? 1 : 0,
        ]);

        return redirect()
            ->route('departments.index')
            ->with(
                'success',
                'Department Updated Successfully.'
            );
    }

    public function destroy($id)
    {
        $department = Department::where(
            'business_id',
            Auth::user()->active_business_id
        )->findOrFail($id);

        $department->delete();

        return redirect()
            ->route('departments.index')
            ->with(
                'success',
                'Department Deleted Successfully.'
            );
    }
}