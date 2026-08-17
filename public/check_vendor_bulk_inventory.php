<?php
$base = dirname(__DIR__);
header('Content-Type: text/plain');

require $base . '/vendor/autoload.php';
$app = require_once $base . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== All vendor inventory-related routes ===\n";
foreach (\Illuminate\Support\Facades\Route::getRoutes() as $r) {
    $name = $r->getName() ?? '';
    $uri = $r->uri();
    if (stripos($name, 'inventory') !== false && stripos($name, 'vendor') !== false) {
        echo $name . " => " . $uri . " => " . $r->getActionName() . " [" . implode(',', $r->methods()) . "]\n";
    }
}

echo "\n\n=== VendorHourlyInventoryController - full method list ===\n";
$path = $base . '/app/Http/Controllers/Vendor/VendorHourlyInventoryController.php';
$content = file_get_contents($path);
foreach (explode("\n", $content) as $i => $line) {
    if (stripos($line, 'public function') !== false) {
        echo ($i+1) . ": " . trim($line) . "\n";
    }
}

echo "\n\n=== Search for any 'bulk' related code anywhere in this controller ===\n";
echo (stripos($content, 'bulk') !== false ? "FOUND references to 'bulk'\n" : "NO 'bulk' references found\n");

echo "\n\n=== Vendor hourly inventory view - date range / bulk related markup ===\n";
$path2 = $base . '/resources/views/vendors/hotel/inventory/hourly.blade.php';
$content2 = file_get_contents($path2);
echo "Contains 'date range' or 'bulk' keywords: ";
echo (stripos($content2, 'bulk') !== false || stripos($content2, 'date range') !== false || stripos($content2, 'date_from') !== false ? "YES\n" : "NO\n");
echo "File size: " . strlen($content2) . " bytes\n";
