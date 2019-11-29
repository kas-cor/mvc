<?php

/**
 * @copyright 2019
 * @author kas-cor <kascorp@gmail.com>
 * @link https://github.com/kas-cor/mvc
 */

use app\App;
use Dotenv\Dotenv;

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

Dotenv::create(__DIR__ . '/..')->load();

$config = require __DIR__ . '/../config/web.php';

try {
    (new App($config))->run();
} catch (ErrorException $e) {
    die($e->getMessage());
}
