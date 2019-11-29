<?php

use app\core\{Assets, Db, Route};

return [
    'components' => [
        'assets' => [
            'class' => Assets::class,
            'cache' => env('ASSETS_CACHE'),
            'css' => [
                '/vendor/npm-asset/normalize.css/normalize.css',
                '/vendor/bower-asset/bootstrap/dist/css/bootstrap.min.css',
            ],
            'js' => [
                '/vendor/bower-asset/jquery/dist/jquery.min.js',
                '/vendor/bower-asset/bootstrap/dist/js/bootstrap.js',
            ],
        ],
        'db' => [
            'class' => Db::class,
            'config' => [
                'host' => env('DBHOST'),
                'dbname' => env('DBNAME'),
                'username' => env('DBUSER'),
                'password' => env('DBPASS'),
            ],
            'pagination' => env('PAGINATION'),
        ],
        'routes' => [
            'class' => Route::class,
            '' => [
                'controller' => 'main',
                'action' => 'index',
            ],
            'main/sort' => [
                'controller' => 'main',
                'action' => 'sort',
            ],
            'admin' => [
                'controller' => 'admin',
                'action' => 'index',
            ],
            'admin/login' => [
                'controller' => 'admin',
                'action' => 'login',
            ],
            'admin/logout' => [
                'controller' => 'admin',
                'action' => 'logout',
            ],
        ],
    ],
];
