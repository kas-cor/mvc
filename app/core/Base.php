<?php

namespace app\core;

use ErrorException;

/**
 * Class Base
 * @package app\core
 */
class Base {

    /**
     * @var array App config
     */
    public $config;

    /**
     * @var array App components
     */
    public static $components = [];

    /**
     * @var array App requests
     */
    public static $request = [];

    /**
     * Base constructor
     * @param array $config
     */
    public function __construct($config) {
        $this->config = $config;
    }

    /**
     * App running
     * @throws ErrorException
     */
    public function run() {
        // Filtering request
        $this->genRequest();

        // Components loading
        foreach ($this->config['components'] as $name => $config) {
            if (class_exists($config['class'])) {
                $component = new $config['class']($config);
                static::$components[$name] = $component->init();
            } else {
                throw new ErrorException('Component "' . $name . '" not found!');
            }
        }
    }

    /**
     * Filtering request
     * @param array|string $data
     * @return array|string
     */
    static function convRequest($data) {
        if (is_array($data)) {
            return array_map("self::convRequest", $data);
        } else {
            return is_string($data) ? trim(htmlspecialchars(stripslashes($data))) : $data;
        }
    }

    /**
     * Getting requests
     */
    private function genRequest() {
        static::$request['get'] = $this->convRequest($_GET);
        static::$request['post'] = $this->convRequest($_POST);
        static::$request['request'] = $this->convRequest($_REQUEST);
    }

}
