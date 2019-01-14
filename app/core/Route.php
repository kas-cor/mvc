<?php

namespace app\core;

use app\App;

class Route {

    protected $routes;
    protected $params;

    public function __construct($routes) {
        $this->routes = $routes;
    }

    public function init() {
        if ($this->match()) {
            $path_controller = 'app\\controllers\\' . ucfirst($this->params['controller']) . 'Controller';
            if (class_exists($path_controller)) {
                $action = $this->params['action'] . 'Action';
                if (method_exists($path_controller, $action)) {
                    App::$components['routes'] = $this->params;
                    $controller = new $path_controller($this->params);
                    $controller->$action();
                } else {
                    throw new \ErrorException('Action "' . $action . '" not found!');
                }
            } else {
                throw new \ErrorException('Controller "' . $path_controller . '" not found!');
            }
        } else {
            throw new \ErrorException('Route "' . $_SERVER['REDIRECT_URL'] . '" not found!');
        }
    }

    private function match() {
        $url = trim($_SERVER['REDIRECT_URL'], '/');
        foreach ($this->routes as $route => $params) {
            if ($route == 'class') {
                continue;
            }
            if (preg_match('#^' . $route . '$#', $url, $matches)) {
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

}
