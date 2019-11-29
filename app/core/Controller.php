<?php

namespace app\core;

use ErrorException;

/**
 * Class Controller
 * @package app\core
 */
abstract class Controller {

    /**
     * @var string Path route
     */
    public $route;

    /**
     * @var string Layout name
     */
    public $layout = 'main';

    /**
     * Controller constructor
     * @param string $route
     */
    public function __construct($route) {
        $this->route = $route;
    }

    /**
     * View render
     * @param string $view View name
     * @param array $vars Variables
     * @return null
     * @throws ErrorException
     */
    public function render($view, $vars) {
        $view = new View($this->route, $view);
        $view->layout = $this->layout;
        $view->render($vars);

        return null;
    }

    /**
     * Redirect
     * @param string $path Path to redirect
     */
    public function redirect($path) {
        return header('location: ' . $path);
    }

}
