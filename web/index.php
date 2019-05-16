<?php

/**
 * @copyright 2019
 * @author kas-cor <kascorp@gmail.com>
 */

use app\App;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/autoload.php';

$config = require __DIR__ . '/../config/web.php';

try {
    (new App($config))->run();
} catch (ErrorException $e) {
}
