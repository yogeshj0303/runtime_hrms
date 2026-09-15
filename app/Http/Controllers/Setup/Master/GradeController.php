<?php

namespace App\Http\Controllers\Setup\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Grade;

class GradeController extends Controller
{
    public function index()
    {
        $grades = Grade::where(
            'business_id',
            Auth::user()->active_business_id
        )->latest()->get();

        return view(
            'admin.setup.grades.index',
            compact('grades')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        Grade::create([

            'user_id' =>
                Auth::id(),

            'business_id' =>
                Auth::user()->active_business_id,

            'name' =>
                $request->name,
        ]);

        return redirect()
            ->route('grades.index')
            ->with(
                'success',
                'Grade Created Successfully.'
            );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $grade = Grade::where(
            'business_id',
            Auth::user()->active_business_id
        )->findOrFail($id);

        $grade->update([

            'name' =>
                $request->name,
        ]);

        return redirect()
            ->route('grades.index')
            ->with(
                'success',
                'Grade Updated Successfully.'
            );
    }

    public function destroy($id)
    {
        $grade = Grade::where(
            'business_id',
            Auth::user()->active_business_id
        )->findOrFail($id);

        $grade->delete();

        return redirect()
            ->route('grades.index')
            ->with(
                'success',
                'Grade Deleted Successfully.'
            );
    }
}