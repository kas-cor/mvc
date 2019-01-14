<?php

namespace app\core;

class Base {

    public $config;
    public static $components = [];
    public static $request = [];

    public function __construct($config) {
        $this->config = $config;
    }

    public function run() {
        // Filtring request
        $this->genRequest();
        
        // Components loading
        foreach ($this->config['components'] as $name => $config) {
            if (class_exists($config['class'])) {
                $component = new $config['class']($config);
                static::$components[$name] = $component->init();
            } else {
                throw new \ErrorException('Compotent "' . $name . '" not found!');
            }
        }
    }

    static function convRequest($data) {
        if (is_array($data)) {
            return array_map("self::convRequest", $data);
        } else {
            return is_string($data) ? trim(htmlspecialchars(stripslashes($data))) : $data;
        }
    }

    private function genRequest() {
        static::$request['get'] = $this->convRequest($_GET);
        static::$request['post'] = $this->convRequest($_POST);
        static::$request['request'] = $this->convRequest($_REQUEST);
    }

}
