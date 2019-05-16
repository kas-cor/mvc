<?php /** @noinspection MissedFieldInspection */

use app\core\Assets;
use app\core\Db;
use app\core\Route;

return [
    'components' => [
        'assets' => [
            'class' => Assets::class,
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
            'class' => Db::class,
            'config' => require __DIR__ . '/db.php',
            'pagination' => 3,
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
