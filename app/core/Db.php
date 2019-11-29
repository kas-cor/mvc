<?php

namespace app\core;

use Doctrine\ORM\{EntityManager, ORMException};
use Doctrine\ORM\Tools\Setup;

/**
 * Class Db
 * @package app\core
 */
class Db {

    /**
     * @var array DB params
     */
    protected $params;

    /**
     * Db constructor
     * @param array $params
     */
    public function __construct($params) {
        $this->params = $params;
    }

    /**
     * Component initialisation
     * @return array
     * @throws ORMException
     */
    public function init() {
        $isDevMode = true;
        $config = Setup::createAnnotationMetadataConfiguration([__DIR__ . "/../models"], $isDevMode);

        $conn = [
            'driver' => 'pdo_mysql',
            'host' => $this->params['config']['host'],
            'dbname' => $this->params['config']['dbname'],
            'user' => $this->params['config']['username'],
            'password' => $this->params['config']['password'],
        ];

        return [
            'em' => EntityManager::create($conn, $config),
            'pagination' => $this->params['pagination'],
        ];
    }

}
