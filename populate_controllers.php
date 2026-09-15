<?php

$controllers = [
    'WorkProfileController' => 'work-profile',
    'PolicyController' => 'policies',
    'SalaryController' => 'salary',
    'IdentityController' => 'identity',
    'AddressController' => 'address',
    'DocumentController' => 'documents',
    'AssetController' => 'assets',
    'FamilyController' => 'family',
    'PermissionController' => 'permissions',
    'LoginController' => 'login-access',
    'AdditionalInfoController' => 'additional-info',
    'ActivityLogController' => 'activity-logs'
];

$dir = 'C:/xampp/htdocs/SomyaHRMS)n/app/Http/Controllers/Employee/';

foreach ($controllers as $className => $viewName) {
    $content = "<?php\n\nnamespace App\Http\Controllers\Employee;\n\nuse App\Http\Controllers\Controller;\nuse Illuminate\Http\Request;\nuse App\Models\Employee;\n\nclass $className extends Controller\n{\n    public function index(Request \$request)\n    {\n        \$employee = Employee::findOrFail(\$request->id);\n        return view('admin.employee.profile.$viewName', compact('employee'));\n    }\n}\n";
    
    file_put_contents($dir . $className . '.php', $content);
}

echo "Controllers updated!";
