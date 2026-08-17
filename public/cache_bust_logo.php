<?php
$base = dirname(__DIR__);
header('Content-Type: text/plain');

$report = [];

function patchFile($base, $relPath, array $replacements, &$report) {
    $path = $base . '/' . $relPath;
    if (!file_exists($path)) { $report[] = "SKIP $relPath — not found"; return; }
    $content = file_get_contents($path);
    $original = $content;
    $misses = [];
    foreach ($replacements as $old => $new) {
        $count = substr_count($content, $old);
        if ($count === 0) { $misses[] = substr($old, 0, 60); continue; }
        $content = str_replace($old, $new, $content);
    }
    if ($content === $original) {
        $report[] = "NO CHANGE $relPath" . (!empty($misses) ? " (patterns not found: " . implode(' | ', $misses) . ")" : "");
        return;
    }
    $backup = $path . '.bak-' . date('Ymd-His');
    copy($path, $backup);
    file_put_contents($path, $content);
    $report[] = "PATCHED $relPath (backup: " . basename($backup) . ") - $count occurrence(s)";
}

$cacheBust = "?v=" . time();

patchFile($base, 'resources/views/frontend/partials/header/header-v1.blade.php', [
    "{{ asset('stayzio/images/stayzio-logo.png') }}" => "{{ asset('stayzio/images/stayzio-logo.png') }}$cacheBust",
], $report);

echo "=== CACHE-BUST LOGO DEPLOY REPORT ===\n\n";
foreach ($report as $line) { echo $line . "\n"; }

$viewCacheDir = $base . '/storage/framework/views';
$cleared = 0;
if (is_dir($viewCacheDir)) {
    foreach (glob($viewCacheDir . '/*.php') as $f) { unlink($f); $cleared++; }
}
echo "\nCompiled views cleared: $cleared files\n";
if (function_exists('opcache_reset')) { opcache_reset(); echo "OPcache cleared.\n"; }

echo "\n=== Verification: confirm the actual file on disk is genuinely the new logo ===\n";
$logoPath = $base . '/public/stayzio/images/stayzio-logo.png';
if (file_exists($logoPath)) {
    echo "File size: " . filesize($logoPath) . " bytes (should be 65473 if it's the new logo)\n";
    echo "Last modified: " . date('Y-m-d H:i:s', filemtime($logoPath)) . "\n";
} else {
    echo "FILE NOT FOUND - this would explain the issue entirely\n";
}
