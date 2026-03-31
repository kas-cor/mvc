<?php

namespace app\core;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
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
    public function __construct(array $params) {
        $this->params = $params;
    }

    /**
     * Component initialisation
     * @return array
     * @throws ORMException
     */
    public function init(): array {
        // Get dev mode from environment or config
        $isDevMode = (bool)env('DEV_MODE', true);
        
        // Use modern attribute metadata configuration instead of annotations
        $config = Setup::createAttributeMetadataConfiguration(
            [__DIR__ . "/../models"], 
            $isDevMode
        );

        $conn = [
            'driver' => 'pdo_mysql',
            'host' => $this->params['config']['host'],
            'dbname' => $this->params['config']['dbname'],
            'user' => $this->params['config']['username'],
            'password' => $this->params['config']['password'],
            'charset' => 'utf8mb4',
        ];

        return [
            'em' => EntityManager::create($conn, $config),
            'pagination' => $this->params['pagination'],
        ];
    }

}
