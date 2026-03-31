<?php

declare(strict_types=1);

return [
    'connection' => [
        'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_mysql',
        'url' => $_ENV['DATABASE_URL'] ?? null,
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'port' => $_ENV['DB_PORT'] ?? '3306',
        'dbname' => $_ENV['DB_NAME'] ?? 'mvc_db',
        'user' => $_ENV['DB_USER'] ?? 'root',
        'password' => $_ENV['DB_PASSWORD'] ?? '',
        'charset' => 'utf8mb4',
    ],
    'orm' => [
        'paths' => [__DIR__ . '/../src/Model'],
        'proxy_dir' => __DIR__ . '/../cache/doctrine/proxies',
        'auto_generate_proxy_classes' => true,
    ],
];
