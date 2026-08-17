<?php
$base = dirname(__DIR__);
header('Content-Type: text/plain');

$path = $base . '/app/Http/Controllers/Admin/HotelManagement/HotelController.php';
$content = file_get_contents($path);

echo "=== popularityOrder() and related update method ===\n";
$pos = strpos($content, 'function popularityOrder');
if ($pos !== false) {
    $endPos = strpos($content, "\n    public function", $pos + 20);
    if ($endPos === false) $endPos = $pos + 2000;
    echo substr($content, $pos, $endPos - $pos) . "\n";
}

echo "\n\n=== Is there an update method for saving new order (search for serial_number writes) ===\n";
foreach (explode("\n", $content) as $i => $line) {
    if (stripos($line, 'serial_number') !== false) {
        echo ($i+1) . ": " . trim($line) . "\n";
    }
}

echo "\n\n=== Does the FRONTEND hotel listing query order by serial_number? ===\n";
$path2 = $base . '/app/Http/Controllers/FrontEnd/RoomController.php';
$content2 = file_get_contents($path2);
foreach (explode("\n", $content2) as $i => $line) {
    if (stripos($line, 'serial_number') !== false || stripos($line, 'orderBy') !== false) {
        echo ($i+1) . ": " . trim($line) . "\n";
    }
}
