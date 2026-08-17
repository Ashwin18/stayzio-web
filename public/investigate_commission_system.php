<?php
$base = dirname(__DIR__);
header('Content-Type: text/plain');

require $base . '/vendor/autoload.php';
$app = require_once $base . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Does VendorHourlyInventoryController@updateInline trigger ANY notification/email? ===\n";
$path = $base . '/app/Http/Controllers/Vendor/VendorHourlyInventoryController.php';
$content = file_get_contents($path);
echo (stripos($content, 'notif') !== false || stripos($content, 'mail') !== false || stripos($content, 'Session::flash') !== false ? "Found notification-related code\n" : "NO notification, email, or admin alert of any kind when vendor updates inventory\n");

echo "\n\n=== Admin routes for commission settings ===\n";
foreach (\Illuminate\Support\Facades\Route::getRoutes() as $r) {
    $name = $r->getName() ?? '';
    $uri = $r->uri();
    if (stripos($name, 'commission') !== false || stripos($uri, 'commission') !== false) {
        echo $name . " => " . $uri . " => " . $r->getActionName() . " [" . implode(',', $r->methods()) . "]\n";
    }
}

echo "\n\n=== hotel_daily_inventories commission columns ===\n";
$cols = \Illuminate\Support\Facades\Schema::getColumnListing('hotel_daily_inventories');
foreach ($cols as $c) {
    if (stripos($c, 'commission') !== false) echo "$c\n";
}

echo "\n\n=== Is there ANY default/global commission setting (basic_settings or elsewhere)? ===\n";
$basicCols = \Illuminate\Support\Facades\Schema::getColumnListing('basic_settings');
foreach ($basicCols as $c) {
    if (stripos($c, 'commission') !== false) echo "basic_settings.$c\n";
}

echo "\n\n=== Current commission data coverage - how many inventory rows have commission set vs null? ===\n";
$total = \Illuminate\Support\Facades\DB::table('hotel_daily_inventories')->count();
$withCommission = \Illuminate\Support\Facades\DB::table('hotel_daily_inventories')->whereNotNull('commission_3hrs')->count();
echo "Total inventory rows: $total\n";
echo "Rows with commission_3hrs SET: $withCommission\n";
echo "Rows with commission_3hrs NULL: " . ($total - $withCommission) . "\n";
