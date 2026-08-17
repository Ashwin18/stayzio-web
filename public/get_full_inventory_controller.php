<?php
$base = dirname(__DIR__);
header('Content-Type: text/plain');

$path = $base . '/app/Http/Controllers/Vendor/VendorHourlyInventoryController.php';
echo file_get_contents($path);
