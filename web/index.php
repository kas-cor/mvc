<?php

/**
 * @copyright 2019
 * @author kas-cor <kascorp@gmail.com>
 */

use app\App;
use Dotenv\Dotenv;
use Sentry;

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

Dotenv::create(__DIR__ . '/..')->load();

Sentry\init(['dsn' => 'https://c4933d0d878641aaadf4f5ed15ab631a@sentry.io/1726711']);

$config = require __DIR__ . '/../config/web.php';

try {
    (new App($config))->run();
} catch (ErrorException $e) {
    die($e->getMessage());
}
