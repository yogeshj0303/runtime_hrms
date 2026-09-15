<?php
$dir = new RecursiveDirectoryIterator('c:/xampp/htdocs/SomyaHRMS/resources/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

foreach ($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    
    if (strpos($content, '<table') !== false) {
        $tableCount = substr_count($content, '<table');
        $responsiveCount = substr_count($content, 'table-responsive');
        
        if ($tableCount > $responsiveCount) {
            echo $path . "\n";
        }
    }
}
