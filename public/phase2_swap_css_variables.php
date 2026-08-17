<?php
$base = dirname(__DIR__);
$path = $base . '/public/stayzio/css/style.css';

header('Content-Type: text/plain');

if (!file_exists($path)) { die('Target file not found'); }

$content = file_get_contents($path);
$report = [];

// 1. Swap the :root block
$old1 = ":root {
    --red: #e8272b;
    --red-light: #fff0f0;
    --dark: #1a1a2e;
    --text: #2d2d2d;
    --muted: #6b7280;
    --border: #e5e7eb;
    --bg: #ffffff;
    --bg2: #f8f9fa;
    --radius: 12px;
    --shadow: 0 2px 16px rgba(0, 0, 0, 0.08);
    --shadow-hover: 0 8px 32px rgba(0, 0, 0, 0.14);
}";

$new1 = ":root {
    --red: #00B8B8;
    --red-light: #e0f7f7;
    --dark: #0B1F3A;
    --text: #2D3748;
    --muted: #6b7280;
    --border: #e5e7eb;
    --bg: #ffffff;
    --bg2: #f8f9fa;
    --radius: 12px;
    --shadow: 0 2px 16px rgba(0, 0, 0, 0.08);
    --shadow-hover: 0 8px 32px rgba(0, 0, 0, 0.14);
}";

$c1 = substr_count($content, $old1);
$content = str_replace($old1, $new1, $content);
$report[] = ":root block swap: $c1 occurrence(s)";

// 2. Fix hardcoded hex values in this same file
$hexMap = [
    '#e8272b' => '#00B8B8',
    '#9b123c' => '#0B1F3A',
    '#1a1a2e' => '#0B1F3A',
];
foreach ($hexMap as $old => $new) {
    $c = substr_count($content, $old);
    $content = str_replace($old, $new, $content);
    $report[] = "Hardcoded $old -> $new: $c occurrence(s)";
}

$backup = $path . '.bak-' . date('Ymd-His');
copy($path, $backup);
file_put_contents($path, $content);

echo "=== PHASE 2: CORE CSS VARIABLE SWAP DEPLOY REPORT ===\n\n";
foreach ($report as $line) { echo $line . "\n"; }
echo "\nBackup saved at: " . basename($backup) . "\n";
echo "\nThis single change should cascade to every element using var(--red), var(--dark), or var(--text)\n";
echo "across every page that loads this stylesheet - buttons, links, prices, headings, etc.\n";
