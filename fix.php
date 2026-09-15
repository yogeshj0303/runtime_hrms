<?php
$files = [
    'resources/views/admin/employee/profile/summary.blade.php', 
    'resources/views/admin/employee/profile/basic.blade.php'
]; 
foreach($files as $file) { 
    $content = file_get_contents($file); 
    $content = str_replace('$employee->profile->', '$employee->profile?->', $content); 
    file_put_contents($file, $content); 
} 
echo 'Fixed!';
