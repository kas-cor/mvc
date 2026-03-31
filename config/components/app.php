<?php

return [
    'database' => [
        'host' => env('DBHOST', 'localhost'),
        'dbname' => env('DBNAME', 'mvc_db'),
        'username' => env('DBUSER', 'root'),
        'password' => env('DBPASS', ''),
        'charset' => 'utf8mb4',
    ],
    
    'cache' => [
        'driver' => env('CACHE_DRIVER', 'filesystem'),
        'lifetime' => (int) env('CACHE_LIFETIME', 3600),
        'prefix' => env('CACHE_PREFIX', 'mvc_'),
        'redis_dsn' => env('REDIS_DSN', 'redis://localhost:6379'),
    ],
    
    'logging' => [
        'level' => env('LOG_LEVEL', 'debug'),
        'path' => env('LOG_PATH', __DIR__ . '/../logs'),
        'channels' => ['app', 'error', 'database'],
    ],
    
    'security' => [
        'csrf_enabled' => true,
        'rate_limit_requests' => 60,
        'rate_limit_window' => 60,
    ],
    
    'app' => [
        'name' => env('APP_NAME', 'MVC Application'),
        'env' => env('APP_ENV', 'development'),
        'debug' => env('APP_DEBUG', true),
        'url' => env('APP_URL', 'http://localhost'),
        'timezone' => env('APP_TIMEZONE', 'UTC'),
    ],
];
