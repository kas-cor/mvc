<?php

namespace app\core;

use ErrorException;
use src\Middleware\MiddlewareDispatcher;

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
     * @var MiddlewareDispatcher
     */
    protected MiddlewareDispatcher $middleware;

    /**
     * @var array HTTP methods for route matching
     */
    protected array $methodRoutes = [];

    /**
     * Route constructor
     * @param array $routes
     */
    public function __construct(array $routes) {
        $this->routes = $routes;
        $this->middleware = new MiddlewareDispatcher();
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
                    
                    // Create controller with dependency injection
                    $controller = new $path_controller($this->params);
                    
                    // Execute middleware chain
                    $request = $this->buildRequest();
                    
                    $this->middleware->handle($request, function($req) use ($controller, $action) {
                        return $controller->$action();
                    });
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
     * Build request array from server variables
     * @return array
     */
    private function buildRequest(): array
    {
        return [
            'method' => $_SERVER['REQUEST_METHOD'],
            'uri' => $_SERVER['REQUEST_URI'] ?? '',
            'path' => trim($_SERVER['REDIRECT_URL'] ?? '', '/'),
            'query' => $_GET ?? [],
            'body' => $_POST ?? [],
            'headers' => getallheaders(),
            'accept' => $_SERVER['HTTP_ACCEPT'] ?? '',
        ];
    }

    /**
     * Add middleware to route
     * @param mixed $middleware
     * @return self
     */
    public function addMiddleware($middleware): self
    {
        $this->middleware->add($middleware);
        return $this;
    }

    /**
     * Register route with specific HTTP method
     * @param string $method
     * @param string $route
     * @param array $params
     * @return self
     */
    public function addMethodRoute(string $method, string $route, array $params): self
    {
        $this->methodRoutes[strtoupper($method)][$route] = $params;
        return $this;
    }

    /**
     * Matching class
     * @return bool
     */
    private function match(): bool {
        $url = trim($_SERVER['REDIRECT_URL'], '/');
        $method = $_SERVER['REQUEST_METHOD'];
        
        // First check method-specific routes
        if (isset($this->methodRoutes[$method])) {
            foreach ($this->methodRoutes[$method] as $route => $params) {
                if (preg_match('#^' . $route . '$#', $url, $matches)) {
                    $this->params = $params;
                    return true;
                }
            }
        }
        
        // Then check general routes
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
