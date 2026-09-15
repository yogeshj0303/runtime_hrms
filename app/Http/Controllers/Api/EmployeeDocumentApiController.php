<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\EmployeeDocument;
use Illuminate\Http\Request;

class EmployeeDocumentApiController extends Controller
{
    public function index($employee_id)
    {
        $documents = EmployeeDocument::where('employee_id', $employee_id)
            ->where('is_hidden', false)
            ->latest()
            ->get();

        $mappedDocuments = $documents->map(function($d) {
            return [
                'id' => $d->id,
                'document_type' => $d->document_name ?? $d->document_type ?? 'Document',
                'file_url' => asset($d->document_file ?? $d->file),
                'verification_status' => $d->status ?? $d->verification_status ?? 'Pending'
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Employee documents fetched successfully',
            'data' => $mappedDocuments
        ]);
    }
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required_without:employee_id|exists:users,id',
            'employee_id' => 'required_without:user_id|exists:users,id',
            'document_type' => 'required_without:document_name|string|max:100',
            'document_name' => 'required_without:document_type|string|max:100',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,txt|max:10240',
            'description' => 'nullable|string',
            'is_hidden' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $userId = $request->user_id ?? $request->employee_id;
        $docType = $request->document_type ?? $request->document_name;

        $uploadedFile = $request->file('file');

        $filename = time().'_'.$uploadedFile->getClientOriginalName();

        $uploadedFile->move(public_path('assets/employee_documents'), $filename);

        $document = EmployeeDocument::create([
            'employee_id' => $userId,
            'document_name' => $docType,
            'document_file' => 'assets/employee_documents/'.$filename,
            'file' => 'assets/employee_documents/'.$filename,
            'description' => $request->description,
            'is_hidden' => $request->is_hidden ?? false,
        ]);

    return response()->json([
        'status' => true,
        'message' => 'Document uploaded successfully',
        'data' => [
            'id' => $document->id,
            'document_type' => $document->document_name,
            'file_url' => asset($document->document_file),
            'verification_status' => 'Pending'
        ]
    ]);
}
}