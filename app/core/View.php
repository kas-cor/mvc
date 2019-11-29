<?php

namespace app\core;

use ErrorException;

/**
 * Class View
 * @package app\core
 */
class View {

    /**
     * @var string Layout name
     */
    public $layout = 'main';

    /**
     * @var array Path to view
     */
    public $path;

    /**
     * @var string View name
     */
    public $view;

    /**
     * View constructor
     * @param array $path
     * @param string $view
     */
    public function __construct(array $path, string $view) {
        $this->path = $path;
        $this->view = $view;
    }

    /**
     * Render view
     * @param array $vars
     * @throws ErrorException
     */
    public function render(array $vars) {
        $path = __DIR__ . '/../views/' . $this->path['controller'] . '/' . $this->view . '.php';
        if (file_exists($path)) {
            extract($vars);
            ob_start();
            require $path;
            $content = ob_get_clean();
            require __DIR__ . '/../views/layouts/' . $this->layout . '.php';
        } else {
            throw new ErrorException('View "' . $path . '" not found!');
        }
    }

}
