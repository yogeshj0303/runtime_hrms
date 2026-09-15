<?php

namespace App\Http\Controllers\Setup\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Workflow;

class WorkflowController extends Controller
{
    public function index()
    {
        $workflows = Workflow::where(
            'business_id',
            Auth::user()->active_business_id
        )->latest()->get();

        return view(
            'admin.setup.workflows.index',
            compact('workflows')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'workflow_name' => 'required|max:255',
        ]);

        Workflow::create([

            'user_id' =>
                Auth::id(),

            'business_id' =>
                Auth::user()->active_business_id,

            'workflow_name' =>
                $request->workflow_name,

            'description' =>
                $request->description,

            'is_active' =>
                $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()
            ->route('workflows.index')
            ->with(
                'success',
                'Workflow Created Successfully.'
            );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'workflow_name' => 'required|max:255',
        ]);

        $workflow = Workflow::where(
            'business_id',
            Auth::user()->active_business_id
        )->findOrFail($id);

        $workflow->update([

            'workflow_name' =>
                $request->workflow_name,

            'description' =>
                $request->description,

            'is_active' =>
                $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()
            ->route('workflows.index')
            ->with(
                'success',
                'Workflow Updated Successfully.'
            );
    }

    public function destroy($id)
    {
        $workflow = Workflow::where(
            'business_id',
            Auth::user()->active_business_id
        )->findOrFail($id);

        $workflow->delete();

        return redirect()
            ->route('workflows.index')
            ->with(
                'success',
                'Workflow Deleted Successfully.'
            );
    }
}