<?php

use App\Application;

/**
 * Router per `php artisan serve` — document root = public_html (non laravel/public).
 *
 * @see Application::publicPath()
 * @see public_html/index.php
 */
$publicPath = realpath(__DIR__.'/../public_html') ?: __DIR__.'/../public_html';

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

if ($uri !== '/' && ! str_contains($uri, '..')) {
    $assetPath = $publicPath.$uri;

    /*
     * The demo server is launched from the Laravel directory while the public
     * document root is the sibling public_html directory. Returning false
     * makes PHP's built-in server look in the wrong document root and turns
     * every published CSS/JS asset into a 404. Serve existing public files
     * explicitly; dynamic requests still go through index.php below.
    */
    if (is_file($assetPath)) {
        $extension = strtolower(pathinfo($assetPath, PATHINFO_EXTENSION));
        $mimeByExtension = [
            'css' => 'text/css; charset=UTF-8',
            'js' => 'application/javascript; charset=UTF-8',
            'mjs' => 'application/javascript; charset=UTF-8',
            'json' => 'application/json; charset=UTF-8',
            'svg' => 'image/svg+xml',
            'html' => 'text/html; charset=UTF-8',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
        ];
        $mime = $mimeByExtension[$extension]
            ?? (function_exists('mime_content_type') ? mime_content_type($assetPath) : false);
        header('Content-Type: '.(is_string($mime) && $mime !== '' ? $mime : 'application/octet-stream'));
        header('Cache-Control: public, max-age=3600');
        readfile($assetPath);

        return true;
    }
}

require $publicPath.'/index.php';
