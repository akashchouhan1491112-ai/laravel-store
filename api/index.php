<?php

// Ensure /tmp directories exist for serverless environment
$dirs = [
    '/tmp/storage',
    '/tmp/storage/app',
    '/tmp/storage/app/public',
    '/tmp/storage/framework',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
    '/tmp/bootstrap-cache',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

// Copy initial database.sqlite to /tmp if it doesn't exist
$localSqlite = dirname(__DIR__) . '/database/database.sqlite';
$tmpSqlite = '/tmp/database.sqlite';
if (!file_exists($tmpSqlite)) {
    if (file_exists($localSqlite)) {
        @copy($localSqlite, $tmpSqlite);
    } else {
        @touch($tmpSqlite);
    }
    @chmod($tmpSqlite, 0666);
}

// Essential Serverless Environment Settings
$serverlessEnv = [
    'APP_ENV' => 'production',
    'APP_DEBUG' => 'true',
    'APP_KEY' => getenv('APP_KEY') ?: 'base64:XzkH+qVgx+YfQzJe3xNS+cqgdjgyxHnfr4TpRztuiic=',
    'APP_STORAGE' => '/tmp/storage',
    'VIEW_COMPILED_PATH' => '/tmp/storage/framework/views',
    'APP_SERVICES_CACHE' => '/tmp/bootstrap-cache/services.php',
    'APP_PACKAGES_CACHE' => '/tmp/bootstrap-cache/packages.php',
    'APP_CONFIG_CACHE' => '/tmp/bootstrap-cache/config.php',
    'APP_ROUTES_CACHE' => '/tmp/bootstrap-cache/routes.php',
    'APP_EVENTS_CACHE' => '/tmp/bootstrap-cache/events.php',
    'CACHE_STORE' => 'array',
    'CACHE_DRIVER' => 'array',
    'SESSION_DRIVER' => 'cookie',
    'LOG_CHANNEL' => 'stderr',
    'DB_CONNECTION' => 'sqlite',
    'DB_DATABASE' => '/tmp/database.sqlite',
];

foreach ($serverlessEnv as $key => $val) {
    putenv("{$key}={$val}");
    $_ENV[$key] = $val;
    $_SERVER[$key] = $val;
}

// Forward to public/index.php
require dirname(__DIR__) . '/public/index.php';
