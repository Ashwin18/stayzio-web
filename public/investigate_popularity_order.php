<?php
$base = dirname(__DIR__);
header('Content-Type: text/plain');

require $base . '/vendor/autoload.php';
$app = require_once $base . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Admin routes for popularity ===\n";
foreach (\Illuminate\Support\Facades\Route::getRoutes() as $r) {
    $name = $r->getName() ?? '';
    $uri = $r->uri();
    if (stripos($name, 'popular') !== false || stripos($uri, 'popular') !== false) {
        echo $name . " => " . $uri . " => " . $r->getActionName() . "\n";
    }
}

echo "\n\n=== Does hotels table have a popularity-related column? ===\n";
$cols = \Illuminate\Support\Facades\Schema::getColumnListing('hotels');
foreach ($cols as $c) {
    if (stripos($c, 'popular') !== false || stripos($c, 'order') !== false || stripos($c, 'rank') !== false || stripos($c, 'priority') !== false) {
        echo "hotels.$c\n";
    }
}

echo "\n\n=== Sample current values ===\n";
$sample = \Illuminate\Support\Facades\DB::table('hotels')->select('id')->limit(3)->get();
foreach ($sample as $s) {
    $full = \Illuminate\Support\Facades\DB::table('hotels')->where('id', $s->id)->first();
    echo json_encode($full) . "\n";
}
