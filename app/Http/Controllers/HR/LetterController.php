<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeLetter;
use App\Models\LetterTemplate;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmployeeLetterMail;
use Carbon\Carbon;

class LetterController extends Controller
{
    public function index()
    {
        $letters = EmployeeLetter::with(['employee', 'template'])
            ->whereHas('employee', function ($query) {
                $query->where('business_id', Auth::user()->active_business_id);
            })
            ->latest()
            ->paginate(20);

        return view('admin.hr.letters.index', compact('letters'));
    }

    public function create()
    {
        $templates = LetterTemplate::where('is_active', true)->get();
        $employees = Employee::where('business_id', Auth::user()->active_business_id)
            ->get();
            
        return view('admin.hr.letters.create', compact('templates', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'letter_template_id' => 'required|exists:letter_templates,id',
            'issued_date' => 'required|date',
        ]);

        $employee = Employee::with(['workProfiles.designation', 'workProfiles.department', 'workProfiles.location'])->findOrFail($request->employee_id);
        $template = LetterTemplate::findOrFail($request->letter_template_id);

        // Generate dynamic content
        $content = $template->content;
        
        $placeholders = [
            '{{employee_name}}' => $employee->first_name . ' ' . $employee->last_name,
            '{{employee_code}}' => $employee->employee_code ?? 'N/A',
            '{{designation}}' => $employee->workProfiles->first()->designation->name ?? 'Employee',
            '{{department}}' => $employee->workProfiles->first()->department->name ?? 'N/A',
            '{{location}}' => $employee->workProfiles->first()->location->name ?? 'N/A',
            '{{joining_date}}' => $employee->joining_date ? $employee->joining_date->format('d M, Y') : 'N/A',
            '{{salary}}' => $employee->salary ? number_format($employee->salary, 2) : 'N/A',
            '{{date}}' => Carbon::parse($request->issued_date)->format('d M, Y'),
            '{{business_name}}' => $employee->business->name ?? 'Company',
        ];
        
        foreach ($placeholders as $key => $value) {
            $content = str_replace($key, $value, $content);
        }

        EmployeeLetter::create([
            'employee_id' => $request->employee_id,
            'letter_template_id' => $request->letter_template_id,
            'issued_date' => $request->issued_date,
            'generated_html' => $content,
            'status' => 'Draft'
        ]);

        return redirect()->route('hr.letters.index')->with('success', 'Letter generated successfully. You can review and publish it.');
    }

    public function show($id)
    {
        $letter = EmployeeLetter::with(['employee', 'template'])->findOrFail($id);
        return view('admin.hr.letters.show', compact('letter'));
    }

    public function edit($id)
    {
        $letter = EmployeeLetter::findOrFail($id);
        return view('admin.hr.letters.edit', compact('letter'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'generated_html' => 'required|string',
            'status' => 'required|in:Draft,Published'
        ]);

        $letter = EmployeeLetter::with('employee')->findOrFail($id);
        
        $wasDraft = $letter->status !== 'Published';

        $letter->update([
            'generated_html' => $request->generated_html,
            'status' => $request->status
        ]);

        if ($wasDraft && $request->status === 'Published' && $letter->employee && $letter->employee->email) {
            try {
                Mail::to($letter->employee->email)->send(new EmployeeLetterMail($letter));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send letter email: " . $e->getMessage());
                return redirect()->route('hr.letters.show', $letter->id)->with('success', 'Letter updated successfully. However, the email could not be sent due to mail server configuration.');
            }
        }

        return redirect()->route('hr.letters.show', $letter->id)->with('success', 'Letter updated successfully.');
    }

    public function destroy($id)
    {
        $letter = EmployeeLetter::findOrFail($id);
        $letter->delete();

        return redirect()->route('hr.letters.index')->with('success', 'Letter deleted successfully.');
    }
}
