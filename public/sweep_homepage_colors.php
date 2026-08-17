<?php
$base = dirname(__DIR__);
$path = $base . '/resources/views/frontend/home/index-v1.blade.php';

header('Content-Type: text/plain');

if (!file_exists($path)) { die('Target file not found'); }

$content = file_get_contents($path);

$colorMap = [
    '#c41246' => '#00B8B8',
    '#9b123c' => '#0B1F3A',
    '#7c1835' => '#086972',
    '#e8385a' => '#00D4D4',
    '#674457' => '#4A5568',
    '#624054' => '#4A5568',
];

$report = [];
foreach ($colorMap as $old => $new) {
    $count = substr_count($content, $old);
    $content = str_replace($old, $new, $content);
    $report[] = "$old -> $new: $count occurrence(s)";
}

$backup = $path . '.bak-' . date('Ymd-His');
copy($path, $backup);
file_put_contents($path, $content);

echo "=== HOMEPAGE COLOR SWEEP DEPLOY REPORT ===\n\n";
foreach ($report as $line) { echo $line . "\n"; }
echo "\nBackup saved at: " . basename($backup) . "\n";

$viewCacheDir = $base . '/storage/framework/views';
$cleared = 0;
if (is_dir($viewCacheDir)) {
    foreach (glob($viewCacheDir . '/*.php') as $f) { unlink($f); $cleared++; }
}
echo "Compiled views cleared: $cleared files\n";
if (function_exists('opcache_reset')) { opcache_reset(); echo "OPcache cleared.\n"; }
