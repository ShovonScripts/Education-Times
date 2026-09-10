<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Strip Permissions-Policy headers from upstream (XAMPP/Apache/CDN) that
// include features some browsers don't recognize (e.g. "attribution-reporting").
// We send a clean, minimal Permissions-Policy that all modern browsers accept.
if (function_exists('header_remove')) {
    @header_remove('Permissions-Policy');
    @header_remove('Permissions-Policy-Report-Only');
}
header('Permissions-Policy: geolocation=(), microphone=(), camera=()');

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
