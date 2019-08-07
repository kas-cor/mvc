<?php

use Dotenv\Dotenv;

require_once __DIR__ . '/vendor/autoload.php';

Dotenv::create(__DIR__)->load();

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'development',
        'development' => [
            'adapter' => 'mysql',
            'host' => env('DBHOST'),
            'name' => env('DBNAME'),
            'user' => env('DBUSER'),
            'pass' => env('DBPASS'),
            'port' => 3306,
            'charset' => 'utf8',
        ],
    ]
];
