<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

use function Safe\define;
use function Safe\set_time_limit;

define('LARAVEL_START', microtime(true));
define('LARAVEL_DIR', __DIR__.'/../laravel');

set_time_limit(0);

$laravelDirectory = __DIR__.'/../laravel';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $laravelDirectory.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $laravelDirectory.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
$application = require_once $laravelDirectory.'/bootstrap/app.php';
if (! $application instanceof Application) {
    throw new RuntimeException('Laravel bootstrap did not return an application instance.');
}

$application->handleRequest(Request::capture());
