<?php

namespace app\core;

use app\App;

abstract class Controller {

    public $route;
    public $layout = 'main';
    
    public function __construct($route) {
        $this->route = $route;
    }

    public function render($view, $vars) {
        $view = new View($this->route, $view);
        $view->layout = $this->layout;
        $view->render($vars);
    }
    
    public function redirect($path) {
        header('location: ' . $path);
    }

}
