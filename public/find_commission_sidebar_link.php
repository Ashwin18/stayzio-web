<?php
$base = dirname(__DIR__);
header('Content-Type: text/plain');

$path = $base . '/resources/views/admin/layout.blade.php';
$content = file_get_contents($path);

$pos = stripos($content, 'Commission Config');
if ($pos !== false) {
    echo substr($content, max(0,$pos-400), 500) . "\n";
} else {
    echo "'Commission Config' text not found in sidebar - checking broader for 'commission'\n";
    $pos2 = stripos($content, 'commission');
    if ($pos2 !== false) {
        echo substr($content, max(0,$pos2-300), 500) . "\n";
    } else {
        echo "No 'commission' reference found anywhere in the sidebar at all\n";
    }
}
