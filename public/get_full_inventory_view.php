<?php
$base = dirname(__DIR__);
header('Content-Type: text/plain');

$path = $base . '/resources/views/vendors/hotel/inventory/hourly.blade.php';
echo file_get_contents($path);
