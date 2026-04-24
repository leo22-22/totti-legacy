<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$_SERVER['APP_ROOT'] = dirname(__DIR__);

// Force errorlog so Laravel doesn't try to write to disk
$_ENV['LOG_CHANNEL'] = 'errorlog';
$_SERVER['LOG_CHANNEL'] = 'errorlog';
putenv('LOG_CHANNEL=errorlog');

$_ENV['SESSION_DRIVER'] = 'cookie';
$_SERVER['SESSION_DRIVER'] = 'cookie';
putenv('SESSION_DRIVER=cookie');

$_ENV['CACHE_STORE'] = 'array';
$_SERVER['CACHE_STORE'] = 'array';
putenv('CACHE_STORE=array');

set_exception_handler(function (\Throwable $e) {
    http_response_code(500);
    echo '<pre style="background:#1a1a1a;color:#ff6b6b;padding:2rem;font-size:14px;">';
    echo htmlspecialchars(get_class($e) . ': ' . $e->getMessage() . "\n\n");
    echo htmlspecialchars($e->getTraceAsString());
    echo '</pre>';
});

require dirname(__DIR__) . '/public/index.php';
