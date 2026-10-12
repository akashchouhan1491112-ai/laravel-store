<?php

// Ensure serverless /tmp directories exist for Laravel on Vercel
if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL']) || getenv('VERCEL')) {
    $storageDirs = [
        '/tmp/storage',
        '/tmp/storage/app',
        '/tmp/storage/app/public',
        '/tmp/storage/framework',
        '/tmp/storage/framework/cache',
        '/tmp/storage/framework/cache/data',
        '/tmp/storage/framework/sessions',
        '/tmp/storage/framework/views',
        '/tmp/storage/logs',
    ];

    foreach ($storageDirs as $dir) {
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }

    // Copy initial database.sqlite to /tmp if it doesn't exist
    $localSqlite = __DIR__ . '/../database/database.sqlite';
    $tmpSqlite = '/tmp/database.sqlite';
    if (!file_exists($tmpSqlite)) {
        if (file_exists($localSqlite)) {
            @copy($localSqlite, $tmpSqlite);
        } else {
            @touch($tmpSqlite);
        }
    }
}

// Forward Vercel requests to Laravel's public entrypoint
require __DIR__ . '/../public/index.php';
