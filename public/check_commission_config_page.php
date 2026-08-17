<?php
$base = dirname(__DIR__);
header('Content-Type: text/plain');

require $base . '/vendor/autoload.php';
$app = require_once $base . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$r = \Illuminate\Support\Facades\Route::getRoutes()->getByName('admin.room_management.tax_amount');
echo "Route points to: " . ($r ? $r->getActionName() : 'NOT FOUND') . "\n\n";

if ($r) {
    $action = $r->getActionName();
    list($class, $method) = explode('@', $action);
    $classFile = $base . '/app/Http/Controllers/' . str_replace(['App\\Http\\Controllers\\', '\\'], ['', '/'], $class) . '.php';
    echo "=== $method() content ===\n";
    $content = file_get_contents($classFile);
    $pos = strpos($content, "function $method(");
    $endPos = strpos($content, "\n    public function", $pos + 20);
    if ($endPos === false) $endPos = $pos + 1500;
    echo substr($content, $pos, $endPos - $pos) . "\n";
}
