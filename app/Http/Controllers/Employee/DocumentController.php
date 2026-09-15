<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

use App\Models\EmployeeDocument;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $employee = Employee::with('documents')->findOrFail($request->id);
        return view('admin.employee.profile.documents', compact('employee'));
    }

    public function store(Request $request)
    {
        $employee = Employee::findOrFail($request->id);

        if ($employee->documents()->count() >= 50) {
            return redirect()->back()->with('error', 'Maximum 50 documents can be uploaded for one employee.');
        }

        $request->validate([
            'document_file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,txt|max:10240',
            'description' => 'nullable|string'
        ]);

        $file = $request->file('document_file');
        
        $originalName = $file->getClientOriginalName();
        if (strlen($originalName) > 100) {
            return redirect()->back()->with('error', 'File name should not be more than 100 characters long.');
        }

        $filename = time().'_'.$originalName;
        $file->move(public_path('assets/employee_documents'), $filename);
        $path = 'assets/employee_documents/' . $filename;

        EmployeeDocument::create([
            'employee_id' => $employee->id,
            'document_name' => $originalName,
            'document_file' => $path,
            'file' => $path,
            'description' => $request->description,
            'is_hidden' => $request->has('is_hidden') ? true : false,
            'status' => 'verified'
        ]);

        return redirect()->back()->with('success', 'Document uploaded successfully.');
    }

    public function destroy($id)
    {
        $document = EmployeeDocument::findOrFail($id);
        
        $filePath = public_path($document->document_file);
        if(file_exists($filePath)) {
            unlink($filePath);
        }
        
        $document->delete();

        return redirect()->back()->with('success', 'Document deleted successfully.');
    }
}
