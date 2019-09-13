<?php /** @noinspection PhpIncludeInspection */

namespace app\core;

use ErrorException;

/**
 * Class View
 * @package app\core
 */
class View {

    /**
     * Layout name
     * @var string
     */
    public $layout = 'main';

    /**
     * Path to view
     * @var array
     */
    public $path;

    /**
     * View name
     * @var string
     */
    public $view;

    /**
     * View constructor
     * @param string $path
     * @param string $view
     */
    public function __construct($path, $view) {
        $this->path = $path;
        $this->view = $view;
    }

    /**
     * Render view
     * @param array $vars
     * @throws ErrorException
     */
    public function render($vars) {
        $path = __DIR__ . '/../views/' . $this->path['controller'] . '/' . $this->view . '.php';
        if (file_exists($path)) {
            extract($vars);
            ob_start();
            require $path;
            /** @noinspection PhpUnusedLocalVariableInspection */
            $content = ob_get_clean();
            require __DIR__ . '/../views/layouts/' . $this->layout . '.php';
        } else {
            throw new ErrorException('View "' . $path . '" not found!');
        }
    }

}
