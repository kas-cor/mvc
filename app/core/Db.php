<?php

namespace app\core;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class Db {

    protected $params;

    public function __construct($params) {
        $this->params = $params;
    }

    public function init() {
        $isDevMode = true;
        $config = Setup::createAnnotationMetadataConfiguration([__DIR__ . "/../models"], $isDevMode);

        $conn = array(
            'driver' => 'pdo_mysql',
            'host' => $this->params['config']['host'],
            'dbname' => $this->params['config']['dbname'],
            'user' => $this->params['config']['username'],
            'password' => $this->params['config']['password'],
        );

        return [
            'em' => EntityManager::create($conn, $config),
            'paginations' => $this->params['paginations'],
        ];
    }

}
