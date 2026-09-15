<?php

$views = [
    'work-profile' => 'Work Profile',
    'policies' => 'Policies',
    'salary' => 'Salary',
    'identity' => 'Identity',
    'address' => 'Addresses',
    'documents' => 'Documents',
    'assets' => 'Assets',
    'family' => 'Family Members',
    'permissions' => 'Permissions',
    'login-access' => 'Login & Access',
    'additional-info' => 'Additional Info',
    'activity-logs' => 'Activity Logs'
];

$dir = 'C:/xampp/htdocs/SomyaHRMS)n/resources/views/admin/employee/profile/';

foreach ($views as $file => $title) {
    $content = "@extends('admin.employee.profile.layout')\n\n@section('profile_title', '$title')\n@section('profile_description', 'Manage $title records.')\n\n@section('profile_content')\n<div class=\"text-center text-muted p-5\">\n    <i class=\"ri-tools-line\" style=\"font-size: 48px;\"></i>\n    <h5 class=\"mt-3\">$title Module Under Construction</h5>\n    <p>This section will be built in the next phase.</p>\n</div>\n@endsection";
    
    file_put_contents($dir . $file . '.blade.php', $content);
}

echo "Views generated!";
