<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class BulkUploadController extends Controller
{
    public function index()
    {
        return view('admin.employee.bulk-upload');
    }

    public function downloadTemplate()
    {
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=employee_bulk_upload_template.csv',
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];

        $columns = ['First Name', 'Last Name', 'Email', 'Employee Code', 'Phone', 'Joining Date', 'Salary'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Example row
            fputcsv($file, ['John', 'Doe', 'john.doe@example.com', 'EMP001', '9876543210', date('Y-m-d'), '50000']);
            
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
                
                $email = trim($row['email'] ?? '');
                $employeeCode = trim($row['employee code'] ?? '');
                $firstName = trim($row['first name'] ?? '');
                
                if (empty($email) || empty($employeeCode) || empty($firstName)) {
                    $errorCount++;
                    $errors[] = "Row " . ($index + 2) . ": Missing required fields (Email, Employee Code, or First Name).";
                    continue;
                }

                // Check if user exists
                $user = User::where('email', $email)->first();

                if (!$user) {
                    $user = User::create([
                        'name' => $firstName . ' ' . trim($row['last name'] ?? ''),
                        'email' => $email,
                        'password' => Hash::make('password123'), // Default password
                        'active_business_id' => $businessId
                    ]);
                }

                // Check if employee exists
                $employee = Employee::where('employee_code', $employeeCode)
                    ->where('business_id', $businessId)
                    ->first();

                if (!$employee) {
                    Employee::create([
                        'user_id' => $user->id,
                        'business_id' => $businessId,
                        'first_name' => $firstName,
                        'last_name' => trim($row['last name'] ?? ''),
                        'email' => $email,
                        'employee_code' => $employeeCode,
                        'phone' => trim($row['phone'] ?? ''),
                        'joining_date' => !empty($row['joining date']) ? date('Y-m-d', strtotime($row['joining date'])) : null,
                        'salary' => !empty($row['salary']) ? floatval(str_replace(',', '', $row['salary'])) : null,
                        'created_by' => Auth::id(),
                    ]);
                    $successCount++;
                } else {
                    $errorCount++;
                    $errors[] = "Row " . ($index + 2) . ": Employee code '$employeeCode' already exists.";
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk upload failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred during upload. No data was saved. Please check the file format.');
        }

        if ($errorCount > 0 && $successCount == 0) {
            return redirect()->back()->with('error', "Upload failed. Found $errorCount errors.")->with('import_errors', $errors);
        } elseif ($errorCount > 0) {
            return redirect()->back()->with('warning', "Successfully imported $successCount employees. Found $errorCount errors.")->with('import_errors', $errors);
        }

        return redirect()->back()->with('success', "Successfully imported $successCount employees.");
    }
}
