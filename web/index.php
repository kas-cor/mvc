<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/autoload.php';

$config = require __DIR__ . '/../config/web.php';

(new \app\App($config))->run();
