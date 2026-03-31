<?php

declare(strict_types=1);

return [
    // Database
    'Doctrine\ORM\EntityManager' => function ($container) {
        $config = require __DIR__ . '/doctrine.php';
        return \Doctrine\ORM\EntityManager::create($config['connection'], $config['orm']);
    },

    // Logger
    'src\Logger\Logger' => function ($container) {
        $logger = new \src\Logger\Logger('app');
        $logger->pushHandler(new \Monolog\Handler\RotatingFileHandler(
            __DIR__ . '/../logs/app.log',
            7,
            \Monolog\Logger::DEBUG
        ));
        return $logger;
    },

    // Cache
    'src\Cache\CacheManager' => function ($container) {
        return new \src\Cache\CacheManager('file', [
            'directory' => __DIR__ . '/../cache',
        ]);
    },

    // Validator
    'src\Validator\Validator' => function ($container) {
        return new \src\Validator\Validator();
    },

    // Twig Environment
    'Twig\Environment' => function ($container) {
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../views/templates');
        $twig = new \Twig\Environment($loader, [
            'cache' => __DIR__ . '/../cache/twig',
            'debug' => $_ENV['APP_DEBUG'] ?? false,
            'auto_reload' => true,
        ]);
        
        // Add global variables
        $twig->addGlobal('app', [
            'name' => $_ENV['APP_NAME'] ?? 'MVC App',
            'url' => $_ENV['APP_URL'] ?? 'http://localhost',
        ]);
        
        return $twig;
    },

    // Session
    'src\Service\SessionService' => function ($container) {
        return new \src\Service\SessionService();
    },

    // Auth Service
    'src\Service\AuthService' => function ($container) {
        return new \src\Service\AuthService(
            $container->get('Doctrine\ORM\EntityManager'),
            $container->get('src\Service\SessionService')
        );
    },
];
