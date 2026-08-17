<?php
$base = dirname(__DIR__);
$path = $base . '/public/stayzio/css/style.css';

header('Content-Type: text/plain');

$content = file_get_contents($path);

$old1 = '.stayzio-brand-logo{
    width:auto;
    height:38px;
    object-fit:contain;
    flex:0 0 auto;
}';
$new1 = '.stayzio-brand-logo{
    width:auto;
    height:50px;
    object-fit:contain;
    flex:0 0 auto;
}';

$old2 = '.stayzio-loader-card img{width:auto;height:64px;object-fit:contain;animation:stayzioPulse 1.2s ease-in-out infinite;}';
$new2 = '.stayzio-loader-card img{width:auto;height:80px;object-fit:contain;animation:stayzioPulse 1.2s ease-in-out infinite;}';

$c1 = substr_count($content, $old1);
$c2 = substr_count($content, $old2);
$content = str_replace([$old1, $old2], [$new1, $new2], $content);

$backup = $path . '.bak-' . date('Ymd-His');
copy($path, $backup);
file_put_contents($path, $content);

echo "Header logo resize: $c1 occurrence(s)\n";
echo "Loader logo resize: $c2 occurrence(s)\n";
echo "Backup: " . basename($backup) . "\n";
if (function_exists('opcache_reset')) { opcache_reset(); echo "OPcache cleared.\n"; }
