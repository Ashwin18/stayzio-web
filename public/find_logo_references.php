<?php
$base = dirname(__DIR__);
header('Content-Type: text/plain');

echo "=== Search all frontend views for 'stayzio-logo' references ===\n";
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base . '/resources/views/frontend'));
$found = [];
foreach ($rii as $file) {
    if ($file->isDir()) continue;
    $c = @file_get_contents($file->getPathname());
    if ($c && stripos($c, 'stayzio-logo') !== false) {
        preg_match_all("/asset\('([^']*stayzio-logo[^']*)'\)/", $c, $m);
        foreach ($m[1] as $path) {
            $found[$path] = true;
        }
    }
}
foreach (array_keys($found) as $p) echo "$p\n";

echo "\n=== Does the actual logo file exist at public/stayzio/images/stayzio-logo.png? ===\n";
$logoPath = $base . '/public/stayzio/images/stayzio-logo.png';
echo file_exists($logoPath) ? "YES, size: " . filesize($logoPath) . " bytes\n" : "NOT FOUND at that path\n";

echo "\n=== Search for any OTHER logo file references (favicon, different names) ===\n";
foreach ($rii as $file) {} // reset not needed, new iterator
$rii2 = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base . '/resources/views/frontend'));
$found2 = [];
foreach ($rii2 as $file) {
    if ($file->isDir()) continue;
    $c = @file_get_contents($file->getPathname());
    if ($c && preg_match_all("/asset\('([^']*logo[^']*\.(png|jpg|jpeg|svg))'\)/i", $c, $m)) {
        foreach ($m[1] as $path) $found2[$path] = true;
    }
}
foreach (array_keys($found2) as $p) echo "$p\n";
