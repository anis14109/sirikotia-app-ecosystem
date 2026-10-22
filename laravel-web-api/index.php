<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/apps/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Serve static files from the Laravel public directory (apps/public). This makes
// the application work from any folder and any web server that routes the request
// here, without needing rewrite rules for every asset type.
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
if (($queryPosition = strpos($requestUri, '?')) !== false) {
    $requestUri = substr($requestUri, 0, $queryPosition);
}
$uri = parse_url($requestUri, PHP_URL_PATH);
if ($uri === null || $uri === '') {
    $uri = '/';
}

$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$basePath = rtrim(dirname($scriptName), '/');
if ($basePath !== '' && str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath));
}
$uri = rawurldecode($uri);

$publicDir = realpath(__DIR__.'/apps/public');
if ($publicDir !== false && $uri !== '' && $uri !== '/') {
    $requested = realpath($publicDir.$uri);
    if ($requested !== false
        && str_starts_with($requested, $publicDir.DIRECTORY_SEPARATOR)
        && is_file($requested)) {
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'text/javascript',
            'mjs' => 'text/javascript',
            'json' => 'application/json',
            'html' => 'text/html',
            'htm' => 'text/html',
            'txt' => 'text/plain',
            'xml' => 'application/xml',
            'map' => 'application/json',
            'webmanifest' => 'application/manifest+json',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'webp' => 'image/webp',
            'avif' => 'image/avif',
            'ico' => 'image/x-icon',
            'bmp' => 'image/bmp',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'otf' => 'font/otf',
            'eot' => 'application/vnd.ms-fontobject',
            'wasm' => 'application/wasm',
            'pdf' => 'application/pdf',
            'mp4' => 'video/mp4',
            'webm' => 'video/webm',
            'mp3' => 'audio/mpeg',
            'wav' => 'audio/wav',
        ];
        $extension = strtolower(pathinfo($requested, PATHINFO_EXTENSION));
        header('Content-Type: '.($mimeTypes[$extension] ?? 'application/octet-stream'));
        header('Content-Length: '.filesize($requested));
        readfile($requested);
        exit;
    }
}

// Register the Composer autoloader...
require __DIR__.'/apps/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/apps/bootstrap/app.php';

$app->handleRequest(Request::capture());