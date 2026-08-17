<?php
$envFile = __DIR__ . '/../.env';
$env = [];
foreach (file($envFile) as $line) {
    $line = trim($line);
    if ($line && strpos($line, '=') !== false && $line[0] !== '#') {
        [$k, $v] = explode('=', $line, 2);
        $env[trim($k)] = trim($v, '"\'');
    }
}
$pdo = new PDO("mysql:host=localhost;dbname={$env['DB_DATABASE']};charset=utf8", $env['DB_USERNAME'], $env['DB_PASSWORD']);
echo "<pre>";
echo "=== Rooms for hotel 25 ===\n";
$rooms = $pdo->query("SELECT r.id, r.status, rc.title, rc.slug FROM rooms r LEFT JOIN room_contents rc ON rc.room_id = r.id WHERE r.hotel_id=25")->fetchAll(PDO::FETCH_ASSOC);
foreach($rooms as $r) print_r($r);
echo "\n=== Hourly prices for those rooms ===\n";
foreach($rooms as $r) {
    $prices = $pdo->query("SELECT hp.id, hp.price, bh.hour FROM hourly_room_prices hp JOIN booking_hours bh ON bh.id=hp.hour_id WHERE hp.room_id={$r['id']}")->fetchAll(PDO::FETCH_ASSOC);
    echo "Room {$r['id']} ({$r['title']}):\n";
    foreach($prices as $p) echo "  price_id={$p['id']} hour={$p['hour']} price={$p['price']}\n";
}
echo "</pre>";
