<?php
$base = dirname(__DIR__);
header('Content-Type: text/plain');

echo "=== Header brand markup ===\n";
$path = $base . '/resources/views/frontend/partials/header/header-v1.blade.php';
$content = file_get_contents($path);
$pos = strpos($content, 'stayzio-main-brand');
if ($pos !== false) {
    echo substr($content, max(0,$pos-100), 500) . "\n";
}

echo "\n\n=== Loader markup ===\n";
$pos2 = strpos($content, 'stayzio-loader');
if ($pos2 !== false) {
    echo substr($content, max(0,$pos2-50), 500) . "\n";
}

echo "\n\n=== Logo image CSS sizing rule ===\n";
$cssPath = $base . '/public/stayzio/css/style.css';
$css = file_get_contents($cssPath);
if (preg_match('/\.stayzio-brand-logo\s*\{[^}]*\}/', $css, $m)) echo $m[0] . "\n";
if (preg_match('/\.stayzio-loader-card\s+img[^{]*\{[^}]*\}/', $css, $m)) echo $m[0] . "\n";
