<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class BulkAddressUploadController extends Controller
{
    public function index()
    {
        return view('admin.employee.bulk-upload-address');
    }

    public function downloadTemplate()
    {
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=employee_address_bulk_upload_template.csv',
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];

        $columns = ['Employee Code', 'Type', 'Address Line 1', 'Address Line 2', 'City', 'State', 'Country', 'Zipcode'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Example row
            fputcsv($file, ['EMP001', 'permanent', '123 Main St', 'Apt 4B', 'New York', 'NY', 'USA', '10001']);
            fputcsv($file, ['EMP001', 'current', '456 Side St', '', 'New York', 'NY', 'USA', '10002']);
            
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
                $type = strtolower(trim($row['type'] ?? ''));
                
                if (empty($employeeCode) || empty($type)) {
                    $errorCount++;
                    $errors[] = "Row " . ($index + 2) . ": Missing required fields (Employee Code or Type).";
                    continue;
                }

                if (!in_array($type, ['permanent', 'current'])) {
                    $errorCount++;
                    $errors[] = "Row " . ($index + 2) . ": Invalid Type '$type'. Must be 'permanent' or 'current'.";
                    continue;
                }

                // Check if employee exists
                $employee = Employee::where('employee_code', $employeeCode)
                    ->where('business_id', $businessId)
                    ->first();

                if (!$employee) {
                    $errorCount++;
                    $errors[] = "Row " . ($index + 2) . ": Employee code '$employeeCode' not found.";
                    continue;
                }

                $address1 = trim($row['address line 1'] ?? '');
                $address2 = trim($row['address line 2'] ?? '');
                $city = trim($row['city'] ?? '');
                $state = trim($row['state'] ?? '');
                $country = trim($row['country'] ?? '');
                $zipcode = trim($row['zipcode'] ?? '');

                // Update or create address
                EmployeeAddress::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'type' => $type,
                    ],
                    [
                        'address1' => $address1,
                        'address2' => $address2,
                        'city' => $city,
                        'state' => $state,
                        'country' => $country,
                        'zipcode' => $zipcode,
                    ]
                );

                $successCount++;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk address upload failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred during upload. No data was saved. Please check the file format.');
        }

        if ($errorCount > 0 && $successCount == 0) {
            return redirect()->back()->with('error', "Upload failed. Found $errorCount errors.")->with('import_errors', $errors);
        } elseif ($errorCount > 0) {
            return redirect()->back()->with('warning', "Successfully imported/updated $successCount addresses. Found $errorCount errors.")->with('import_errors', $errors);
        }

        return redirect()->back()->with('success', "Successfully imported/updated $successCount addresses.");
    }
}
