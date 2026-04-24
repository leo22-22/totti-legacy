<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$root = dirname(__DIR__);
$_SERVER['APP_ROOT'] = $root;

putenv('LOG_CHANNEL=errorlog');
putenv('SESSION_DRIVER=cookie');
putenv('CACHE_STORE=array');
$_ENV['LOG_CHANNEL'] = 'errorlog';
$_ENV['SESSION_DRIVER'] = 'cookie';
$_ENV['CACHE_STORE'] = 'array';

set_exception_handler(function (\Throwable $e) {
    header('Content-Type: text/plain');
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
    echo $e->getFile() . ':' . $e->getLine() . "\n\n";
    echo $e->getTraceAsString();
});

register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        header('Content-Type: text/plain');
        echo "FATAL: " . $error['message'] . "\n";
        echo "File: " . $error['file'] . ":" . $error['line'] . "\n";
    }
});

require $root . '/public/index.php';
