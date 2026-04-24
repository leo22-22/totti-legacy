<?php

// Step 1: basic output test
echo "PHP OK: " . PHP_VERSION . "\n";
flush();

$root = dirname(__DIR__);

// Step 2: check vendor
echo "Vendor: " . (file_exists($root . '/vendor/autoload.php') ? 'EXISTS' : 'MISSING') . "\n";
flush();

// Step 3: check .env
echo "Env file: " . (file_exists($root . '/.env') ? 'EXISTS' : 'MISSING') . "\n";
flush();

// Step 4: force env vars
putenv('LOG_CHANNEL=errorlog');
putenv('SESSION_DRIVER=cookie');
putenv('CACHE_STORE=array');

$_ENV['LOG_CHANNEL'] = 'errorlog';
$_ENV['SESSION_DRIVER'] = 'cookie';
$_ENV['CACHE_STORE'] = 'array';

// Step 5: try to load Laravel
echo "Loading Laravel...\n";
flush();

set_exception_handler(function (\Throwable $e) {
    echo "\nERROR: " . get_class($e) . ': ' . $e->getMessage() . "\n";
    echo $e->getFile() . ':' . $e->getLine() . "\n";
});

$_SERVER['APP_ROOT'] = $root;

require $root . '/public/index.php';
