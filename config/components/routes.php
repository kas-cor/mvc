<?php

return [
    // Default routes (available for all HTTP methods)
    '' => [
        'controller' => 'main',
        'action' => 'index',
    ],
    'main/sort' => [
        'controller' => 'main',
        'action' => 'sort',
    ],
    
    // Admin routes
    'admin' => [
        'controller' => 'admin',
        'action' => 'index',
    ],
    'admin/login' => [
        'controller' => 'admin',
        'action' => 'login',
    ],
    'admin/logout' => [
        'controller' => 'admin',
        'action' => 'logout',
    ],
    
    // API routes (example)
    'api/tasks' => [
        'controller' => 'api',
        'action' => 'tasks',
        'middleware' => ['auth', 'csrf'],
    ],
];
