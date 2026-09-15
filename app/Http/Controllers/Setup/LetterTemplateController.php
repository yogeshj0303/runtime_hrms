<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\LetterTemplate;
use Illuminate\Http\Request;

class LetterTemplateController extends Controller
{
    public function index()
    {
        $templates = LetterTemplate::where('business_id', auth()->user()->active_business_id)->get();
        return view('admin.setup.letter_templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.setup.letter_templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'content' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $validated['business_id'] = auth()->user()->active_business_id;
        $validated['user_id'] = auth()->id();

        LetterTemplate::create($validated);
        return redirect()->route('setup.letter-templates.index')->with('success', 'Letter Template created successfully.');
    }

    public function edit($id)
    {
        $letterTemplate = LetterTemplate::where('business_id', auth()->user()->active_business_id)->findOrFail($id);
        return view('admin.setup.letter_templates.edit', compact('letterTemplate'));
    }

    public function update(Request $request, $id)
    {
        $letterTemplate = LetterTemplate::where('business_id', auth()->user()->active_business_id)->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'content' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $letterTemplate->update($validated);

        return redirect()->route('setup.letter-templates.index')->with('success', 'Letter Template updated successfully.');
    }

    public function destroy($id)
    {
        $letterTemplate = LetterTemplate::where('business_id', auth()->user()->active_business_id)->findOrFail($id);
        $letterTemplate->delete();
        return redirect()->route('setup.letter-templates.index')->with('success', 'Letter Template deleted successfully.');
    }
}
