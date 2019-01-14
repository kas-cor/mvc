<?php

return [
    'components' => [
        'assets' => [
            'class' => \app\core\Assets::class,
            'cache' => 'YmdH', // like php date('YmdHi'), this example is every minute
            'css' => [
                '/vendor/twbs/bootstrap/dist/css/bootstrap.min.css',
            ],
            'js' => [
                '/vendor/components/jquery/jquery.min.js',
                '/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js',
            ],
        ],
        'db' => [
            'class' => \app\core\Db::class,
            'config' => require __DIR__ . '/db.php',
            'paginations' => 3,
        ],
        'routes' => [
            'class' => \app\core\Route::class,
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
