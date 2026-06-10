<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (! function_exists('bootstrap_debug_log')) {
    function bootstrap_debug_log(string $message): void
    {
        $logFile = __DIR__.'/../storage/logs/bootstrap-debug.log';
        $line = sprintf("[%s] %s\n", date('c'), $message);

        @file_put_contents($logFile, $line, FILE_APPEND);
    }
}

bootstrap_debug_log('index.php hit');

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    bootstrap_debug_log('maintenance mode bootstrap');
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';
bootstrap_debug_log('autoload loaded');

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';
bootstrap_debug_log('application bootstrapped');

$request = Request::capture();
bootstrap_debug_log(sprintf('request captured: %s %s', $request->method(), $request->getRequestUri()));

try {
    $app->handleRequest($request);
    bootstrap_debug_log('request handled');
} catch (Throwable $e) {
    bootstrap_debug_log(sprintf('request failed: %s - %s', $e::class, $e->getMessage()));

    throw $e;
}
