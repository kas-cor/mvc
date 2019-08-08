<?php

namespace app\core;

use ErrorException;

/**
 * Class Controller
 *
 * @package app\core
 */
abstract class Controller {

    /**
     * Path route
     *
     * @var string
     */
    public $route;

    /**
     * Layout name
     *
     * @var string
     */
    public $layout = 'main';

    /**
     * Controller constructor
     *
     * @param string $route
     */
    public function __construct($route) {
        $this->route = $route;
    }

    /**
     * View render
     *
     * @param string $view
     * @param array  $vars
     *
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
     *
     * @param string $path
     */
    public function redirect($path) {
        return header('location: ' . $path);
    }

}
