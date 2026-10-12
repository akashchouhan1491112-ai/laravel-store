<?php

use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\VendorApprovedMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'vendor.approved' => VendorApprovedMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

$storagePath = env('APP_STORAGE');
if (!$storagePath && file_exists('/tmp') && DIRECTORY_SEPARATOR === '/') {
    $storagePath = '/tmp/storage';
}

if ($storagePath) {
    $app->useStoragePath($storagePath);
}

return $app;
