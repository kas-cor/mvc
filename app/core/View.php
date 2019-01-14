<?php

namespace app\core;

class View {

    public $layout = 'main';
    public $path;
    public $view;

    public function __construct($path, $view) {
        $this->path = $path;
        $this->view = $view;
    }

    public function render($vars) {
        $path = __DIR__ . '/../views/' . $this->path['controller'] . '/' . $this->view . '.php';
        if (file_exists($path)) {
            extract($vars);
            ob_start();
            require $path;
            $content = ob_get_clean();
            require __DIR__ . '/../views/layouts/' . $this->layout . '.php';
        } else {
            throw new \ErrorException('View "' . $path . '" not found!');
        }
    }

}
