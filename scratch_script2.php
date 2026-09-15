<?php
$dir = 'app/Http/Controllers/Setup';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$phpFiles = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

foreach ($phpFiles as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    
    // Check if the controller has a store or update method
    if (strpos($content, 'function store(') !== false || strpos($content, 'function update(') !== false) {
        $hasBusinessId = strpos($content, 'business_id') !== false;
        $hasUserId = strpos($content, 'user_id') !== false;
        
        if (!$hasBusinessId || !$hasUserId) {
            echo str_replace('\\', '/', $path) . ' - hasBusinessId: ' . ($hasBusinessId ? 'yes' : 'no') . ', hasUserId: ' . ($hasUserId ? 'yes' : 'no') . "\n";
        }
    }
}
