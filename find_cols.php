<?php
$dir = new RecursiveDirectoryIterator('c:/xampp/htdocs/SomyaHRMS/resources/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

$count = 0;
foreach ($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    
    // Look for class="col-X" (where X is not 12) without any col-md, col-lg etc.
    if (preg_match_all('/class="([^"]*\bcol-(?:[1-9]|10|11)\b[^"]*)"/', $content, $matches)) {
        foreach ($matches[1] as $classAttr) {
            // Check if there is NO responsive breakpoint class
            if (!preg_match('/\bcol-(?:sm|md|lg|xl|xxl)-/', $classAttr)) {
                echo basename($path) . ": " . $classAttr . "\n";
                $count++;
            }
        }
    }
}
echo "Total found: " . $count . "\n";
