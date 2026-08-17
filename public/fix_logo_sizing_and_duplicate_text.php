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
        if (strpos($content, $old) === false) { $misses[] = substr($old, 0, 60); continue; }
        $content = str_replace($old, $new, $content);
    }
    if ($content === $original) {
        $report[] = "NO CHANGE $relPath" . (!empty($misses) ? " (patterns not found: " . implode(' | ', $misses) . ")" : "");
        return;
    }
    $backup = $path . '.bak-' . date('Ymd-His');
    copy($path, $backup);
    file_put_contents($path, $content);
    $report[] = "PATCHED $relPath (backup: " . basename($backup) . ")" . (!empty($misses) ? " [WARNING: " . implode(' | ', $misses) . "]" : "");
}

// ── 1. Remove redundant text in header, resize logo image ──
patchFile($base, 'resources/views/frontend/partials/header/header-v1.blade.php', [
    '            <a class="navbar-brand stayzio-main-brand" href="{{ route(\'index\') }}">
                <img src="{{ asset(\'stayzio/images/stayzio-logo.png\') }}"
                     alt="{{ $websiteInfo->website_title }}"
                     class="stayzio-brand-logo">
                <span class="stayzio-brand-text">Stay<span>Zio</span></span>
            </a>' =>
    '            <a class="navbar-brand stayzio-main-brand" href="{{ route(\'index\') }}">
                <img src="{{ asset(\'stayzio/images/stayzio-logo.png\') }}"
                     alt="{{ $websiteInfo->website_title }}"
                     class="stayzio-brand-logo">
            </a>',

    '<div id="stayzio-loader" class="stayzio-loader" aria-label="Loading StayZio">
    <div class="stayzio-loader-card">
        <img src="{{ asset(\'stayzio/images/stayzio-logo.png\') }}" alt="StayZio Logo">
        <div class="stayzio-loader-name">Stay<span>Zio</span></div>
        <div class="stayzio-loader-line"><span></span></div>
    </div>
</div>' =>
    '<div id="stayzio-loader" class="stayzio-loader" aria-label="Loading StayZio">
    <div class="stayzio-loader-card">
        <img src="{{ asset(\'stayzio/images/stayzio-logo.png\') }}" alt="StayZio Logo">
        <div class="stayzio-loader-line"><span></span></div>
    </div>
</div>',
], $report);

// ── 2. Fix CSS sizing for wordmark-shaped logo instead of square icon ──
patchFile($base, 'public/stayzio/css/style.css', [
    '.stayzio-brand-logo{
    width:44px;
    height:44px;
    object-fit:contain;
    border-radius:8px;
    flex:0 0 auto;
}' =>
    '.stayzio-brand-logo{
    width:auto;
    height:38px;
    object-fit:contain;
    flex:0 0 auto;
}',

    '.stayzio-loader-card img{width:86px;height:86px;object-fit:contain;border-radius:18px;animation:stayzioPulse 1.2s ease-in-out infinite;}' =>
    '.stayzio-loader-card img{width:auto;height:64px;object-fit:contain;animation:stayzioPulse 1.2s ease-in-out infinite;}',
], $report);

$viewCacheDir = $base . '/storage/framework/views';
$cleared = 0;
if (is_dir($viewCacheDir)) {
    foreach (glob($viewCacheDir . '/*.php') as $f) { unlink($f); $cleared++; }
}
$report[] = "Compiled views cleared: $cleared files";
if (function_exists('opcache_reset')) { opcache_reset(); $report[] = "OPcache cleared."; }

echo "=== LOGO SIZING + DOUBLE-BRANDING FIX DEPLOY REPORT ===\n\n";
foreach ($report as $line) { echo $line . "\n"; }
echo "\nDone.\n";
