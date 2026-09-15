<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeIdentity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class BulkBankUploadController extends Controller
{
    public function index()
    {
        return view('admin.employee.bulk-upload-bank');
    }

    public function downloadTemplate()
    {
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=employee_bank_details_template.csv',
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];

        $columns = [
            'Employee Code', 
            'Bank Name', 
            'Account Number', 
            'IFSC', 
            'Aadhaar Number', 
            'PAN Number', 
            'PF UAN', 
            'ESI Number'
        ];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Example row
            fputcsv($file, [
                'EMP001', 
                'State Bank of India', 
                '123456789012', 
                'SBIN0001234', 
                '123456789012', 
                'ABCDE1234F', 
                '100012345678', 
                '2000123456'
            ]);
            
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $csvData = array_map('str_getcsv', file($file->getRealPath()));
        
        $header = array_shift($csvData);
        // Normalize headers
        $header = array_map(function($h) { return trim(strtolower($h)); }, $header);

        $businessId = Auth::user()->active_business_id;

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($csvData as $index => $row) {
                // Ensure row matches header length
                if (count($header) !== count($row)) {
                    continue; // Skip invalid rows
                }
                
                $row = array_combine($header, $row);
                
                $employeeCode = trim($row['employee code'] ?? '');
                
                if (empty($employeeCode)) {
                    $errorCount++;
                    $errors[] = "Row " . ($index + 2) . ": Missing required field (Employee Code).";
                    continue;
                }

                // Check if employee exists
                $employee = Employee::where('employee_code', $employeeCode)
                    ->where('business_id', $businessId)
                    ->first();

                if (!$employee) {
                    $errorCount++;
                    $errors[] = "Row " . ($index + 2) . ": Employee code '$employeeCode' not found in your business.";
                    continue;
                }

                EmployeeIdentity::updateOrCreate(
                    ['employee_id' => $employee->id],
                    [
                        'bank_name' => trim($row['bank name'] ?? ''),
                        'account_number' => trim($row['account number'] ?? ''),
                        'ifsc' => trim($row['ifsc'] ?? ''),
                        'aadhaar_number' => trim($row['aadhaar number'] ?? ''),
                        'pan_number' => trim($row['pan number'] ?? ''),
                        'pf_uan' => trim($row['pf uan'] ?? ''),
                        'esi_number' => trim($row['esi number'] ?? '')
                    ]
                );
                
                $successCount++;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk bank upload failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred during upload. No data was saved. Please check the file format.');
        }

        if ($errorCount > 0 && $successCount == 0) {
            return redirect()->back()->with('error', "Upload failed. Found $errorCount errors.")->with('import_errors', $errors);
        } elseif ($errorCount > 0) {
            return redirect()->back()->with('warning', "Successfully updated bank details for $successCount employees. Found $errorCount errors.")->with('import_errors', $errors);
        }

        return redirect()->back()->with('success', "Successfully updated bank details for $successCount employees.");
    }
}
