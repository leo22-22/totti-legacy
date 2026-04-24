<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$_SERVER['APP_ROOT'] = dirname(__DIR__);

try {
    require dirname(__DIR__) . '/public/index.php';
} catch (\Throwable $e) {
    http_response_code(500);
    echo '<pre style="background:#1a1a1a;color:#ff6b6b;padding:2rem;font-size:14px;">';
    echo htmlspecialchars(get_class($e) . ': ' . $e->getMessage() . "\n\n");
    echo htmlspecialchars($e->getTraceAsString());
    echo '</pre>';
}
