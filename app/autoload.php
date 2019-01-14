<?php

namespace app;

session_start();

spl_autoload_register(function($class) {
    $filename = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($filename)) {
        require $filename;
    }
});

class App extends \app\core\Base {
}