<?php

namespace app\core;

use app\App;
use ErrorException;

/**
 * Class Route
 * @package app\core
 */
class Route {

    /**
     * @var array App routes
     */
    protected $routes;

    /**
     * @var array App params
     */
    protected $params;

    /**
     * Route constructor
     * @param array $routes
     */
    public function __construct(array $routes) {
        $this->routes = $routes;
    }

    /**
     * @throws ErrorException
     */
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
                    throw new ErrorException('Action "' . $action . '" not found!');
                }
            } else {
                throw new ErrorException('Controller "' . $path_controller . '" not found!');
            }
        } else {
            throw new ErrorException('Route "' . $_SERVER['REDIRECT_URL'] . '" not found!');
        }
    }

    /**
     * Matching class
     * @return bool
     */
    private function match(): bool {
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
