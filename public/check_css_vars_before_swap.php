<?php
$base = dirname(__DIR__);
header('Content-Type: text/plain');

$path = $base . '/public/stayzio/css/style.css';
$content = file_get_contents($path);

echo "=== Full :root block ===\n";
if (preg_match('/:root\s*\{[^}]*\}/', $content, $m)) echo $m[0] . "\n";

echo "\n=== Count of var(--red) usage across the file ===\n";
echo substr_count($content, 'var(--red)') . " occurrences of var(--red)\n";
echo substr_count($content, 'var(--dark)') . " occurrences of var(--dark)\n";
echo substr_count($content, 'var(--text)') . " occurrences of var(--text)\n";

echo "\n=== Any hardcoded hex values NOT using variables (sample search) ===\n";
foreach (['#e8272b', '#e31e24', '#c41246', '#9b123c', '#1a1a2e', '#7c1835'] as $hex) {
    $c = substr_count($content, $hex);
    if ($c > 0) echo "$hex: $c occurrence(s) in shared stylesheet\n";
}
