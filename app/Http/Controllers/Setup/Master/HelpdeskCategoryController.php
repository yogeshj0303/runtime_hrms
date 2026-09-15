<?php

namespace App\Http\Controllers\Setup\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HelpdeskCategory;

class HelpdeskCategoryController extends Controller
{
    public function index()
    {
        $categories = HelpdeskCategory::where(
            'business_id',
            Auth::user()->active_business_id
        )->latest()->get();

        return view(
            'admin.setup.helpdesk_categories.index',
            compact('categories')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|max:255',
        ]);

        HelpdeskCategory::create([

            'user_id' =>
                Auth::id(),

            'business_id' =>
                Auth::user()->active_business_id,

            'category_name' =>
                $request->category_name,

            'primary_approver' =>
                $request->primary_approver,

            'backup_approver' =>
                $request->backup_approver,

            'is_active' =>
                $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()
            ->route('helpdesk-categories.index')
            ->with(
                'success',
                'Category Created Successfully.'
            );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required|max:255',
        ]);

        $category = HelpdeskCategory::where(
            'business_id',
            Auth::user()->active_business_id
        )->findOrFail($id);

        $category->update([

            'category_name' =>
                $request->category_name,

            'primary_approver' =>
                $request->primary_approver,

            'backup_approver' =>
                $request->backup_approver,

            'is_active' =>
                $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()
            ->route('helpdesk-categories.index')
            ->with(
                'success',
                'Category Updated Successfully.'
            );
    }

    public function destroy($id)
    {
        $category = HelpdeskCategory::where(
            'business_id',
            Auth::user()->active_business_id
        )->findOrFail($id);

        $category->delete();

        return redirect()
            ->route('helpdesk-categories.index')
            ->with(
                'success',
                'Category Deleted Successfully.'
            );
    }
}