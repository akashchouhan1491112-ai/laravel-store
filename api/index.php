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

// Helper to set environment variable if not already defined
function setEnvDefault(string $key, string $default): void {
    $existing = getenv($key);
    if ($existing === false || $existing === '') {
        $existing = $_ENV[$key] ?? ($_SERVER[$key] ?? null);
    }
    $value = ($existing !== null && $existing !== false && $existing !== '') ? (string) $existing : $default;
    putenv("{$key}={$value}");
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}

// Storage paths for serverless read-only filesystem
setEnvDefault('APP_STORAGE', '/tmp/storage');
setEnvDefault('LARAVEL_STORAGE_PATH', '/tmp/storage');
setEnvDefault('VIEW_COMPILED_PATH', '/tmp/storage/framework/views');
setEnvDefault('APP_SERVICES_CACHE', '/tmp/bootstrap-cache/services.php');
setEnvDefault('APP_PACKAGES_CACHE', '/tmp/bootstrap-cache/packages.php');
setEnvDefault('APP_CONFIG_CACHE', '/tmp/bootstrap-cache/config.php');
setEnvDefault('APP_ROUTES_CACHE', '/tmp/bootstrap-cache/routes.php');
setEnvDefault('APP_EVENTS_CACHE', '/tmp/bootstrap-cache/events.php');

// Application defaults
setEnvDefault('APP_ENV', 'production');
setEnvDefault('APP_DEBUG', 'true');
setEnvDefault('APP_KEY', 'base64:XzkH+qVgx+YfQzJe3xNS+cqgdjgyxHnfr4TpRztuiic=');
setEnvDefault('LOG_CHANNEL', 'stderr');
setEnvDefault('CACHE_STORE', 'array');
setEnvDefault('CACHE_DRIVER', 'array');
setEnvDefault('SESSION_DRIVER', 'cookie');

// Database setup: Only fall back to /tmp/database.sqlite if DB_CONNECTION is not externally configured
$dbConnection = getenv('DB_CONNECTION') ?: ($_ENV['DB_CONNECTION'] ?? ($_SERVER['DB_CONNECTION'] ?? 'sqlite'));
setEnvDefault('DB_CONNECTION', $dbConnection);

if ($dbConnection === 'sqlite') {
    $localSqlite = dirname(__DIR__) . '/database/database.sqlite';
    $tmpSqlite = '/tmp/database.sqlite';

    if (!file_exists($tmpSqlite) || filesize($tmpSqlite) === 0) {
        if (file_exists($localSqlite) && filesize($localSqlite) > 0) {
            @copy($localSqlite, $tmpSqlite);
        } else {
            @touch($tmpSqlite);
        }
        @chmod($tmpSqlite, 0666);
    }

    setEnvDefault('DB_DATABASE', $tmpSqlite);
}

// Normalize SCRIPT_NAME so Symfony Request doesn't strip '/api' prefix from API routes
$_SERVER['SCRIPT_NAME'] = '/index.php';

// Forward request to Laravel public front controller
require dirname(__DIR__) . '/public/index.php';
