<?php
$base = dirname(__DIR__);
header('Content-Type: text/plain');

$path = $base . '/resources/views/frontend/home/index-v1.blade.php';
$content = file_get_contents($path);

$colors = ['#e31e24', '#c41246', '#9b123c', '#7c1835', '#e8385a', '#e8272b', '#1a1a2e', '#0b1f3a', '#674457', '#624054', '#9b123c'];
foreach ($colors as $hex) {
    $count = substr_count(strtolower($content), strtolower($hex));
    if ($count > 0) echo "$hex: $count occurrence(s)\n";
}

echo "\n=== Any CSS custom properties (var(--xxx)) already used in this file? ===\n";
preg_match_all('/var\(--[\w-]+\)/', $content, $m);
$unique = array_unique($m[0]);
foreach ($unique as $v) echo "$v\n";
